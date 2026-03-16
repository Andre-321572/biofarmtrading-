<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseInvoiceController extends Controller
{
    /**
     * Affiche la liste des factures
     */
    public function index()
    {
        $invoices = PurchaseInvoice::with('weights') // Eager loading essentiel
            ->orderBy('date_invoice', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('purchase_invoices.index', compact('invoices'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        // Génération du prochain numéro de bon
        $lastInvoice = PurchaseInvoice::latest()->first();
        $nextNumber = $lastInvoice ? intval(preg_replace('/[^0-9]/', '', $lastInvoice->bon_no)) + 1 : 1;
        $nextBonNo = '#' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        return view('purchase_invoices.create', compact('nextBonNo'));
    }

    /**
     * Enregistre une nouvelle facture
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'bon_no' => 'required|string|unique:purchase_invoices',
            'date_invoice' => 'required|date',
            'zone' => 'nullable|string|max:255',
            'producteur' => 'nullable|string|max:255',
            'chauffeur' => 'nullable|string|max:255',
            'code_parcelle_matricule' => 'nullable|string|max:255',
            'fruit' => 'nullable|string|max:255',
            'avarie_pct' => 'nullable|numeric|min:0|max:100',
            'pu_pf' => 'nullable|numeric|min:0',
            'pu_gf' => 'nullable|numeric|min:0',
            'prime_bio_kg' => 'nullable|numeric|min:0',
            'total_credit' => 'nullable|numeric|min:0',
            'signature_resp' => 'nullable|string',
            'signature_prod' => 'nullable|string',
            'net_payer_lettre' => 'nullable|string',
            'weights_csv' => 'nullable|string',
            'calibres_csv' => 'nullable|string',
        ]);

        // Log pour déboguer
        Log::info('Création facture - Données reçues:', [
            'bon_no' => $validated['bon_no'],
            'weights_csv' => substr($request->input('weights_csv', ''), 0, 100) . '...',
            'calibres_csv' => substr($request->input('calibres_csv', ''), 0, 100) . '...'
        ]);

        // Création de la facture
        $invoice = PurchaseInvoice::create([
            'bon_no' => $validated['bon_no'],
            'date_invoice' => $validated['date_invoice'],
            'zone' => $validated['zone'] ?? null,
            'producteur' => $validated['producteur'] ?? null,
            'chauffeur' => $validated['chauffeur'] ?? null,
            'code_parcelle_matricule' => $validated['code_parcelle_matricule'] ?? null,
            'fruit' => $validated['fruit'] ?? null,
            'avarie_pct' => $validated['avarie_pct'] ?? 0,
            'pu_pf' => $validated['pu_pf'] ?? 0,
            'pu_gf' => $validated['pu_gf'] ?? 0,
            'prime_bio_kg' => $validated['prime_bio_kg'] ?? 0,
            'total_credit' => $validated['total_credit'] ?? 0,
            'signature_resp' => $validated['signature_resp'] ?? null,
            'signature_prod' => $validated['signature_prod'] ?? null,
            'net_payer_lettre' => $validated['net_payer_lettre'] ?? null,
            'user_id' => Auth::id(),
        ]);

        // Traitement des poids
        $savedCount = $this->processWeights($invoice, $request);

        if ($savedCount === 0) {
            $invoice->delete();
            return back()->withInput()->with('error', 'ERREUR : Aucun poids valide n\'a été enregistré.');
        }

        // Mise à jour des poids avariés et marchands
        $this->updateAvariePoids($invoice);

        Log::info("Facture #{$invoice->bon_no} créée avec {$savedCount} poids");

        return redirect()->route('purchase_invoices.index')
            ->with('success', 'Facture d\'achat enregistrée avec succès.');
    }

    /**
     * Traite les poids et calibres
     */
    private function processWeights(PurchaseInvoice $invoice, Request $request): int
    {
        $weights = [];
        $calibres = [];

        // Récupération depuis les CSV
        if ($request->filled('weights_csv')) {
            $weights = explode(',', $request->input('weights_csv'));
        }

        if ($request->filled('calibres_csv')) {
            $calibres = explode(',', $request->input('calibres_csv'));
        }

        // Fallback sur le tableau calibres si nécessaire
        if (empty($calibres) || count($calibres) < 200) {
            $calibres = $request->input('calibres', array_fill(0, 200, 'PF'));
        }

        $savedCount = 0;

        for ($i = 0; $i < 200; $i++) {
            $weight = isset($weights[$i]) ? trim($weights[$i]) : null;

            // Ne sauvegarder que les poids valides > 0
            if ($weight !== null && $weight !== '' && is_numeric($weight) && floatval($weight) > 0) {
                $weightVal = floatval($weight);

                // Détermination du calibre
                $calibreRaw = isset($calibres[$i]) ? trim($calibres[$i]) : 'PF';
                $calibre = strtoupper($calibreRaw) === 'GF' ? 'GF' : 'PF';

                $invoice->weights()->create([
                    'position' => $i + 1,
                    'weight' => $weightVal,
                    'calibre' => $calibre,
                ]);

                $savedCount++;
            }
        }

        return $savedCount;
    }

    /**
     * Met à jour les poids avariés et marchands
     */
    private function updateAvariePoids(PurchaseInvoice $invoice): void
    {
        $totalWeight = $invoice->total_weight;
        $avariePct = floatval($invoice->avarie_pct ?? 0);
        $poidsAvarie = round(($totalWeight * $avariePct) / 100, 2);
        $poidsMarchand = round($totalWeight - $poidsAvarie, 2);

        $invoice->update([
            'poids_avarie' => $poidsAvarie,
            'poids_marchand' => $poidsMarchand,
        ]);
    }

    /**
     * Affiche les détails d'une facture
     */
    public function show(PurchaseInvoice $purchaseInvoice)
    {
        // Les poids sont automatiquement chargés via le booted() du modèle
        return view('purchase_invoices.show', compact('purchaseInvoice'));
    }

    /**
     * Génère le PDF d'une facture
     */
    public function pdf(PurchaseInvoice $purchaseInvoice)
    {
        // Les poids sont automatiquement chargés
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'DomPDF n\'est pas installé correctement.');
        }

        $pdf = Pdf::loadView('purchase_invoices.pdf', compact('purchaseInvoice'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('facture-achat-' . $purchaseInvoice->bon_no . '.pdf');
    }

    /**
     * Supprime une facture
     */
    public function destroy(PurchaseInvoice $purchaseInvoice)
    {
        // Les poids seront automatiquement supprimés (cascade dans la migration)
        $purchaseInvoice->delete();

        return redirect()->route('purchase_invoices.index')
            ->with('success', 'Facture supprimée avec succès.');
    }

    /**
     * Statistiques rapides pour le dashboard
     */
    public function stats()
    {
        $stats = [
            'total_factures' => PurchaseInvoice::count(),
            'total_poids' => PurchaseInvoice::with('weights')->get()->sum('total_weight'),
            'total_montant' => PurchaseInvoice::get()->sum('net_a_payer'),
            'factures_mois' => PurchaseInvoice::whereMonth('date_invoice', now()->month)->count(),
        ];

        return response()->json($stats);
    }
}