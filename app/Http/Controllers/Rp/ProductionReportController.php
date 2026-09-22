<?php

namespace App\Http\Controllers\Rp;

use App\Http\Controllers\Controller;
use App\Models\ProductionReport;
use App\Models\ProductionReportLot;
use App\Exports\ProductionReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ProductionReportController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductionReport::with('lots', 'user')
            ->orderBy('date_rapport', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('lots_peles', 'like', "%{$search}%")
                  ->orWhereHas('lots', function($lq) use ($search) {
                      $lq->where('numero_lot', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_start')) {
            $query->whereDate('date_rapport', '>=', $request->date_start);
        }

        if ($request->filled('date_end')) {
            $query->whereDate('date_rapport', '<=', $request->date_end);
        }

        $reports = $query->paginate(15)->withQueryString();

        return view('rp.production_reports.index', compact('reports'));
    }

    public function create()
    {
        return view('rp.production_reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_rapport' => 'required|date',
            'nombre_ouvriers' => 'required|integer|min:0',
            'nombre_ouvriers_absents' => 'nullable|integer|min:0',
            'nombre_ouvriers_repos' => 'nullable|integer|min:0',
            'nombre_couteaux_debut' => 'nullable|integer|min:0',
            'nombre_couteaux_fin' => 'nullable|integer|min:0',
            'nombre_ciseaux_debut' => 'nullable|integer|min:0',
            'nombre_ciseaux_fin' => 'nullable|integer|min:0',
            'lots_peles' => 'nullable|string',
            'notes' => 'nullable|string',
            'lots' => 'required|array|min:1',
            'lots.*.numero_lot' => 'required|string|max:255',
            'lots.*.type_produit' => 'nullable|string|max:255',
            'lots.*.quantite_declayee_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_rcl_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_mcl_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_rps_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_mps_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_lpa_kg' => 'nullable|numeric|min:0',
            'lots.*.sachets_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_500g' => 'nullable|integer|min:0',
            'lots.*.sachets_rcl_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_rcl_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_mcl_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_mcl_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_rps_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_rps_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_mps_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_mps_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_lpa_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_lpa_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_lpa_100g' => 'nullable|integer|min:0',
            'lots.*.sachets_ttpm_1kg' => 'nullable|integer|min:0',
            'lots.*.type_coupe' => 'nullable|string|max:100',
            'lots.*.dechets_kg' => 'nullable|numeric|min:0',
            'lots.*.cl_ttpm_kg' => 'nullable|numeric|min:0',
            'lots.*.pa_kg' => 'nullable|numeric|min:0',
            'lots.*.pelage_info' => 'nullable|string',
            'lots.*.notes' => 'nullable|string',
        ]);

        // Auto-generate reference code PROD-YYYYMMDD-XXXX
        $dateStr = date('Ymd', strtotime($validated['date_rapport']));
        $countToday = ProductionReport::whereDate('date_rapport', $validated['date_rapport'])->count() + 1;
        $reference = 'PROD-' . $dateStr . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        $report = ProductionReport::create([
            'reference' => $reference,
            'date_rapport' => $validated['date_rapport'],
            'nombre_ouvriers' => $validated['nombre_ouvriers'],
            'nombre_ouvriers_absents' => $validated['nombre_ouvriers_absents'] ?? 0,
            'nombre_ouvriers_repos' => $validated['nombre_ouvriers_repos'] ?? 0,
            'nombre_couteaux_debut' => $validated['nombre_couteaux_debut'] ?? 0,
            'nombre_couteaux_fin' => $validated['nombre_couteaux_fin'] ?? 0,
            'nombre_ciseaux_debut' => $validated['nombre_ciseaux_debut'] ?? 0,
            'nombre_ciseaux_fin' => $validated['nombre_ciseaux_fin'] ?? 0,
            'lots_peles' => $validated['lots_peles'] ?? null,
            'user_id' => Auth::id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['lots'] as $lotData) {
            $report->lots()->create([
                'numero_lot' => $lotData['numero_lot'],
                'type_produit' => $lotData['type_produit'] ?? 'Ananas',
                'quantite_declayee_kg' => $lotData['quantite_declayee_kg'] ?? 0,
                'poids_rcl_kg' => $lotData['poids_rcl_kg'] ?? 0,
                'poids_mcl_kg' => $lotData['poids_mcl_kg'] ?? 0,
                'poids_rps_kg' => $lotData['poids_rps_kg'] ?? 0,
                'poids_mps_kg' => $lotData['poids_mps_kg'] ?? 0,
                'poids_lpa_kg' => $lotData['poids_lpa_kg'] ?? 0,
                'sachets_2kg' => $lotData['sachets_2kg'] ?? 0,
                'sachets_1kg' => $lotData['sachets_1kg'] ?? 0,
                'sachets_500g' => $lotData['sachets_500g'] ?? 0,
                'sachets_rcl_2kg' => $lotData['sachets_rcl_2kg'] ?? 0,
                'sachets_rcl_1kg' => $lotData['sachets_rcl_1kg'] ?? 0,
                'sachets_mcl_2kg' => $lotData['sachets_mcl_2kg'] ?? 0,
                'sachets_mcl_1kg' => $lotData['sachets_mcl_1kg'] ?? 0,
                'sachets_rps_2kg' => $lotData['sachets_rps_2kg'] ?? 0,
                'sachets_rps_1kg' => $lotData['sachets_rps_1kg'] ?? 0,
                'sachets_mps_2kg' => $lotData['sachets_mps_2kg'] ?? 0,
                'sachets_mps_1kg' => $lotData['sachets_mps_1kg'] ?? 0,
                'sachets_lpa_2kg' => $lotData['sachets_lpa_2kg'] ?? 0,
                'sachets_lpa_1kg' => $lotData['sachets_lpa_1kg'] ?? 0,
                'sachets_lpa_100g' => $lotData['sachets_lpa_100g'] ?? 0,
                'sachets_ttpm_1kg' => $lotData['sachets_ttpm_1kg'] ?? 0,
                'type_coupe' => $lotData['type_coupe'] ?? null,
                'dechets_kg' => $lotData['dechets_kg'] ?? 0,
                'cl_ttpm_kg' => $lotData['cl_ttpm_kg'] ?? 0,
                'pa_kg' => $lotData['pa_kg'] ?? 0,
                'pelage_info' => $lotData['pelage_info'] ?? null,
                'notes' => $lotData['notes'] ?? null,
            ]);
        }

        return redirect()->route('rp.production-reports.show', $report)
            ->with('success', 'Rapport journalier de production enregistré avec succès.');
    }

    public function show(ProductionReport $productionReport)
    {
        $productionReport->load('lots', 'user');
        return view('rp.production_reports.show', ['report' => $productionReport]);
    }

    public function edit(ProductionReport $productionReport)
    {
        $productionReport->load('lots');
        return view('rp.production_reports.edit', ['report' => $productionReport]);
    }

    public function update(Request $request, ProductionReport $productionReport)
    {
        $validated = $request->validate([
            'date_rapport' => 'required|date',
            'nombre_ouvriers' => 'required|integer|min:0',
            'nombre_ouvriers_absents' => 'nullable|integer|min:0',
            'nombre_ouvriers_repos' => 'nullable|integer|min:0',
            'nombre_couteaux_debut' => 'nullable|integer|min:0',
            'nombre_couteaux_fin' => 'nullable|integer|min:0',
            'nombre_ciseaux_debut' => 'nullable|integer|min:0',
            'nombre_ciseaux_fin' => 'nullable|integer|min:0',
            'lots_peles' => 'nullable|string',
            'notes' => 'nullable|string',
            'lots' => 'required|array|min:1',
            'lots.*.numero_lot' => 'required|string|max:255',
            'lots.*.type_produit' => 'nullable|string|max:255',
            'lots.*.quantite_declayee_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_rcl_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_mcl_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_rps_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_mps_kg' => 'nullable|numeric|min:0',
            'lots.*.poids_lpa_kg' => 'nullable|numeric|min:0',
            'lots.*.sachets_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_500g' => 'nullable|integer|min:0',
            'lots.*.sachets_rcl_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_rcl_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_mcl_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_mcl_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_rps_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_rps_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_mps_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_mps_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_lpa_2kg' => 'nullable|integer|min:0',
            'lots.*.sachets_lpa_1kg' => 'nullable|integer|min:0',
            'lots.*.sachets_lpa_100g' => 'nullable|integer|min:0',
            'lots.*.sachets_ttpm_1kg' => 'nullable|integer|min:0',
            'lots.*.type_coupe' => 'nullable|string|max:100',
            'lots.*.dechets_kg' => 'nullable|numeric|min:0',
            'lots.*.cl_ttpm_kg' => 'nullable|numeric|min:0',
            'lots.*.pa_kg' => 'nullable|numeric|min:0',
            'lots.*.pelage_info' => 'nullable|string',
            'lots.*.notes' => 'nullable|string',
        ]);

        $productionReport->update([
            'date_rapport' => $validated['date_rapport'],
            'nombre_ouvriers' => $validated['nombre_ouvriers'],
            'nombre_ouvriers_absents' => $validated['nombre_ouvriers_absents'] ?? 0,
            'nombre_ouvriers_repos' => $validated['nombre_ouvriers_repos'] ?? 0,
            'nombre_couteaux_debut' => $validated['nombre_couteaux_debut'] ?? 0,
            'nombre_couteaux_fin' => $validated['nombre_couteaux_fin'] ?? 0,
            'nombre_ciseaux_debut' => $validated['nombre_ciseaux_debut'] ?? 0,
            'nombre_ciseaux_fin' => $validated['nombre_ciseaux_fin'] ?? 0,
            'lots_peles' => $validated['lots_peles'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Re-create lots
        $productionReport->lots()->delete();

        foreach ($validated['lots'] as $lotData) {
            $productionReport->lots()->create([
                'numero_lot' => $lotData['numero_lot'],
                'type_produit' => $lotData['type_produit'] ?? 'Ananas',
                'quantite_declayee_kg' => $lotData['quantite_declayee_kg'] ?? 0,
                'poids_rcl_kg' => $lotData['poids_rcl_kg'] ?? 0,
                'poids_mcl_kg' => $lotData['poids_mcl_kg'] ?? 0,
                'poids_rps_kg' => $lotData['poids_rps_kg'] ?? 0,
                'poids_mps_kg' => $lotData['poids_mps_kg'] ?? 0,
                'poids_lpa_kg' => $lotData['poids_lpa_kg'] ?? 0,
                'sachets_2kg' => $lotData['sachets_2kg'] ?? 0,
                'sachets_1kg' => $lotData['sachets_1kg'] ?? 0,
                'sachets_500g' => $lotData['sachets_500g'] ?? 0,
                'sachets_rcl_2kg' => $lotData['sachets_rcl_2kg'] ?? 0,
                'sachets_rcl_1kg' => $lotData['sachets_rcl_1kg'] ?? 0,
                'sachets_mcl_2kg' => $lotData['sachets_mcl_2kg'] ?? 0,
                'sachets_mcl_1kg' => $lotData['sachets_mcl_1kg'] ?? 0,
                'sachets_rps_2kg' => $lotData['sachets_rps_2kg'] ?? 0,
                'sachets_rps_1kg' => $lotData['sachets_rps_1kg'] ?? 0,
                'sachets_mps_2kg' => $lotData['sachets_mps_2kg'] ?? 0,
                'sachets_mps_1kg' => $lotData['sachets_mps_1kg'] ?? 0,
                'sachets_lpa_2kg' => $lotData['sachets_lpa_2kg'] ?? 0,
                'sachets_lpa_1kg' => $lotData['sachets_lpa_1kg'] ?? 0,
                'sachets_lpa_100g' => $lotData['sachets_lpa_100g'] ?? 0,
                'sachets_ttpm_1kg' => $lotData['sachets_ttpm_1kg'] ?? 0,
                'type_coupe' => $lotData['type_coupe'] ?? null,
                'dechets_kg' => $lotData['dechets_kg'] ?? 0,
                'cl_ttpm_kg' => $lotData['cl_ttpm_kg'] ?? 0,
                'pa_kg' => $lotData['pa_kg'] ?? 0,
                'pelage_info' => $lotData['pelage_info'] ?? null,
                'notes' => $lotData['notes'] ?? null,
            ]);
        }

        return redirect()->route('rp.production-reports.show', $productionReport)
            ->with('success', 'Rapport journalier de production mis à jour avec succès.');
    }

    public function destroy(ProductionReport $productionReport)
    {
        $productionReport->delete();
        return redirect()->route('rp.production-reports.index')
            ->with('success', 'Rapport journalier de production supprimé.');
    }

    public function pdf(ProductionReport $productionReport)
    {
        $productionReport->load('lots', 'user');
        $pdf = Pdf::loadView('rp.production_reports.pdf', ['report' => $productionReport]);
        return $pdf->download('rapport-production-' . $productionReport->reference . '.pdf');
    }

    public function excel(ProductionReport $productionReport)
    {
        $productionReport->load('lots', 'user');
        return Excel::download(new ProductionReportExport($productionReport), 'rapport-production-' . $productionReport->reference . '.xlsx');
    }
}
