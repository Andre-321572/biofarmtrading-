<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\PayrollService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\Worker;
use App\Models\WorkerBonus;

class SalaryController extends Controller
{
    protected PayrollService $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Vérifie si l'utilisateur connecté a le droit de gérer les salaires (Admin ou RH).
     */
    protected function checkAuthorizedUser(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        return $user->isAdmin() || $user->isRh();
    }

    /**
     * Affiche l'état récapitulatif des salaires des ouvriers pour un mois donné.
     */
    public function index(Request $request)
    {
        // Mois sélectionné (format YYYY-MM, défaut = mois courant)
        $monthInput = $request->input('month', Carbon::now()->format('Y-m'));
        
        try {
            $monthCarbon = Carbon::createFromFormat('Y-m', $monthInput);
        } catch (\Exception $e) {
            $monthCarbon = Carbon::now();
        }

        $shift = $request->input('shift', 'all');
        $search = $request->input('search');

        // Mois précédent et mois suivant pour navigation rapide
        $prevMonth = $monthCarbon->copy()->subMonth()->format('Y-m');
        $nextMonth = $monthCarbon->copy()->addMonth()->format('Y-m');

        // Récupération des calculs (taux dynamique chargé automatiquement via Setting)
        $payrollData = $this->payrollService->getMonthlyPayroll($monthCarbon, $shift, $search);

        // Détection du préfixe de route selon le rôle (rh ou admin)
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
        $isRh = Auth::check() && Auth::user()->isRh();
        $prefix = $isAdmin ? 'admin.' : ($isRh ? 'rh.' : '');
        $canEdit = $isAdmin || $isRh;

        // Ouvriers disponibles pour modal d'attribution des primes
        $allWorkers = Worker::orderBy('last_name', 'asc')->orderBy('first_name', 'asc')->get();

        return view('admin.salaries.index', array_merge($payrollData, [
            'selectedMonth' => $monthCarbon->format('Y-m'),
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
            'selectedShift' => $shift,
            'search' => $search,
            'prefix' => $prefix,
            'canEditRate' => $canEdit,
            'allWorkers' => $allWorkers,
        ]));
    }

    /**
     * Met à jour le taux horaire appliqué aux ouvriers (Accessible à l'Admin et au RH).
     */
    public function updateRate(Request $request)
    {
        if (!$this->checkAuthorizedUser()) {
            abort(403, "Action non autorisée. Seuls les administrateurs et RH peuvent modifier les taux horaires.");
        }

        // Normalisation automatique des nombres à virgule (remplacement de ',' par '.')
        if ($request->has('hourly_rate') && !is_null($request->input('hourly_rate'))) {
            $normalized = str_replace([' ', ','], ['', '.'], (string) $request->input('hourly_rate'));
            $request->merge(['hourly_rate' => $normalized === '' ? null : $normalized]);
        }
        if ($request->has('new_hourly_rate') && !is_null($request->input('new_hourly_rate'))) {
            $normalized = str_replace([' ', ','], ['', '.'], (string) $request->input('new_hourly_rate'));
            $request->merge(['new_hourly_rate' => $normalized === '' ? null : $normalized]);
        }

        $validated = $request->validate([
            'hourly_rate' => 'nullable|numeric|min:1|max:100000',
            'new_hourly_rate' => 'nullable|numeric|min:1|max:100000',
        ], [
            'hourly_rate.numeric' => 'Le taux horaire standard doit être un nombre valide (ex : 275 ou 275,50).',
            'hourly_rate.min' => 'Le taux horaire standard doit être supérieur ou égal à 1 FCFA.',
            'new_hourly_rate.numeric' => 'Le taux horaire des nouveaux ouvriers doit être un nombre valide (ex : 218,75).',
            'new_hourly_rate.min' => 'Le taux horaire des nouveaux ouvriers doit être supérieur ou égal à 1 FCFA.',
        ]);

        $messages = [];

        // Mise à jour du taux pour les anciens ouvriers
        if (!empty($validated['hourly_rate'])) {
            $newRate = (float) $validated['hourly_rate'];
            Setting::set(
                'worker_hourly_rate',
                $newRate,
                'float',
                'Taux horaire standard des anciens ouvriers Bio Farm Trading (en FCFA)'
            );
            $messages[] = "Taux régulier : " . number_format($newRate, 2, ',', ' ') . " FCFA/h";
        }

        // Mise à jour du taux pour les nouveaux ouvriers (période d'essai 14 jours)
        if (!empty($validated['new_hourly_rate'])) {
            $newRecruitRate = (float) $validated['new_hourly_rate'];
            Setting::set(
                'worker_new_hourly_rate',
                $newRecruitRate,
                'float',
                'Taux horaire des nouveaux ouvriers en période d\'essai Bio Farm Trading (en FCFA)'
            );
            $messages[] = "Taux période d'essai : " . number_format($newRecruitRate, 2, ',', ' ') . " FCFA/h";
        }

        $msgText = count($messages) > 0 ? implode(' • ', $messages) : 'Paramètres enregistrés.';

        return redirect()->back()->with('success', "Paramétrage des salaires mis à jour avec succès ({$msgText}). Tous les calculs ont été actualisés.");
    }

