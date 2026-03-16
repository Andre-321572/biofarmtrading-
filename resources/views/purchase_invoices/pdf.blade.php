<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bordereau #{{ $purchaseInvoice->bon_no }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 8.5px; color: #333; line-height: 1.1; }
        
        .header { width: 100%; margin-bottom: 5px; }
        .logo { width: 90px; vertical-align: middle; }
        .company-info { text-align: center; vertical-align: middle; padding-bottom: 10px; }
        .company-name { font-size: 26px; font-weight: 900; color: #38a169; letter-spacing: 2px; }
        .company-desc { font-size: 9px; color: #4a5568; font-weight: 1000; margin-top: 2px; }
        .date-box { text-align: right; vertical-align: top; width: 100px; }
        .date-label { font-size: 7px; font-weight: bold; color: #999; text-transform: uppercase; }
        .date-val { font-size: 11px; font-weight: 900; margin-top: 2px; border-bottom: 1px solid #333; padding-bottom: 2px; }

        .doc-title-container { border: 2.5px solid #1a202c; padding: 4px; border-radius: 4px; text-align: center; margin: 10px 0; }
        .doc-title { font-size: 18px; font-weight: 1000; letter-spacing: 12px; text-transform: uppercase; margin: 0; }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; border: 1.5px solid #1a202c; }
        .info-table td { border: 1px solid #718096; padding: 3.5px 6px; font-size: 9px; }
        .info-label { font-weight: bold; text-transform: uppercase; color: #4a5568; width: 15%; font-size: 8.5px; }
        .info-value { font-weight: 1000; color: #000; width: 35%; }
        .highlight-label { color: #dd6b20; font-weight: bold; }
        .highlight-value { color: #dd6b20; font-weight: 1000; }

        .weight-header { background-color: #1a202c; color: white; text-align: center; padding: 5px; font-size: 11px; font-weight: 1000; letter-spacing: 10px; text-transform: uppercase; margin-bottom: 0px; }

        .weight-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .weight-table th { background-color: #4a5568; color: white; font-size: 6.5px; padding: 3px; border: 1px solid #1a202c; text-align: center; }
        .weight-table td { border: 1px solid #cbd5e0; padding: 1.5px 4px; text-align: center; height: 11px; font-size: 7.5px; font-weight: 1000; }
        .index-cell { color: #3182ce; font-weight: 900; background-color: #f7fafc; width: 12%; }
        .poids-cell { width: 13%; }
        .total-row td { background-color: #edf2f7; font-weight: 1000; border: 1.2px solid #1a202c; padding: 3px; }

        .financial-grid { width: 100%; margin-top: 10px; }
        .fin-box { width: 49%; vertical-align: top; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 0; }
        .fin-table { width: 100%; border-collapse: collapse; }
        .fin-table td { padding: 5px 8px; border-bottom: 0.5px solid #e2e8f0; font-size: 9px; }
        .fin-table tr:last-child td { border-bottom: none; }
        .fin-label { font-weight: bold; text-transform: uppercase; color: #718096; font-size: 8px; }
        .fin-val { text-align: right; font-weight: 1000; font-size: 10px; }
        .net-a-payer-row { background-color: #ebf8ff; color: #2b6cb0; border-top: 2px solid #2b6cb0; }
        .net-label { font-size: 10px; font-weight: 1000; text-transform: uppercase; }
        .net-val { font-size: 13px; font-weight: 1000; }
        .letter-box { padding: 8px; border-bottom: 0.5px solid #e2e8f0; font-style: italic; color: #4a5568; }

        .sign-container { width: 100%; margin-top: 10px; }
        .sign-box { width: 49%; height: 80px; border: 0.5px solid #edf2f7; vertical-align: top; text-align: center; padding: 5px; }
        .sign-title { font-weight: 1000; text-transform: uppercase; color: #2d3748; font-size: 9px; margin-bottom: 10px; display: block; }
        .sign-img { height: 45px; max-width: 100%; mix-blend-multiply: multiply; }
        .sign-footer { font-size: 6.5px; color: #a0aec0; border-top: 0.5px dotted #e2e8f0; margin-top: 5px; padding-top: 3px; }

        .footer { text-align: center; font-size: 7px; color: #a0aec0; margin-top: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td width="120"><img src="{{ public_path('images/logo.jpg') }}" class="logo"></td>
            <td class="company-info">
                <div class="company-name">BIO FARM TRADING</div>
                <div class="company-desc">Production-Commercialisation de produits agricoles biologiques</div>
            </td>
            <td class="date-box">
                <div class="date-label">Date</div>
                <div class="date-val">{{ $purchaseInvoice->date_invoice->format('d/m/Y') }}</div>
                <div class="date-label" style="margin-top:5px">Bon N°</div>
                <div class="date-val" style="border:none">{{ $purchaseInvoice->bon_no }}</div>
            </td>
        </tr>
    </table>

    <div class="doc-title-container">
        <h2 class="doc-title">FACTURE D'ACHAT</h2>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Zone</td>
            <td class="info-value">{{ $purchaseInvoice->zone ?: '—' }}</td>
            <td class="info-label">Producteur</td>
            <td class="info-value">{{ $purchaseInvoice->producteur ?: '—' }}</td>
        </tr>
        <tr>
            <td class="info-label">Chauffeur</td>
            <td class="info-value">{{ $purchaseInvoice->chauffeur ?: '—' }}</td>
            <td class="info-label">Matricule</td>
            <td class="info-value">{{ $purchaseInvoice->code_parcelle_matricule ?: '—' }}</td>
        </tr>
        <tr>
            <td class="info-label">Fruit</td>
            <td class="info-value" colspan="3">{{ $purchaseInvoice->fruit ?: '—' }}</td>
        </tr>
        <tr>
            <td class="info-label highlight-label">% Avarie</td>
            <td class="info-value highlight-value">{{ number_format($purchaseInvoice->avarie_pct, 2) }} %</td>
            <td class="info-label highlight-label" style="background-color: #fffaf0">Poids Marchand</td>
            <td class="info-value highlight-value" style="background-color: #fffaf0">{{ number_format($purchaseInvoice->poids_marchand_total, 2, ',', ' ') }} kg</td>
        </tr>
    </table>

    <div class="weight-header">Relevé de Poids</div>
    
    @php
        $allWeights = $purchaseInvoice->weights->sortBy('position')->values();
        $cols = 8;
        $maxRows = 25; // 200 / 8
    @endphp

    <table class="weight-table">
        <thead>
            <tr>
                @for($c=0; $c < $cols; $c++)
                    <th width="4%">N°</th><th width="8.5%">Poids/Cal</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @for($r=0; $r < $maxRows; $r++)
            <tr>
                @for($c=0; $c < $cols; $c++)
                    @php 
                        $idx = $r + ($c * $maxRows);
                        $w = $allWeights->get($idx);
                    @endphp
                    <td class="index-cell">{{ $w ? str_pad($w->position, 3, '0', STR_PAD_LEFT) : '' }}</td>
                    <td class="poids-cell">
                        @if($w && $w->weight > 0)
                            {{ number_format($w->weight, 1) }}<span style="font-size: 4.5px; opacity:0.6">[{{ $w->calibre }}]</span>
                        @endif
                    </td>
                @endfor
            </tr>
            @endfor
            <tr class="total-row">
                @for($c=0; $c < $cols; $c++)
                    @php $colSum = $allWeights->slice($c * $maxRows, $maxRows)->sum('weight'); @endphp
                    <td>T</td><td>{{ number_format($colSum, 2) }}</td>
                @endfor
            </tr>
        </tbody>
    </table>

    @php
        $avarieMod = (1 - ($purchaseInvoice->avarie_pct / 100));
        $weightPF = $allWeights->where('calibre', 'PF')->sum('weight');
        $weightGF = $allWeights->where('calibre', 'GF')->sum('weight');
        $poidsMarchandPF = $weightPF * $avarieMod;
        $poidsMarchandGF = $weightGF * $avarieMod;
        $montantTotalFruis = ($poidsMarchandPF * ($purchaseInvoice->pu_pf ?? 0)) + ($poidsMarchandGF * ($purchaseInvoice->pu_gf ?? 0));
        $totalPrime = $purchaseInvoice->total_weight * ($purchaseInvoice->prime_bio_kg ?? 0);
        $montantTotalBrut = $montantTotalFruis + $totalPrime;
    @endphp

    <table class="financial-grid">
        <tr>
            <td class="fin-box" style="border-right:none">
                <table class="fin-table">
                    <tr><td class="fin-label">Poids Total</td><td class="fin-val">{{ number_format($purchaseInvoice->total_weight, 2, ',', ' ') }} kg</td></tr>
                    <tr><td class="fin-label">P.U PF</td><td class="fin-val" style="color:#3182ce">{{ number_format($purchaseInvoice->pu_pf) }} <small>FCFA/kg</small></td></tr>
                    <tr><td class="fin-label">P.U GF</td><td class="fin-val" style="color:#dd6b20">{{ number_format($purchaseInvoice->pu_gf) }} <small>FCFA/kg</small></td></tr>
                    <tr><td class="fin-label">Montant Total Fruits</td><td class="fin-val">{{ number_format($montantTotalFruis, 0, ',', ' ') }} FCFA</td></tr>
                    <tr class="net-a-payer-row">
                        <td class="net-label">Net à Payer</td>
                        <td class="net-val">{{ number_format($purchaseInvoice->net_a_payer, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr><td class="fin-label">Prime Bio/kg</td><td class="fin-val">{{ number_format($purchaseInvoice->prime_bio_kg) }} <small>FCFA/kg</small></td></tr>
                </table>
            </td>
            <td width="2%"></td>
            <td class="fin-box">
                <table class="fin-table">
                    <tr><td class="fin-label">Poids Marchand Petit Fruit</td><td class="fin-val" style="color:#3182ce">{{ number_format($poidsMarchandPF, 2, ',', ' ') }} kg</td></tr>
                    <tr><td class="fin-label">Poids Marchand Gros Fruit</td><td class="fin-val" style="color:#dd6b20">{{ number_format($poidsMarchandGF, 2, ',', ' ') }} kg</td></tr>
                    <tr><td class="fin-label">Total Crédit</td><td class="fin-val" style="color:#e53e3e">{{ number_format($purchaseInvoice->total_credit, 0, ',', ' ') }} FCFA</td></tr>
                    <tr>
                        <td colspan="2">
                            <div style="font-size: 7px; color: #999; text-transform: uppercase;">Net à payer en lettre :</div>
                            <div style="font-weight: 1000; font-size: 9px; margin-top: 2px;">{{ $purchaseInvoice->net_payer_lettre ?: '—' }}</div>
                        </td>
                    </tr>
                    <tr><td class="fin-label">Montant Total de la Prime</td><td class="fin-val">{{ number_format($totalPrime, 0, ',', ' ') }} FCFA</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="sign-container">
        <tr>
            <td class="sign-box">
                <span class="sign-title">Bio Farm Trading / Responsable</span>
                @if($purchaseInvoice->signature_resp)
                    <img src="{{ $purchaseInvoice->signature_resp }}" class="sign-img">
                @endif
                <div class="sign-footer">Signature & Cachet</div>
            </td>
            <td width="2%"></td>
            <td class="sign-box">
                <span class="sign-title">Le Producteur</span>
                @if($purchaseInvoice->signature_prod)
                    <img src="{{ $purchaseInvoice->signature_prod }}" class="sign-img">
                @endif
                <div class="sign-footer">Signature</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Généré le {{ now()->format('d/m/Y à H:i') }} par {{ Auth::user()->name ?? 'Achat Coopérative' }} - BIO FARM TRADING
    </div>

</body>
</html>