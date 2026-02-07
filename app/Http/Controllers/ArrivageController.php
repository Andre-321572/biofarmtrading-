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
            'details' => 'required|array|min:1',
            'details.*.fruit' => 'required|string|in:ananas,papaye',
            'details.*.variete' => 'nullable|string',
            'details.*.poids' => 'required|numeric|min:0.01',
        ]);

        $arrivage = Arrivage::create([
            'chauffeur' => $validated['chauffeur'],
            'matricule_camion' => $validated['matricule_camion'],
            'date_arrivage' => $validated['date_arrivage'],
            'zone_provenance' => $validated['zone_provenance'],
            'user_id' => Auth::id(),
        ]);

        foreach ($validated['details'] as $detail) {
            $arrivage->details()->create([
                'fruit' => $detail['fruit'],
                'variete' => $detail['variete'] ?? 'non_applicable',
                'poids' => $detail['poids'],
            ]);
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
}