    /**
     * Attribue une prime mensuelle à un ou plusieurs ouvriers cochés.
     */
    public function storeBonus(Request $request)
    {
        if (!$this->checkAuthorizedUser()) {
            abort(403, "Action non autorisée.");
        }

        $validated = $request->validate([
            'worker_ids' => 'required|array|min:1',
            'worker_ids.*' => 'exists:workers,id',
            'month' => 'required|date_format:Y-m',
            'amount' => 'required|integer|min:1|max:1000000',
            'reason' => 'nullable|string|max:255',
        ], [
            'worker_ids.required' => 'Veuillez sélectionner au moins un ouvrier.',
            'worker_ids.min' => 'Veuillez cocher au moins un ouvrier pour la prime.',
            'amount.required' => 'Le montant de la prime est obligatoire.',
            'amount.integer' => 'Le montant doit être un entier en FCFA.',
            'amount.min' => 'Le montant de la prime doit être au moins 1 FCFA.',
        ]);

        $workerIds = $validated['worker_ids'];
        $month = $validated['month'];
        $amount = (int) $validated['amount'];
        $reason = $validated['reason'] ?? 'Prime mensuelle';
        $userId = Auth::id();

        $count = 0;
        foreach ($workerIds as $wId) {
            WorkerBonus::updateOrCreate(
                [
                    'worker_id' => $wId,
                    'month' => $month,
                ],
                [
                    'amount' => $amount,
                    'reason' => $reason,
                    'created_by' => $userId,
                ]
            );
            $count++;
        }

        return redirect()->back()->with('success', "Prime de " . number_format($amount, 0, ',', ' ') . " FCFA attribuée avec succès à {$count} ouvrier(s) pour le mois {$month}.");
    }

    /**
     * Supprime / résilie une prime mensuelle d'un ouvrier avec justification obligatoire.
     */
    public function destroyBonus(Request $request, WorkerBonus $bonus)
    {
        if (!$this->checkAuthorizedUser()) {
            abort(403, "Action non autorisée.");
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:3|max:500',
        ], [
            'cancellation_reason.required' => 'Veuillez préciser le motif de résiliation/suppression de la prime.',
            'cancellation_reason.min' => 'Le motif de résiliation doit comporter au moins 3 caractères.',
        ]);

        $workerName = $bonus->worker ? ($bonus->worker->last_name . ' ' . $bonus->worker->first_name) : 'l\'ouvrier';
        $amountFormatted = number_format($bonus->amount, 0, ',', ' ');
        $reason = trim($validated['cancellation_reason']);

        $bonus->cancellation_reason = $reason;
        $bonus->cancelled_by = Auth::id();
        $bonus->cancelled_at = now();
        $bonus->save();
        $bonus->delete();

