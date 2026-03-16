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
            'zone' => 'nullable|string',
            'chauffeur' => 'nullable|string',
            'fruit' => 'nullable|string',
            'producteur' => 'nullable|string',
            'code_parcelle_matricule' => 'nullable|string',
            'calibre' => 'nullable|string',
            'pu_pf' => 'nullable|numeric|min:0',
            'pu_gf' => 'nullable|numeric|min:0',
            'prime_bio_kg' => 'nullable|numeric|min:0',
            'avarie_pct' => 'nullable|numeric|min:0|max:100',
            'total_credit' => 'nullable|numeric|min:0',
            'signature_resp' => 'nullable|string',
            'signature_prod' => 'nullable|string',
            'net_payer_lettre' => 'nullable|string',
            'weights' => 'nullable|array',
            'weights.*' => 'nullable|numeric|min:0',
            'calibres' => 'nullable|array',
            'calibres.*' => 'nullable|string|in:PF,GF',
            'weights_csv' => 'nullable|string',
            'calibres_csv' => 'nullable|string',
        ]);

        $invoice = PurchaseInvoice::create([
            'bon_no' => $validated['bon_no'],
            'date_invoice' => $validated['date_invoice'],
            'zone' => $validated['zone'] ?? null,
            'chauffeur' => $validated['chauffeur'] ?? null,
            'fruit' => $validated['fruit'] ?? null,
            'producteur' => $validated['producteur'] ?? null,
            'code_parcelle_matricule' => $validated['code_parcelle_matricule'] ?? null,
            'calibre' => null, // Global calibre field is deprecated
            'pu_pf' => $validated['pu_pf'] ?? 0,
            'pu_gf' => $validated['pu_gf'] ?? 0,
            'prime_bio_kg' => $validated['prime_bio_kg'] ?? 0,
            'avarie_pct' => $validated['avarie_pct'] ?? 0,
            'total_credit' => $validated['total_credit'] ?? 0,
            'signature_resp' => $validated['signature_resp'] ?? null,
            'signature_prod' => $validated['signature_prod'] ?? null,
            'poids_avarie' => 0,
            'poids_marchand' => 0,
            'net_payer_lettre' => $validated['net_payer_lettre'] ?? null,
            'user_id' => Auth::id(),
        ]);

        \Log::info("Full Request Data:", $request->all());

        // Logic combined to match weights and calibres (CSV + Arrays fallback)
        $rawWeights = [];
        $rawCalibres = [];

        // 1. Try CSV (Highest priority/reliability)
        if ($request->filled('weights_csv')) {
            $rawWeights = explode(',', $request->input('weights_csv'));
        }
        if ($request->filled('calibres_csv')) {
            $rawCalibres = explode(',', $request->input('calibres_csv'));
        }

        // 2. Fallback to standard arrays if CSV was empty or incomplete
        if (empty(array_filter($rawWeights))) {
            $rawWeights = $request->input('weights', []);
        }
        if (empty(array_filter($rawCalibres))) {
            $rawCalibres = $request->input('calibres', []);
        }

        $savedCount = 0;
        for ($i = 0; $i < 200; $i++) {
            $weight = $rawWeights[$i] ?? null;
            $weightVal = (float)$weight;
            
            if ($weightVal > 0) {
                $calibreRaw = $rawCalibres[$i] ?? 'PF';
                $calibre = strtoupper(trim($calibreRaw));
                if ($calibre !== 'GF') $calibre = 'PF';
                
                $invoice->weights()->create([
                    'position' => $i + 1,
                    'weight'   => $weightVal,
                    'calibre'  => $calibre,
                ]);
                $savedCount++;
            }
        }

        if ($savedCount === 0) {
            $invoice->delete(); // Clean up incomplete invoice
            return back()->withInput()->with('error', 'ERREUR : Aucun poids n\'a été reçu par le serveur. Veuillez réessayer ou contacter le support.');
        }

        \Log::info("Purchase Invoice stored: $savedCount weights saved.");

        // Calcul automatique avarie & poids marchand (totaux) pour le cache DB
        $totalWeight  = $invoice->total_weight;
        $avariePct    = (float)($request->input('avarie_pct') ?? 0);
        $poidsAvarie  = round(($totalWeight * $avariePct) / 100, 2);
        $poidsMarchand = round($totalWeight - $poidsAvarie, 2);

        $invoice->update([
            'poids_avarie'   => $poidsAvarie,
            'poids_marchand' => $poidsMarchand,
        ]);

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
