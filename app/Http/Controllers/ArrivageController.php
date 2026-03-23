<?php

namespace App\Http\Controllers;

use App\Models\Arrivage;
use App\Models\ArrivageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrivageExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ArrivageController extends Controller
{
    public function index()
    {
        $arrivages = Arrivage::with('details')
            ->orderBy('date_arrivage', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('arrivages.index', compact('arrivages'));
    }

    public function create()
    {
        return view('arrivages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chauffeur' => 'required|string|max:255',
            'matricule_camion' => 'required|string|max:255',
            'date_arrivage' => 'required|date',
            'zone_provenance' => 'required|string|max:255',
            'fruit_type' => 'required|string|max:255',
            'ph' => 'nullable|string|max:20',
            'brix' => 'nullable|string|max:20',
            'poids' => 'required|array',
            'poids.*' => 'nullable|numeric|min:0',
        ]);

        $arrivage = Arrivage::create([
            'chauffeur' => $validated['chauffeur'],
            'matricule_camion' => $validated['matricule_camion'],
            'date_arrivage' => $validated['date_arrivage'],
            'zone_provenance' => $validated['zone_provenance'],
            'ph' => $validated['ph'],
            'brix' => $validated['brix'],
            'user_id' => Auth::id(),
        ]);

        // Déterminer le fruit et la variété en fonction du type global
        $fruit_type = strtolower($validated['fruit_type']);
        $fruit = $fruit_type;
        $variete = 'normale';

        if (str_contains($fruit_type, 'ananas')) {
            $fruit = 'ananas';
            $variete = str_contains($fruit_type, 'braza') ? 'braza' : 'cayenne_lisse';
        } elseif (str_contains($fruit_type, 'papaye')) {
            $fruit = 'papaye';
            $variete = 'non_applicable';
        }

        // Enregistrer seulement les poids > 0
        foreach ($validated['poids'] as $p) {
            if ($p > 0) {
                $arrivage->details()->create([
                    'fruit' => $fruit,
                    'variete' => $variete,
                    'poids' => $p,
                ]);
            }
        }

        return redirect()->route('arrivages.index')->with('success', 'Arrivage enregistré avec succès.');
    }

    public function show(Arrivage $arrivage)
    {
        $arrivage->load('details');
        return view('arrivages.show', compact('arrivage'));
    }

    public function pdf(Arrivage $arrivage)
    {
        $arrivage->load('details');
        $pdf = Pdf::loadView('arrivages.pdf', compact('arrivage'));
        return $pdf->download('arrivage-' . $arrivage->id . '.pdf');
    }

    public function excel(Arrivage $arrivage)
    {
        $arrivage->load('details');
        return Excel::download(new ArrivageExport($arrivage), 'arrivage-' . $arrivage->id . '.xlsx');
    }

    public function edit(Arrivage $arrivage)
    {
        return view('arrivages.edit', compact('arrivage'));
    }

    public function update(Request $request, Arrivage $arrivage)
    {
        $validated = $request->validate([
            'custom_bon_ref' => 'nullable|string|max:255',
            'chauffeur' => 'required|string|max:255',
            'matricule_camion' => 'required|string|max:255',
            'date_arrivage' => 'required|date',
            'zone_provenance' => 'required|string|max:255',
            'ph' => 'nullable|string|max:20',
            'brix' => 'nullable|string|max:20',
        ]);

        $arrivage->update($validated);

        return redirect()->route('arrivages.index')->with('success', 'Entête de l\'arrivage modifiée avec succès.');
    }

    public function destroy(Arrivage $arrivage)
    {
        $arrivage->delete();
        return redirect()->route('arrivages.index')->with('success', 'Arrivage supprimé avec succès.');
    }
}
