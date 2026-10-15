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

        $validated = $request->validate([
            'hourly_rate' => 'nullable|numeric|min:1|max:100000',
            'new_hourly_rate' => 'nullable|numeric|min:1|max:100000',
        ], [
            'hourly_rate.numeric' => 'Le taux horaire standard doit être un nombre valide.',
            'hourly_rate.min' => 'Le taux horaire standard doit être supérieur ou égal à 1 FCFA.',
            'new_hourly_rate.numeric' => 'Le taux horaire des nouveaux ouvriers doit être un nombre valide.',
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
     * Supprime une prime mensuelle d'un ouvrier.
     */
    public function destroyBonus(WorkerBonus $bonus)
    {
        if (!$this->checkAuthorizedUser()) {
            abort(403, "Action non autorisée.");
        }

        $bonus->delete();

        return redirect()->back()->with('success', "La prime a été supprimée avec succès.");
    }

    /**
     * Affiche la liste dédiée des nouveaux ouvriers et leur gestion tarifaire.
     */
    public function newWorkers(Request $request)
    {
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
        $isRh = Auth::check() && Auth::user()->isRh();
        $prefix = $isAdmin ? 'admin.' : ($isRh ? 'rh.' : '');

        // Récupérer les ouvriers : en cours d'essai, explicitement is_new ou embauchés depuis moins de 60 jours
        $cutoffDate = Carbon::now()->subDays(60);
        $newWorkers = Worker::where(function ($q) use ($cutoffDate) {
            $q->where('is_new', true)
              ->orWhere('hired_at', '>=', $cutoffDate->toDateString())
              ->orWhere(function ($sub) use ($cutoffDate) {
                  $sub->whereNull('hired_at')
                      ->where('created_at', '>=', $cutoffDate);
              });
        })
        ->orderByRaw('CASE WHEN is_new = 1 THEN 0 ELSE 1 END')
        ->orderBy('hired_at', 'desc')
        ->orderBy('created_at', 'desc')
        ->orderBy('last_name', 'asc')
        ->get();

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

        $validated = $request->validate([
            'is_new' => 'nullable|in:0,1,true,false,null',
            'hired_at' => 'nullable|date',
            'hourly_rate' => 'nullable|numeric|min:0|max:100000',
        ]);

        if ($request->has('is_new')) {
            $val = $request->input('is_new');
            if ($val === '' || $val === 'null' || is_null($val)) {
                $worker->is_new = null; // Détection automatique 14 jours
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
     * Génère et télécharge la fiche de paie mensuelle au format PDF pour émargement.
     * REMARQUE STRICTE (Audio 2) : La colonne du Taux Horaire est masquée pour préserver la confidentialité salariale.
     */
    public function pdf(Request $request)
    {
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
        $fileName = 'Etat_Salaires_Ouvriers_' . $monthName . '.pdf';

        return $pdf->download($fileName);
    }
}
