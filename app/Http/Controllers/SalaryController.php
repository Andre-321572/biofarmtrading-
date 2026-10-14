<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\PayrollService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

use App\Models\Setting;

class SalaryController extends Controller
{
    protected PayrollService $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
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
        $prefix = $isAdmin ? 'admin.' : 'rh.';

        return view('admin.salaries.index', array_merge($payrollData, [
            'selectedMonth' => $monthCarbon->format('Y-m'),
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
            'selectedShift' => $shift,
            'search' => $search,
            'prefix' => $prefix,
            'canEditRate' => $isAdmin,
        ]));
    }

    /**
     * Met à jour le taux horaire appliqué aux ouvriers (réservé à l'Admin).
     */
    public function updateRate(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, "Action non autorisée. Seul l'administrateur peut modifier le taux horaire.");
        }

        $validated = $request->validate([
            'hourly_rate' => 'required|integer|min:1|max:100000',
        ], [
            'hourly_rate.required' => 'Le taux horaire est obligatoire.',
            'hourly_rate.integer' => 'Le taux horaire doit être un nombre entier valide.',
            'hourly_rate.min' => 'Le taux horaire doit être supérieur ou égal à 1 FCFA.',
            'hourly_rate.max' => 'Le taux horaire ne peut pas dépasser 100 000 FCFA.',
        ]);

        $newRate = (int) $validated['hourly_rate'];

        Setting::set(
            'worker_hourly_rate',
            $newRate,
            'integer',
            'Taux horaire standard des ouvriers Bio Farm Trading (en FCFA)'
        );

        return redirect()->back()->with('success', "Le taux horaire a été mis à jour à " . number_format($newRate, 0, ',', ' ') . " FCFA/heure avec succès. Tous les salaires ont été automatiquement recalculés.");
    }

    /**
     * Génère et télécharge la fiche de paie mensuelle au format PDF pour le comptable.
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
