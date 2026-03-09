<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Arrivage #{{ str_pad($arrivage->id, 7, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 0.5cm 1cm 2cm 1cm; }
        body { 
            font-family: 'Helvetica', sans-serif; 
            font-size: 8.5px; 
            color: #333;
            line-height: 1.2;
        }
        
        /* Fixed Footer */
        .footer-bar { 
            position: fixed; 
            bottom: -1cm; 
            left: 0; 
            right: 0; 
            background: #f8f8f8; 
            border-top: 1.5px solid #000; 
            padding: 5px 15px; 
            height: 45px;
            text-align: center;
        }
        .logo-box { position: absolute; left: 0; top: 0; }
        .logo { width: 75px; } /* Logo without circle as requested */
        .header-center { text-align: center; width: 100%; }
        .company-name { font-size: 24px; font-weight: 900; letter-spacing: 4px; margin-bottom: 0px; margin-top: 5px; color: #00a651; }
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
            background-color: #34495e; 
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
            border: 1px solid #34495e; 
            padding: 3px; 
            font-size: 7.5px;
            text-align: center;
            text-transform: uppercase;
        }
        .weight-table td { 
            border: 1px solid #ddd; 
            padding: 2px 3px; 
            text-align: center;
            height: 12px;
            font-size: 8px;
        }
        .weight-table .index-col { font-weight: bold; color: #2980b9; background-color: #fbfbfb; border-left: 1px solid #ccc; width: 6%; }
        .weight-table .poids-col { width: 14%; font-weight: bold; }
        
        /* Total Row */
        .total-row td { 
            background-color: #34495e; 
            color: white; 
            font-weight: bold; 
            border: 1px solid #34495e;
            padding: 3px;
        }

        /* Summary Footer */
        .summary-table { width: 100%; border-collapse: collapse; margin-top: 5px; border: 2px solid #34495e; }
        .summary-label { background-color: #f8f8f8; padding: 8px; font-weight: bold; text-transform: uppercase; font-size: 10px; width: 70%; border-right: 1px solid #34495e; }
        .summary-value { padding: 8px; font-weight: 1000; font-size: 14px; text-align: right; background-color: #fff; }

        /* Signature Section */
        .signature-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .signature-box { width: 48%; border: none; height: 80px; padding: 5px; vertical-align: top; }
        .header-container { width: 100%; margin-bottom: 5px; position: relative; height: 80px; margin-top: 5px; }
        .footer-table { width: 100%; border-collapse: collapse; }
        .footer-left-info { width: 70%; font-size: 7.5px; line-height: 1.3; color: #000; font-weight: bold; text-align: center; }
        .footer-right-certif { width: 30%; font-size: 7px; vertical-align: middle; border-left: 1px solid #ddd; padding-left: 10px; }
        .certif-box { display: inline-block; vertical-align: middle; width: 35px; border: 0.5px solid #999; padding: 2px; background: white; text-align: center; margin-right: 5px; }
        .certif-text { display: inline-block; vertical-align: middle; font-weight: bold; line-height: 1.1; text-align: left; }
    </style>
</head>
<body>
    <div class="footer-bar">
        <table class="footer-table">
            <tr>
                <td class="footer-left-info">
                    BIO FARM TRADING RCCM : TG-LOM 2019 B 1488 ; NIF 1001469316 ; Capital 10 000 000 de FCFA.<br>
                    Noépé_Baka Kondji. Rue derière EPP Noépé Tél.: (+228) 92 02 01 10 .<br>
                    E-mail: tbiofarm@gmail.com //// www.biofarmtogo.com
                </td>
                <td class="footer-right-certif">
                    <div class="certif-box">
                        <img src="{{ public_path('images/logo.jpg') }}" style="width: 25px;">
                    </div>
                    <div class="certif-text">
                        Produits bios Certifiés Par<br>
                        Ecocert S.A.S<br>
                        TG - BIO - 154
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="header-container">
        <div class="logo-box">
            <img src="{{ public_path('images/logo.jpg') }}" class="logo">
        </div>
        <div class="header-center">
            <div class="company-name">BIO FARM TRADING</div>
            <div class="company-desc">Production-Transformation-Commercialisation de produits agricoles biologiques</div>
            <div class="document-title">Bon d'Arrivage N° {{ $arrivage->bon_ref }}</div>
        </div>
        <div class="bon-info">
            <div class="bon-label">DATE</div>
            <div class="bon-date">{{ $arrivage->date_arrivage->format('d/m/Y') }}</div>
        </div>
    </div>

    <table class="info-grid">
        <tr>
            <td class="info-label">Préfecture / Zone</td>
            <td class="info-value" colspan="3">{{ $arrivage->zone_provenance }}</td>
        </tr>
        <tr>
            <td class="info-label">Chauffeur</td>
            <td class="info-value">{{ $arrivage->chauffeur }}</td>
            <td class="info-label">Matricule Camion</td>
            <td class="info-value">{{ $arrivage->matricule_camion }}</td>
        </tr>
        <tr>
            <td class="info-label">Fruit</td>
            <td class="info-value">
                {{ $arrivage->fruit_label }}
            </td>
            <td class="info-label">QUALITÉ (PH / BRIX)</td>
            <td class="info-value">
                PH: {{ $arrivage->ph ?? '-' }} &nbsp;&nbsp; | &nbsp;&nbsp; BRIX: {{ $arrivage->brix ? $arrivage->brix . '%' : '-' }}
            </td>
        </tr>
    </table>

    <div class="weight-log-title">Relevé de Poids</div>

    @php 
        $allFilledWeights = $arrivage->details->where('poids', '>', 0)->values();
        $count = $allFilledWeights->count();
        $numCols = 5;
        $rowsPerCol = ceil($count / $numCols);
        if ($rowsPerCol < 10) $rowsPerCol = 10; // Minimum rows for better look if few data
    @endphp

    <table class="weight-table">
        <thead>
            <tr>
                @for($c = 0; $c < $numCols; $c++)
                <th width="6%">N°</th><th width="14%">Poids</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < $rowsPerCol; $i++)
            <tr>
                @for($c = 0; $c < $numCols; $c++)
                    @php $idx = $i + ($c * $rowsPerCol); @endphp
                    <td class="index-col">{{ isset($allFilledWeights[$idx]) ? str_pad($idx + 1, 3, '0', STR_PAD_LEFT) : '' }}</td>
                    <td class="poids-col">{{ isset($allFilledWeights[$idx]) ? number_format($allFilledWeights[$idx]->poids, 2) : '—' }}</td>
                @endfor
            </tr>
            @endfor
            
            <tr class="total-row">
                @for($c = 0; $c < $numCols; $c++)
                    @php
                        $sum = $allFilledWeights->slice($c * $rowsPerCol, $rowsPerCol)->sum('poids');
                    @endphp
                    <td>T</td><td>{{ number_format($sum, 2) }}</td>
                @endfor
            </tr>
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td class="summary-label">Poids Total de l'Arrivage :</td>
            <td class="summary-value">{{ number_format($arrivage->total_general, 2) }} kg</td>
        </tr>
    </table>

</body>
</html>
