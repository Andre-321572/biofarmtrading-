<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture d'Achat - {{ $purchaseInvoice->bon_no }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .container { padding: 20px; }
        .header { margin-bottom: 20px; }
        .logo { width: 60px; height: 60px; float: left; margin-right: 15px; }
        .company-info { float: left; width: 350px; }
        .company-name { font-size: 18px; font-weight: bold; margin-bottom: 2px; }
        .company-tagline { font-size: 9px; color: #666; font-weight: bold; }
        .bon-info { float: right; text-align: right; }
        .bon-no { font-size: 20px; font-weight: bold; display: block; }
        .bon-date { font-size: 14px; font-weight: bold; }
        .title { clear: both; text-align: center; border-bottom: 2px solid #000; padding: 10px 0; margin-bottom: 20px; }
        .title h1 { margin: 0; letter-spacing: 5px; text-transform: uppercase; font-size: 18px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { border: 1px solid #ddd; padding: 6px 10px; }
        .label { background: #f9f9f9; font-weight: bold; text-transform: uppercase; width: 130px; font-size: 8px; color: #555; }
        .value { background: #fff; font-weight: bold; }
        
        .releve-title { background: #1a202c; color: #fff; text-align: center; padding: 4px; text-transform: uppercase; font-weight: bold; letter-spacing: 5px; margin-bottom: 1px; font-size: 9px; }
        
        .weight-table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 10px; }
        .weight-table th { background: #4a5568; color: #fff; padding: 2px; font-size: 7px; border: 0.5px solid #1a202c; }
        .weight-table td { border: 0.5px solid #ddd; padding: 1px 2px; text-align: center; font-size: 7.5px; height: 10px; }
        .weight-table .num { background: #f3f4f6; font-weight: bold; color: #4338ca; width: 6%; }
        .weight-table .weight { font-weight: bold; width: 14%; }
        .weight-table .total-row td { background: #f9fafb; font-weight: bold; border-top: 1.5px solid #4a5568; font-size: 7.5px; }
        
        .summary-section { width: 100%; margin-top: 5px; }
        .summary-left, .summary-right { width: 49.5%; float: left; }
        .summary-right { float: right; }
        
        .summary-table td { border: 1px solid #ddd; padding: 4px 8px; }
        .summary-table .label { width: 130px; font-size: 7.5px; }
        .summary-table .amount { text-align: right; font-weight: bold; font-size: 9px; }
        .summary-table .net-payable { background: #f0fdf4; color: #166534; font-size: 11px; }
        
        .signatures { clear: both; margin-top: 15px; }
        .signature-box { width: 48%; float: left; border: 0.5px solid #ddd; padding: 5px; height: 60px; text-align: center; }
        .signature-box.right { float: right; }
        .signature-label { font-weight: bold; display: block; margin-bottom: 35px; text-transform: uppercase; font-size: 8px; }
        .signature-hint { color: #888; font-size: 6px; border-top: 1px dotted #ddd; display: block; padding-top: 2px; }
        
        .footer { clear: both; text-align: center; color: #999; font-size: 7px; margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-info">
                <div class="company-name">BIO FARM TRADING</div>
                <div class="company-tagline">PRODUCTION-COMMERCIALISATION DE PRODUITS AGRICOLES BIOLOGIQUES<br>CONSEILS - FORMATIONS EN AGROBUSINESS</div>
            </div>
            <div class="bon-info">
                <span style="font-size: 7px; color: #888; font-weight: bold;">BON N°</span>
                <span class="bon-no" style="font-size: 16px;">#{{ $purchaseInvoice->bon_no }}</span>
                <span style="font-size: 7px; color: #888; font-weight: bold;">DATE</span><br>
                <span class="bon-date" style="font-size: 12px;">{{ $purchaseInvoice->date_invoice->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="title" style="padding: 5px 0; margin-bottom: 10px;">
            <h1 style="font-size: 15px;">Facture d'Achat</h1>
        </div>

        <table class="info-table" style="margin-bottom: 10px;">
            <tr>
                <td class="label">PRÉFECTURE</td>
                <td class="value">{{ $purchaseInvoice->prefecture ?? '—' }}</td>
                <td class="label">OP</td>
                <td class="value">{{ $purchaseInvoice->op ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">ZONE</td>
                <td class="value">{{ $purchaseInvoice->zone ?? '—' }}</td>
                <td class="label">PRODUCTEUR</td>
                <td class="value">{{ $purchaseInvoice->producteur ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">CHAUFFEUR</td>
                <td class="value">{{ $purchaseInvoice->chauffeur ?? '—' }}</td>
                <td class="label">CODE PARCELLE / MATRICULE</td>
                <td class="value">{{ $purchaseInvoice->code_parcelle_matricule ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">FRUIT</td>
                <td class="value">{{ $purchaseInvoice->fruit ?? '—' }}</td>
                <td class="label">QUANTITÉ ESTIMÉE</td>
                <td class="value" style="text-align: right;">{{ number_format($purchaseInvoice->quantite_estimee, 2, ',', ' ') }} kg</td>
            </tr>
        </table>

        <div class="releve-title">Relevé de Poids</div>
        
        @php 
            $allWeights = $purchaseInvoice->weights->sortBy('position')->values();
            $count = $allWeights->count();
            $numCols = 5;
            $rowsPerCol = ceil(max($count, 50) / $numCols); // Ensure at least some rows if few data
            if ($rowsPerCol > 40) $rowsPerCol = 40; // Max 40 rows per col to stay on one page (5 cols * 40 = 200)
        @endphp

        <table class="weight-table">
            <thead>
                <tr>
                    @for($c = 0; $c < $numCols; $c++)
                    <th class="num">N°</th><th class="weight">Poids kg</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @for($row=0; $row < $rowsPerCol; $row++)
                <tr>
                    @for($c = 0; $c < $numCols; $c++)
                        @php $idx = $row + ($c * $rowsPerCol); @endphp
                        <td class="num">{{ isset($allWeights[$idx]) ? str_pad($allWeights[$idx]->position, 3, '0', STR_PAD_LEFT) : '' }}</td>
                        <td class="weight">{{ isset($allWeights[$idx]) ? number_format($allWeights[$idx]->weight, 2, ',', ' ') : '—' }}</td>
                    @endfor
                </tr>
                @endfor
            </tbody>
            <tfoot>
                <tr class="total-row">
                    @for($c = 0; $c < $numCols; $c++)
                    <td>T</td>
                    <td>
                        @php
                            $colSum = $allWeights->slice($c * $rowsPerCol, $rowsPerCol)->sum('weight');
                        @endphp
                        {{ number_format($colSum, 2, ',', ' ') }}
                    </td>
                    @endfor
                </tr>
            </tfoot>
        </table>

        <div class="summary-section">
            <div class="summary-left">
                <table class="summary-table">
                    <tr>
                        <td class="label">POIDS TOTAL</td>
                        <td class="amount">{{ number_format($purchaseInvoice->total_weight, 2, ',', ' ') }} kg</td>
                    </tr>
                    <tr>
                        <td class="label">P.U</td>
                        <td class="amount">{{ number_format($purchaseInvoice->pu, 0, ',', ' ') }} FCFA/kg</td>
                    </tr>
                    <tr>
                        <td class="label">MONTANT TOTAL</td>
                        <td class="amount" style="color: #15803d;">{{ number_format($purchaseInvoice->montant_total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td class="label" style="font-weight: black; background: #dcfce7;">NET À PAYER</td>
                        <td class="amount net-payable">{{ number_format($purchaseInvoice->net_a_payer, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td class="label">PRIME BIO/KG</td>
                        <td class="amount" style="color: #4f46e5;">{{ number_format($purchaseInvoice->prime_bio_kg, 0, ',', ' ') }} FCFA/kg</td>
                    </tr>
                </table>
            </div>
            <div class="summary-right">
                <table class="summary-table">
                    <tr>
                        <td class="label">Poids Avarié</td>
                        <td class="amount" style="color: #b91c1c;">{{ number_format($purchaseInvoice->poids_avarie, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td class="label">Poids marchand (Poids net)</td>
                        <td class="amount" style="color: #b91c1c;">{{ number_format($purchaseInvoice->poids_marchand, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td class="label">TOTAL CRÉDIT</td>
                        <td class="amount" style="color: #b91c1c;">{{ number_format($purchaseInvoice->total_credit, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="background: #f9f9f9; height: 35px;">
                            <span style="font-size: 7px; color: #888; text-transform: uppercase;">Net à payer en lettre :</span><br>
                            <span style="font-style: italic; font-weight: bold;">{{ $purchaseInvoice->net_payer_lettre ?? '—' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">MONTANT TOTAL DE LA PRIME</td>
                        <td class="amount" style="color: #4f46e5;">{{ number_format($purchaseInvoice->montant_total_prime, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="signatures">
            <div class="signature-box">
                <span class="signature-label">A2C SAM / Responsable</span>
                <span class="signature-hint">Signature & Cachet</span>
            </div>
            <div class="signature-box right">
                <span class="signature-label">Le Producteur</span>
                <span class="signature-hint">Signature</span>
            </div>
        </div>

        <div class="footer">
            GÉNÉRÉ LE {{ now()->format('d/m/Y à H:i') }} PAR {{ Auth::user()->name }} · BIO FARM TRADING
        </div>
    </div>
</body>
</html>