        return redirect()->back()->with('success', "La prime de {$amountFormatted} FCFA de {$workerName} a été résiliée avec succès. Motif : « {$reason} ».");
    }

    /**
     * Affiche la liste dédiée des nouveaux ouvriers en période d'essai (14 jours).
     * Dès que les 2 semaines d'essai sont achevées, l'ouvrier bascule automatiquement dans les anciens et ne s'affiche plus ici.
     */
    public function newWorkers(Request $request)
    {
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
        $isRh = Auth::check() && Auth::user()->isRh();
        $prefix = $isAdmin ? 'admin.' : ($isRh ? 'rh.' : '');

        // Récupérer STRICTEMENT les ouvriers actuellement en période d'essai (14 jours)
        // Les ouvriers ayant achevé leurs 2 semaines d'essai basculent automatiquement dans les anciens et sont exclus.
        $newWorkers = Worker::query()
            ->orderBy('hired_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('last_name', 'asc')
            ->get()
            ->filter(function ($worker) {
                return $worker->isNew();
            })
            ->values();

        $defaultRate = PayrollService::getHourlyRate();
        $defaultNewRate = PayrollService::getNewHourlyRate();

        return view('admin.workers.new', compact('newWorkers', 'defaultRate', 'defaultNewRate', 'prefix'));
    }

    /**
     * Modifie le profil d'un ouvrier (statut essai/titularisé, date d'embauche ou taux personnalisé).
     */
    public function updateWorkerStatus(Request $request, Worker $worker)
    {
        if (!$this->checkAuthorizedUser()) {
            abort(403, "Action non autorisée.");
        }

        // Normalisation automatique des nombres à virgule pour le taux individuel
        if ($request->has('hourly_rate') && !is_null($request->input('hourly_rate'))) {
            $normalized = str_replace([' ', ','], ['', '.'], (string) $request->input('hourly_rate'));
            $request->merge(['hourly_rate' => $normalized === '' ? null : $normalized]);
        }

        $validated = $request->validate([
            'is_new' => 'nullable|in:0,1,true,false,null',
            'hired_at' => 'nullable|date',
            'hourly_rate' => 'nullable|numeric|min:0|max:100000',
        ]);

        if ($request->has('is_new')) {
            $val = $request->input('is_new');
            if ($val === '' || $val === 'null' || is_null($val)) {
                $worker->is_new = null; // Détection automatique 15 jours
            } else {
                $worker->is_new = filter_var($val, FILTER_VALIDATE_BOOLEAN);
            }
        }

        if ($request->has('hired_at')) {
            $worker->hired_at = !empty($validated['hired_at']) ? $validated['hired_at'] : null;
        }

        if ($request->has('hourly_rate')) {
            $rateVal = $validated['hourly_rate'];
            $worker->hourly_rate = ($rateVal !== null && $rateVal > 0) ? (float) $rateVal : null;
        }

        $worker->save();

        return redirect()->back()->with('success', "Le profil de l'ouvrier {$worker->last_name} {$worker->first_name} a été mis à jour.");
    }

    /**
     * Génère et télécharge l'état financier complet des salaires (Direction / Comptabilité).
     */
    public function pdf(Request $request)
    {
        if (!$this->checkAuthorizedUser()) {
            abort(403, "Action non autorisée.");
        }

        $monthInput = $request->input('month', Carbon::now()->format('Y-m'));

        try {
            $monthCarbon = Carbon::createFromFormat('Y-m', $monthInput);
        } catch (\Exception $e) {
            $monthCarbon = Carbon::now();
        }

        $shift = $request->input('shift', 'all');

        // Données complètes pour le PDF (sans filtre de recherche pour avoir l'état officiel complet)
        $payrollData = $this->payrollService->getMonthlyPayroll($monthCarbon, $shift);

        $dayItems = $payrollData['items']->where('shift', 'day');
        $nightItems = $payrollData['items']->where('shift', 'night');

        $pdf = Pdf::loadView('admin.salaries.pdf', array_merge($payrollData, [
            'dayItems' => $dayItems,
            'nightItems' => $nightItems,
            'generationDate' => Carbon::now(),
        ]))->setPaper('a4', 'landscape');

        $monthName = $monthCarbon->locale('fr')->translatedFormat('F_Y');
        $fileName = 'Etat_Financier_Salaires_' . $monthName . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Génère et télécharge la feuille d'émargement officielle des ouvriers.
     * REMARQUE STRICTE (Audio 4) : Les salaires, primes et montants en FCFA sont 100% masqués pour préserver la confidentialité lors de l'émargement collectif.
     */
    public function emargementPdf(Request $request)
    {
        if (!$this->checkAuthorizedUser()) {
            abort(403, "Action non autorisée.");
        }

        $monthInput = $request->input('month', Carbon::now()->format('Y-m'));

        try {
            $monthCarbon = Carbon::createFromFormat('Y-m', $monthInput);
        } catch (\Exception $e) {
            $monthCarbon = Carbon::now();
        }

        $shift = $request->input('shift', 'all');

        $payrollData = $this->payrollService->getMonthlyPayroll($monthCarbon, $shift);

        $dayItems = $payrollData['items']->where('shift', 'day');
        $nightItems = $payrollData['items']->where('shift', 'night');

        $pdf = Pdf::loadView('admin.salaries.emargement', array_merge($payrollData, [
            'dayItems' => $dayItems,
            'nightItems' => $nightItems,
            'generationDate' => Carbon::now(),
        ]))->setPaper('a4', 'landscape');

        $monthName = $monthCarbon->locale('fr')->translatedFormat('F_Y');
        $shiftSuffix = $shift !== 'all' ? '_' . ucfirst($shift) : '';
        $fileName = 'Fiche_Emargement_Ouvriers_' . $monthName . $shiftSuffix . '.pdf';

        return $pdf->download($fileName);
    }
}
