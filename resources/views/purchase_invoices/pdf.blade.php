<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture d'Achat - {{ $purchaseInvoice->bon_no }}</title>
    <style>
        @page { margin: 5mm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 9px; color: #333; margin: 0; padding: 0; line-height: 1.1; }
        .container { padding: 0; }
        
        /* Header */
        .header-outer { width: 100%; background: #ffffff; margin-bottom: 5px; display: block; border-bottom: 2px solid #3cb54a; }
        .header-inner { display: table; width: 100%; border-collapse: collapse; }
        .header-logo-cell { display: table-cell; vertical-align: middle; padding: 5px 10px; width: 60px; }
        .logo-img { height: 45px; width: auto; display: block; }
        .header-center-cell { display: table-cell; vertical-align: middle; text-align: center; padding: 5px 0; }
        .company-name { font-size: 22px; font-weight: bold; color: #3cb54a; letter-spacing: 1px; margin: 0; }
        .company-tagline { font-size: 8px; color: #333; font-weight: bold; margin: 0; }
        .header-date-cell { display: table-cell; vertical-align: middle; padding: 5px 10px; width: 80px; text-align: right; white-space: nowrap; }
        .bon-label { font-size: 6px; color: #666; font-weight: bold; text-transform: uppercase; display: block; }
        .bon-date { font-size: 10px; font-weight: bold; color: #000; display: block; margin-top: 1px; }
        .header-separator { display: none; }

        /* Title */
        .title-box { border: 1.5px solid #000; text-align: center; padding: 5px 0; margin-bottom: 10px; width: 100%; }
        .title-box h1 { margin: 0; letter-spacing: 8px; text-transform: uppercase; font-size: 16px; font-weight: bold; }
        
        /* Info Table */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; border: 1px solid #ccc; }
        .info-table td { border: 1px solid #ccc; padding: 3px 6px; font-size: 8px; }
        .info-table .label { background: #fdfdfd; font-weight: bold; text-transform: uppercase; width: 110px; color: #555; }
        .info-table .value { background: #fff; font-weight: bold; color: #000; }
        
        /* Weight Table Section */
        .releve-title { background: #1a202c; color: #fff; text-align: center; padding: 4px; text-transform: uppercase; font-weight: bold; letter-spacing: 5px; margin-bottom: 0px; font-size: 9px; }
        
        .weight-table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 3px; }
        .weight-table th { background: #4a5568; color: #fff; padding: 2px; font-size: 6px; border: 0.5px solid #1a202c; text-transform: uppercase; }
        .weight-table td { border: 0.5px solid #ddd; padding: 1px 2px; text-align: center; font-size: 7px; height: 10px; }
        .weight-table .num { color: #4338ca; font-weight: bold; width: 3.5%; background: #f9fafb; border-right: 0.5px solid #ccc; }
        .weight-table .weight { font-weight: bold; width: 9%; }
        .weight-table .total-row td { background: #f9fafb; font-weight: bold; border-top: 1px solid #4a5568; font-size: 7px; padding: 2px; }
        
        /* Summary Section */
        .summary-layout { width: 100%; margin-top: 5px; border-collapse: collapse; }
        .summary-layout td { vertical-align: top; padding: 0; }
        .summary-left { width: 49%; }
        .summary-right { width: 49%; text-align: right; }
        .spacer { width: 2%; }
        
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { border: 1px solid #ddd; padding: 3px 8px; font-size: 8px; }
        .summary-table .label { background: #f8f8f8; font-weight: bold; text-transform: uppercase; color: #555; width: 60%; }
        .summary-table .amount { text-align: right; font-weight: bold; font-size: 9px; color: #000; }
        .summary-table .amount-unit { font-size: 7px; color: #666; }
        
        .net-payable-row .label { background: #dcfce7 !important; color: #166534 !important; font-size: 9px; }
        .net-payable-row .amount { background: #dcfce7 !important; color: #166534 !important; font-size: 10px; font-weight: bold; }
        
        .right-label { text-align: left; }
        .danger-amount { color: #dc2626 !important; }
        .primary-amount { color: #2563eb !important; }
        
        .net-in-words-box { background: #f9fafb; border: 1px solid #ddd; padding: 5px; margin-top: -1px; text-align: left; min-height: 25px; }
        .net-in-words-label { font-size: 6px; color: #666; text-transform: uppercase; display: block; margin-bottom: 2px; font-style: italic; }
        .net-in-words-value { font-weight: bold; font-style: italic; font-size: 9px; color: #000; }

        /* Signatures */
        .signatures-container { margin-top: 30px; width: 100%; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-box { border: 0.5px dotted #ccc; height: 90px; padding: 10px; vertical-align: top; text-align: center; }
        .signature-title { font-weight: bold; text-transform: uppercase; font-size: 9px; display: block; margin-bottom: 40px; }
        .signature-hint { font-size: 7px; color: #999; border-top: 0.5px solid #eee; padding-top: 5px; }


    </style>
</head>
<body>

    <!-- Header SCOOPS OFCA style -->
    <!-- Header SCOOPS OFCA white style -->
    <div class="header-outer">
        <div class="header-inner">
            <div class="header-logo-cell">
                <img src="{{ public_path('images/ofca_logo.png') }}" class="logo-img" alt="Logo">
            </div>
            <div class="header-center-cell">
                <div class="company-name">SCOOPS OFCA</div>
                <div class="company-tagline">Production-Commercialisation de produits agricoles biologiques</div>
            </div>
            <div class="header-date-cell">
                <span class="bon-label">DATE</span>
                <span class="bon-date">{{ $purchaseInvoice->date_invoice->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>
    <div class="header-separator"></div>

    <div class="container">

        <!-- Title -->
        <div class="title-box" style="padding: 3px 0; margin-bottom: 5px;">
            <h1 style="font-size: 14px; letter-spacing: 8px;">Facture d'Achat</h1>
        </div>

        <!-- Info Grid -->
        <table class="info-table" style="margin-bottom: 10px;">
            <tr>
                <td class="label">ZONE</td>
                <td class="value">{{ $purchaseInvoice->zone ?? '—' }}</td>
                <td class="label">PRODUCTEUR</td>
                <td class="value">{{ $purchaseInvoice->producteur ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">CHAUFFEUR</td>
                <td class="value">{{ $purchaseInvoice->chauffeur ?? '—' }}</td>
                <td class="label">MATRICULE</td>
                <td class="value">{{ $purchaseInvoice->code_parcelle_matricule ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">FRUIT</td>
                <td class="value" colspan="3">{{ $purchaseInvoice->fruit ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label" style="background: #fff8ed; color: #c2410c;">% AVARIE</td>
                <td class="value" style="color: #c2410c; font-weight: 900;">{{ number_format($purchaseInvoice->avarie_pct ?? 0, 2, ',', ' ') }} %</td>
                <td class="label" style="background: #fff8ed; color: #c2410c;">POIDS MARCHAND</td>
                <td class="value" style="color: #374151; font-weight: 900;">{{ number_format($purchaseInvoice->poids_marchand_total, 2, ',', ' ') }} kg</td>
            </tr>
        </table>

        <!-- Weights Section -->
        <div class="releve-title" style="letter-spacing: 10px; font-size: 11px;">Relevé de Poids</div>
        
        @php 
            $allWeights = $purchaseInvoice->weights->sortBy('position')->values();
            $count = $allWeights->count();
            $numCols = 8;
            $rowsPerCol = ceil($count / $numCols);
            if ($rowsPerCol < 10) $rowsPerCol = 10;
        @endphp

        <table class="weight-table">
            <thead>
                <tr>
                    @for($c = 0; $c < $numCols; $c++)
                    <th class="num">N°</th><th class="weight">Poids/Cal</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @for($row=0; $row < $rowsPerCol; $row++)
                <tr>
                    @for($c = 0; $c < $numCols; $c++)
                        @php $idx = $row + ($c * $rowsPerCol); @endphp
                        <td class="num">{{ isset($allWeights[$idx]) ? str_pad($allWeights[$idx]->position, 3, '0', STR_PAD_LEFT) : '' }}</td>
                        <td class="weight" style="font-size: 6.5px;">
                            @if(isset($allWeights[$idx]))
                                {{ number_format($allWeights[$idx]->weight, 2, ',', ' ') }}
                                <span style="font-size: 5px; font-weight: bold; {{ $allWeights[$idx]->calibre == 'GF' ? 'color: #c2410c;' : 'color: #4338ca;' }}">
                                    [{{ $allWeights[$idx]->calibre }}]
                                </span>
                            @else
                                &nbsp;
                            @endif
                        </td>
                    @endfor
                </tr>
                @endfor
            </tbody>
            <tfoot>
                <tr class="total-row">
                    @for($c = 0; $c < $numCols; $c++)
                    <td style="background: #fdfdfd; color: #000; border-top: 1px solid #4a5568;">T</td>
                    <td style="background: #fdfdfd; color: #000; border-top: 1px solid #4a5568;">
                        @php
                            $colSum = $allWeights->slice($c * $rowsPerCol, $rowsPerCol)->sum('weight');
                        @endphp
                        {{ number_format($colSum, 2, ',', ' ') }}
                    </td>
                    @endfor
                </tr>
            </tfoot>
        </table>

        <!-- Summary Section -->
        <table class="summary-layout">
            <tr>
                <td class="summary-left" style="width: 45%;">
                    <table class="summary-table">
                        <tr>
                            <td class="label" style="background: #f9fafb;">POIDS TOTAL</td>
                            <td class="amount">{{ number_format($purchaseInvoice->total_weight, 2, ',', ' ') }} <span class="amount-unit">kg</span></td>
                        </tr>
                        <tr>
                            <td class="label" style="background: #eef2ff; font-size: 8px;">P.U PF</td>
                            <td class="amount" style="color: #4338ca;">{{ number_format($purchaseInvoice->pu_pf ?? 0, 0, ',', ' ') }} <span class="amount-unit">FCFA/kg</span></td>
                        </tr>
                        <tr>
                            <td class="label" style="background: #fffbeb; font-size: 8px;">P.U GF</td>
                            <td class="amount" style="color: #c2410c;">{{ number_format($purchaseInvoice->pu_gf ?? 0, 0, ',', ' ') }} <span class="amount-unit">FCFA/kg</span></td>
                        </tr>
                        <tr>
                            <td class="label" style="background: #f9fafb;">MONTANT TOTAL</td>
                            <td class="amount" style="color: #15803d;">{{ number_format($purchaseInvoice->montant_total, 0, ',', ' ') }} <span class="amount-unit">FCFA</span></td>
                        </tr>
                        <tr class="net-payable-row">
                            <td class="label" style="background: #e1f5fe !important; font-weight: 800; color: #01579b !important;">NET À PAYER</td>
                            <td class="amount" style="background: #e1f5fe !important; font-weight: 800; color: #01579b;">{{ number_format($purchaseInvoice->net_a_payer, 0, ',', ' ') }} <span class="amount-unit">FCFA</span></td>
                        </tr>
                        <tr>
                            <td class="label" style="background: #f9fafb;">PRIME BIO/KG</td>
                            <td class="amount" style="color: #4338ca;">{{ number_format($purchaseInvoice->prime_bio_kg, 0, ',', ' ') }} <span class="amount-unit">FCFA/kg</span></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 2%;"></td>
                <td class="summary-right" style="width: 53%;">
                    <table class="summary-table">
                        <tr>
                            <td class="label right-label" style="background: #f9fafb;">Poids marchand petit fruit</td>
                            <td class="amount" style="color: #4338ca;">{{ number_format($purchaseInvoice->poids_marchand_pf, 2, ',', ' ') }} <span class="amount-unit">kg</span></td>
                        </tr>
                        <tr>
                            <td class="label right-label" style="background: #f9fafb;">Poids marchand gros fruit</td>
                            <td class="amount" style="color: #c2410c;">{{ number_format($purchaseInvoice->poids_marchand_gf, 2, ',', ' ') }} <span class="amount-unit">kg</span></td>
                        </tr>
                        <tr>
                            <td class="label right-label" style="background: #f9fafb;">TOTAL CRÉDIT</td>
                            <td class="amount" style="color: #b91c1c;">{{ number_format($purchaseInvoice->total_credit, 0, ',', ' ') }} <span class="amount-unit">FCFA</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="background: #fdfdfd; padding: 5px 10px; height: 35px; border-top: 1px solid #ddd;">
                                <span style="font-size: 7px; color: #999; font-style: italic; text-transform: uppercase;">Net à payer en lettre :</span><br>
                                <span style="font-weight: bold; font-style: italic; font-size: 10px;">{{ $purchaseInvoice->net_payer_lettre ?? '—' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label right-label" style="background: #f9fafb;">MONTANT TOTAL DE LA PRIME</td>
                            <td class="amount" style="color: #4338ca;">{{ number_format($purchaseInvoice->montant_total_prime, 0, ',', ' ') }} <span class="amount-unit">FCFA</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Signatures -->
        <div class="signatures-container" style="margin-top: 5px;">
            <table class="signature-table">
                <tr>
                    <td class="signature-box" style="width: 48%; height: 70px; padding: 3px;">
                        <span class="signature-title" style="margin-bottom: 5px;">A2C OFCA / RESPONSABLE</span>
                        @if($purchaseInvoice->signature_resp)
                            <img src="{{ $purchaseInvoice->signature_resp }}" style="height: 40px; width: auto; mix-blend-multiply;">
                        @endif
                        <span class="signature-hint" style="margin-top: 5px; display: block;">Signature & Cachet</span>
                    </td>
                    <td style="width: 4%;"></td>
                    <td class="signature-box" style="width: 48%; height: 70px; padding: 3px;">
                        <span class="signature-title" style="margin-bottom: 5px;">LE PRODUCTEUR</span>
                        @if($purchaseInvoice->signature_prod)
                            <img src="{{ $purchaseInvoice->signature_prod }}" style="height: 40px; width: auto; mix-blend-multiply;">
                        @endif
                        <span class="signature-hint" style="margin-top: 5px; display: block;">Signature</span>
                    </td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; font-size: 7px; color: #999; margin-top: 5px;">
            GÉNÉRÉ LE {{ now()->format('d/m/Y à H:i') }} PAR {{ Auth::user()->name ?? 'Système' }} · SCOOPS OFCA
        </div>
    </div>
</body>
</html>
