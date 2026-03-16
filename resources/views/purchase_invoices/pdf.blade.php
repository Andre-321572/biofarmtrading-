<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ str_pad($purchaseInvoice->id, 7, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 0.8cm 1cm; }
        body { 
            font-family: 'Helvetica', sans-serif; 
            font-size: 8.5px; 
            color: #333;
            line-height: 1.2;
        }
        
        /* Header Section */
        .header-container { width: 100%; margin-bottom: 5px; position: relative; height: 80px; }
        .logo-box { position: absolute; left: 0; top: 0; }
        .logo { width: 75px; } 
        .header-center { text-align: center; width: 100%; }
        .company-name { font-size: 22px; font-weight: 1000; letter-spacing: 4px; margin-bottom: 0px; margin-top: 5px; color: #1a1a1a; }
        .company-desc { font-size: 7.5px; color: #555; margin-bottom: 8px; font-style: italic; }
        .document-title { 
            font-size: 13px; 
            font-weight: bold; 
            border-top: 1.5px solid #000; 
            border-bottom: 1.5px solid #000;
            padding: 4px 0;
            margin: 5px auto;
            width: 70%;
            text-transform: uppercase;
        }
        .bon-info { position: absolute; right: 0; top: 0; text-align: right; }
        .bon-label { font-size: 7px; color: #999; font-weight: bold; text-transform: uppercase; }
        .bon-number { font-size: 15px; font-weight: 900; margin-bottom: 5px; }
        .bon-date { font-size: 10px; font-weight: bold; }

        /* Info Grid */
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 10px; border: 1.5px solid #333; }
        .info-grid td { 
            border: 1px solid #999; 
            padding: 3px 6px; 
        }
        .info-label { font-weight: bold; text-transform: uppercase; font-size: 7.5px; background-color: #f0f0f0; width: 18%; }
        .info-value { font-weight: bold; color: #000; font-size: 9px; width: 32%; }

        /* Table Title */
        .weight-log-title { 
            background-color: #1e293b; 
            color: white; 
            text-align: center; 
            font-weight: bold; 
            padding: 5px; 
            letter-spacing: 8px;
            text-transform: uppercase;
            font-size: 10px;
            margin-bottom: 0px;
        }

        /* Weight Table */
        .weight-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .weight-table th { 
            background-color: #f2f2f2; 
            border: 1px solid #1e293b; 
            padding: 4px; 
            font-size: 8px;
            text-align: center;
            text-transform: uppercase;
        }
        .weight-table td { 
            border: 1px solid #ddd; 
            padding: 2.5px 5px; 
            text-align: center;
            height: 12px;
            font-size: 7.5px;
        }
        .weight-table .index-col { font-weight: bold; color: #3b82f6; background-color: #fbfbfb; border-left: 1px solid #ccc; width: 15%; }
        .weight-table .poids-col { width: 18.3%; font-weight: bold; }
        .weight-table .cal-tag { font-size: 5px; margin-left: 2px; }
        
        /* Total Row */
        .total-row td { 
            background-color: #334155; 
            color: white; 
            font-weight: bold; 
            border: 1px solid #1e293b;
            padding: 3px;
        }

        /* Financial Section */
        .financial-container { width: 100%; margin-top: 5px; border-collapse: collapse; }
        .financial-box { border: 1.5px solid #1e293b; padding: 0; vertical-align: top; }
        .financial-table { width: 100%; border-collapse: collapse; }
        .financial-table td { border-bottom: 0.5px solid #eee; padding: 4px 10px; font-size: 8.5px; }
        .financial-table tr:last-child td { border-bottom: none; }
        .fin-label { font-weight: bold; text-transform: uppercase; color: #64748b; font-size: 7.5px; width: 65%; }
        .fin-value { text-align: right; font-weight: 1000; font-size: 9px; color: #0f172a; }
        
        .net-payable-box { background-color: #ecfdf5; border-top: 1.5px solid #1e293b; padding: 6px 10px; }
        .net-label { font-size: 10px; font-weight: 1000; text-transform: uppercase; color: #065f46; letter-spacing: 1px; }
        .net-value { font-size: 14px; font-weight: 1000; color: #065f46; float: right; }

        .in-words-box { padding: 4px 10px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; }
        .words-label { font-size: 6px; color: #94a3b8; font-style: italic; text-transform: uppercase; }
        .words-value { font-weight: bold; font-style: italic; font-size: 9px; color: #334155; display: block; margin-top: 2px; }

        /* Signature Section */
        .signature-table { width: 100%; margin-top: 15px; border-collapse: collapse; }
        .signature-box { width: 48%; border: 1px dotted #cbd5e1; height: 75px; padding: 5px; vertical-align: top; position: relative; }
        .signature-title { font-weight: bold; text-transform: uppercase; font-size: 8px; color: #475569; margin-bottom: 5px; display: block; }
        .signature-img { height: 45px; width: auto; mix-blend-multiply: multiply; display: block; margin: 0 auto; }
        .signature-hint { position: absolute; bottom: 5px; left: 0; right: 0; text-align: center; font-size: 6.5px; color: #94a3b8; border-top: 0.5px solid #f1f5f9; padding-top: 2px; margin: 0 10px; }

        /* Bottom Footer */
        .small-footer { text-align: center; margin-top: 15px; font-size: 7px; color: #94a3b8; border-top: 0.5px solid #f1f5f9; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header-container">
        <div class="logo-box">
            <img src="{{ public_path('images/logo.jpg') }}" class="logo">
        </div>
        <div class="header-center">
            <div class="company-name">BIO FARM TRADING</div>
            <div class="company-desc">Production-Commercialisation de produits agricoles biologiques - Conseils - Formations en Agrobusiness</div>
            <div class="document-title">Facture d'Achat N° {{ str_pad($purchaseInvoice->id, 10, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="bon-info">
            <div class="bon-label">BON N°</div>
            <div class="bon-number">#{{ $purchaseInvoice->bon_no }}</div>
            <div class="bon-label">DATE</div>
            <div class="bon-date">{{ $purchaseInvoice->date_invoice->format('d/m/Y') }}</div>
        </div>
    </div>

    <table class="info-grid">
        <tr>
            <td class="info-label">Producteur / OP</td>
            <td class="info-value" colspan="3">{{ $purchaseInvoice->producteur ?: '—' }}</td>
        </tr>
        <tr>
            <td class="info-label">Préfecture / Zone</td>
            <td class="info-value" colspan="3">{{ $purchaseInvoice->zone ?: '—' }}</td>
        </tr>
        <tr>
            <td class="info-label">Chauffeur</td>
            <td class="info-value">{{ $purchaseInvoice->chauffeur ?: '—' }}</td>
            <td class="info-label">Matricule Camion</td>
            <td class="info-value">{{ $purchaseInvoice->code_parcelle_matricule ?: '—' }}</td>
        </tr>
        <tr>
            <td class="info-label">Fruit</td>
            <td class="info-value">{{ $purchaseInvoice->fruit ?: '—' }}</td>
            <td class="info-label" style="background-color:#fff5f5; color:#b91c1c">% Avarie</td>
            <td class="info-value" style="color:#b91c1c">{{ number_format($purchaseInvoice->avarie_pct, 1) }} %</td>
        </tr>
    </table>

    <div class="weight-log-title">Relevé de Poids</div>

    @php 
        $allWeights = $purchaseInvoice->weights->sortBy('position')->values();
        $count = $allWeights->count();
        $rowsPerCol = ceil($count / 3);
        if ($rowsPerCol < 10) $rowsPerCol = 10;
        
        $poidsPF = $allWeights->where('calibre', 'PF')->sum('weight');
        $poidsGF = $allWeights->where('calibre', 'GF')->sum('weight');
        $avarieMod = (1 - ($purchaseInvoice->avarie_pct / 100));
        $poidsMarchandPF = $poidsPF * $avarieMod;
        $poidsMarchandGF = $poidsGF * $avarieMod;
        $totalPrime = $purchaseInvoice->total_weight * ($purchaseInvoice->prime_bio_kg ?? 0);
        $montantMarchand = ($poidsMarchandPF * ($purchaseInvoice->pu_pf ?? 0)) + ($poidsMarchandGF * ($purchaseInvoice->pu_gf ?? 0));
    @endphp

    <table class="weight-table">
        <thead>
            <tr>
                <th width="8%">N°</th><th width="25.33%">Poids kg</th>
                <th width="8%">N°</th><th width="25.33%">Poids kg</th>
                <th width="8%">N°</th><th width="25.34%">Poids kg</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < $rowsPerCol; $i++)
            <tr>
                {{-- Column 1 --}}
                @php $idx1 = $i; $item1 = $allWeights->get($idx1); @endphp
                <td class="index-col">{{ $item1 ? str_pad($item1->position, 2, '0', STR_PAD_LEFT) : '' }}</td>
                <td class="poids-col">
                    {{ $item1 ? number_format($item1->weight, 2) : '—' }}
                    @if($item1)<span class="cal-tag" style="color: {{ $item1->calibre == 'GF' ? '#b45309' : '#4f46e5' }};">[{{ $item1->calibre }}]</span>@endif
                </td>
                
                {{-- Column 2 --}}
                @php $idx2 = $i + $rowsPerCol; $item2 = $allWeights->get($idx2); @endphp
                <td class="index-col">{{ $item2 ? str_pad($item2->position, 2, '0', STR_PAD_LEFT) : '' }}</td>
                <td class="poids-col">
                    {{ $item2 ? number_format($item2->weight, 2) : '—' }}
                    @if($item2)<span class="cal-tag" style="color: {{ $item2->calibre == 'GF' ? '#b45309' : '#4f46e5' }};">[{{ $item2->calibre }}]</span>@endif
                </td>
                
                {{-- Column 3 --}}
                @php $idx3 = $i + (2 * $rowsPerCol); $item3 = $allWeights->get($idx3); @endphp
                <td class="index-col">{{ $item3 ? str_pad($item3->position, 2, '0', STR_PAD_LEFT) : '' }}</td>
                <td class="poids-col">
                    {{ $item3 ? number_format($item3->weight, 2) : '—' }}
                    @if($item3)<span class="cal-tag" style="color: {{ $item3->calibre == 'GF' ? '#b45309' : '#4f46e5' }};">[{{ $item3->calibre }}]</span>@endif
                </td>
            </tr>
            @endfor
            
            <tr class="total-row">
                @php
                    $sum1 = $allWeights->slice(0, $rowsPerCol)->sum('weight');
                    $sum2 = $allWeights->slice($rowsPerCol, $rowsPerCol)->sum('weight');
                    $sum3 = $allWeights->slice(2 * $rowsPerCol)->sum('weight');
                @endphp
                <td>T</td><td>{{ number_format($sum1, 2) }}</td>
                <td>T</td><td>{{ number_format($sum2, 2) }}</td>
                <td>T</td><td>{{ number_format($sum3, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="financial-container">
        <tr>
            <td class="financial-box" style="width: 48%; border-right: none;">
                <table class="financial-table">
                    <tr>
                        <td class="fin-label">Poids Brut Total</td>
                        <td class="fin-value">{{ number_format($purchaseInvoice->total_weight, 2) }} kg</td>
                    </tr>
                    <tr>
                        <td class="fin-label">Poids Marchand PF <span style="font-weight: normal; text-transform: none;">({{ number_format($purchaseInvoice->pu_pf ?? 0) }} /kg)</span></td>
                        <td class="fin-value">{{ number_format($poidsMarchandPF, 2) }} kg</td>
                    </tr>
                    <tr>
                        <td class="fin-label">Poids Marchand GF <span style="font-weight: normal; text-transform: none;">({{ number_format($purchaseInvoice->pu_gf ?? 0) }} /kg)</span></td>
                        <td class="fin-value">{{ number_format($poidsMarchandGF, 2) }} kg</td>
                    </tr>
                    <tr>
                        <td class="fin-label">Prime Biologique total</td>
                        <td class="fin-value" style="color:#059669">+ {{ number_format($totalPrime, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </table>
            </td>
            <td style="width: 4%;"></td>
            <td class="financial-box" style="width: 48%;">
                <table class="financial-table">
                    <tr>
                        <td class="fin-label">Retrait Crédit / Avance</td>
                        <td class="fin-value" style="color:#dc2626">- {{ number_format($purchaseInvoice->total_credit, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td class="fin-label">Valeur Marchandises</td>
                        <td class="fin-value">{{ number_format($montantMarchand, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="net-payable-box">
                            <span class="net-label">Net à Payer</span>
                            <span class="net-value">{{ number_format($purchaseInvoice->net_a_payer, 0, ',', ' ') }} FCFA</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="in-words-box">
                            <span class="words-label">Montant en toutes lettres :</span>
                            <span class="words-value">{{ $purchaseInvoice->net_payer_lettre ?: '—' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <td class="signature-box">
                <span class="signature-title">A2C SAM / Responsable</span>
                @if($purchaseInvoice->signature_resp)
                    <img src="{{ $purchaseInvoice->signature_resp }}" class="signature-img">
                @endif
                <span class="signature-hint">Signature & Cachet</span>
            </td>
            <td style="width: 4%;"></td>
            <td class="signature-box">
                <span class="signature-title">Le Producteur / Livreur</span>
                @if($purchaseInvoice->signature_prod)
                    <img src="{{ $purchaseInvoice->signature_prod }}" class="signature-img">
                @endif
                <span class="signature-hint">Signature</span>
            </td>
        </tr>
    </table>

    <div class="small-footer">
        BIO FARM TRADING - NIF 1002966783 - Tél : (+229) 97562640 / 97264340 - Capital 51 000 000 FCFA - www.biofarmtrading.com<br>
        Généré le {{ now()->format('d/m/Y à H:i') }} par {{ Auth::user()->name ?? 'Système' }} · Document Officiel Bio Farm
    </div>

</body>
</html>