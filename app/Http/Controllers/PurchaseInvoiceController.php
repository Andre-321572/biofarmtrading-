<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseInvoiceController extends Controller
{
    public function index()
    {
        $invoices = PurchaseInvoice::with('weights')
            ->orderBy('date_invoice', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('purchase_invoices.index', compact('invoices'));
    }

    public function create()
    {
        // Simple auto-increment for next BON N°
        $lastInvoice = PurchaseInvoice::latest()->first();
        $nextNumber = $lastInvoice ? intval(preg_replace('/[^0-9]/', '', $lastInvoice->bon_no)) + 1 : 1;
        $nextBonNo = str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
        
        return view('purchase_invoices.create', compact('nextBonNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bon_no' => 'required|string|unique:purchase_invoices',
            'date_invoice' => 'required|date',
            'prefecture' => 'nullable|string',
            'zone' => 'nullable|string',
            'chauffeur' => 'nullable|string',
            'fruit' => 'nullable|string',
            'op' => 'nullable|string',
            'producteur' => 'nullable|string',
            'code_parcelle_matricule' => 'nullable|string',
            'quantite_estimee' => 'nullable|numeric|min:0',
            'pu' => 'required|numeric|min:0',
            'prime_bio_kg' => 'nullable|numeric|min:0',
            'poids_avarie' => 'nullable|numeric|min:0',
            'poids_marchand' => 'nullable|numeric|min:0',
            'net_payer_lettre' => 'nullable|string',
            'weights' => 'required|array',
            'weights.*' => 'nullable|numeric|min:0',
        ]);

        $invoice = PurchaseInvoice::create([
            'bon_no' => $validated['bon_no'],
            'date_invoice' => $validated['date_invoice'],
            'prefecture' => $validated['prefecture'] ?? null,
            'zone' => $validated['zone'] ?? null,
            'chauffeur' => $validated['chauffeur'] ?? null,
            'fruit' => $validated['fruit'] ?? null,
            'op' => $validated['op'] ?? null,
            'producteur' => $validated['producteur'] ?? null,
            'code_parcelle_matricule' => $validated['code_parcelle_matricule'] ?? null,
            'quantite_estimee' => $validated['quantite_estimee'] ?? 0,
            'pu' => $validated['pu'],
            'prime_bio_kg' => $validated['prime_bio_kg'] ?? 0,
            'poids_avarie' => $validated['poids_avarie'] ?? 0,
            'poids_marchand' => $validated['poids_marchand'] ?? 0,
            'net_payer_lettre' => $validated['net_payer_lettre'] ?? null,
            'user_id' => Auth::id(),
        ]);

        foreach ($validated['weights'] as $index => $weight) {
            if ($weight > 0) {
                $invoice->weights()->create([
                    'position' => $index + 1,
                    'weight' => $weight,
                ]);
            }
        }

        return redirect()->route('purchase_invoices.index')->with('success', 'Facture d\'achat enregistrée avec succès.');
    }

    public function show(PurchaseInvoice $purchaseInvoice)
    {
        $purchaseInvoice->load('weights');
        return view('purchase_invoices.show', compact('purchaseInvoice'));
    }

    public function pdf(PurchaseInvoice $purchaseInvoice)
    {
        $purchaseInvoice->load('weights');
        
        // Ensure DomPDF is available
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'DomPDF n\'est pas installé correctement.');
        }

        $pdf = Pdf::loadView('purchase_invoices.pdf', compact('purchaseInvoice'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('facture-achat-' . $purchaseInvoice->bon_no . '.pdf');
    }
}
