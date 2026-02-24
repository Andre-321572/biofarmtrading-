<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Arrivage #{{ str_pad($arrivage->id, 7, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 1cm; }
        body { 
            font-family: 'Helvetica', sans-serif; 
            font-size: 9px; 
            color: #333;
            line-height: 1.3;
        }
        .header-container { width: 100%; margin-bottom: 20px; position: relative; }
        .logo-box { position: absolute; left: 0; top: 0; }
        .logo { width: 80px; }
        .header-center { text-align: center; }
        .company-name { font-size: 20px; font-weight: 900; letter-spacing: 5px; margin-bottom: 2px; }
        .company-desc { font-size: 8px; color: #666; margin-bottom: 10px; }
        .document-title { 
            font-size: 14px; 
            font-weight: bold; 
            border-top: 2px solid #333; 
            border-bottom: 2px solid #333;
            padding: 5px 0;
            margin: 10px 0;
            text-transform: uppercase;
        }
        .bon-info { position: absolute; right: 0; top: 0; text-align: right; }
        .bon-label { font-size: 8px; color: #999; font-weight: bold; }
        .bon-number { font-size: 14px; font-weight: 900; }

        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-grid td { 
            border: 1px solid #ccc; 
            padding: 4px 8px; 
            width: 25%;
        }
        .info-label { font-weight: bold; text-transform: uppercase; font-size: 8px; background-color: #f5f5f5; width: 15%; }
        .info-value { font-weight: bold; color: #000; }

        .weight-log-title { 
            background-color: #2c3e50; 
            color: white; 
            text-align: center; 
            font-weight: bold; 
            padding: 6px; 
            letter-spacing: 10px;
            text-transform: uppercase;
            font-size: 11px;
        }

        .weight-table { width: 100%; border-collapse: collapse; }
        .weight-table th { 
            background-color: #f5f5f5; 
            border: 1px solid #2c3e50; 
            padding: 4px; 
            font-size: 8px;
            text-align: center;
        }
        .weight-table td { 
            border: 1px solid #ddd; 
            padding: 3px 6px; 
            text-align: center;
            height: 14px;
        }
        .weight-table .index-col { font-weight: bold; color: #2c3e50; background-color: #f9f9f9; border-left: 2px solid #2c3e50; }
        .weight-table .total-row td { 
            background-color: #2c3e50; 
            color: white; 
            font-weight: bold; 
            border-bottom: 3px solid #2c3e50;
        }

        .footer-summary { width: 100%; margin-top: 15px; border-collapse: collapse; }
        .summary-box { border: 2px solid #2c3e50; padding: 10px; margin-top: 10px; font-weight: bold; }
        .summary-label { text-transform: uppercase; font-size: 11px; }
        .summary-value { font-size: 16px; float: right; }

        .signature-table { width: 100%; margin-top: 30px; }
        .signature-box { width: 48%; border: 1px solid #ccc; height: 100px; padding: 5px; position: relative; }
        .signature-title { font-weight: bold; text-transform: uppercase; font-size: 8px; }
        .signature-line { border-bottom: 1px dotted #ccc; margin-top: 60px; text-align: center; font-size: 7px; color: #999; }

        .small-footer { text-align: center; margin-top: 40px; font-size: 7px; color: #888; border-top: 0.5px solid #eee; padding-top: 5px; }
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
            <div class="document-title">Bon d'Arrivage N° {{ str_pad($arrivage->id, 10, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="bon-info">
            <div class="bon-label">BON N°</div>
            <div class="bon-number">#{{ str_pad($arrivage->id, 7, '0', STR_PAD_LEFT) }}</div>
            <div class="bon-label" style="margin-top:5px">DATE</div>
            <div class="info-value">{{ $arrivage->date_arrivage->format('d/m/Y') }}</div>
        </div>
    </div>

    <table class="info-grid">
        <tr>
            <td class="info-label">Préfecture</td>
            <td class="info-value">{{ $arrivage->zone_provenance }}</td>
            <td class="info-label">OP</td>
            <td class="info-value">-</td>
        </tr>
        <tr>
            <td class="info-label">Zone</td>
            <td class="info-value">{{ $arrivage->zone_provenance }}</td>
            <td class="info-label">Matricule</td>
            <td class="info-value">{{ $arrivage->matricule_camion }}</td>
        </tr>
        <tr>
            <td class="info-label">Chauffeur</td>
            <td class="info-value">{{ $arrivage->chauffeur }}</td>
            <td class="info-label">Fruit</td>
            <td class="info-value">
                @if($arrivage->total_ananas > 0)
                    Ananas {{ $arrivage->total_ananas_cayenne > 0 ? 'Cayenne' : ($arrivage->total_ananas_braza > 0 ? 'Braza' : '') }}
                @elseif($arrivage->total_papaye > 0)
                    Papaye
                @endif
            </td>
        </tr>
    </table>

    <div class="weight-log-title">Relevé de Poids</div>

    <table class="weight-table">
        <thead>
            <tr>
                <th width="5%">N°</th><th width="28.3%">Poids kg</th>
                <th width="5%">N°</th><th width="28.3%">Poids kg</th>
                <th width="5%">N°</th><th width="28.3%">Poids kg</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $allWeights = $arrivage->details->pluck('poids')->toArray();
                $rows = 67; // Pour atteindre 200 cases (67x3 = 201)
            @endphp
            @for($i = 0; $i < $rows; $i++)
            <tr>
                {{-- Col 1 --}}
                <td class="index-col">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ isset($allWeights[$i]) ? number_format($allWeights[$i], 2) : '—' }}</td>
                
                {{-- Col 2 --}}
                <td class="index-col">{{ $i + 68 }}</td>
                <td>{{ isset($allWeights[$i + 67]) ? number_format($allWeights[$i + 67], 2) : '—' }}</td>
                
                {{-- Col 3 --}}
                <td class="index-col">{{ $i + 135 }}</td>
                <td>{{ isset($allWeights[$i + 134]) ? number_format($allWeights[$i + 134], 2) : '—' }}</td>
            </tr>
            @endfor
            @php
                // Calculs des colonnes pour le pied de tableau
                $sum1 = collect($allWeights)->slice(0, 67)->sum();
                $sum2 = collect($allWeights)->slice(67, 67)->sum();
                $sum3 = collect($allWeights)->slice(134, 66)->sum();
            @endphp
            <tr class="total-row">
                <td>T</td><td>{{ number_format($sum1, 2) }}</td>
                <td>T</td><td>{{ number_format($sum2, 2) }}</td>
                <td>T</td><td>{{ number_format($sum3, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="summary-box">
        <span class="summary-label">Poids Total de l'Arrivage :</span>
        <span class="summary-value">{{ number_format($arrivage->total_general, 2) }} kg</span>
    </div>

    <table class="signature-table">
        <tr>
            <td class="signature-box" style="border-right: none;">
                <div class="signature-title">A2C SAM / Responsable</div>
                <div class="signature-line">Signature & Cachet</div>
            </td>
            <td style="width: 4%;"></td>
            <td class="signature-box">
                <div class="signature-title">Le Producteur / Livreur</div>
                <div class="signature-line">Signature</div>
            </td>
        </tr>
    </table>

    <div class="small-footer">
        BIO FARM TRADING - NIF 1002966783 - Tél : (+229) 97562640 / 97264340 - Capital 51 000 000 FCFA - www.biofarmtrading.com<br>
        Document généré le {{ now()->format('d/m/Y à H:i') }} - Bon N° {{ str_pad($arrivage->id, 7, '0', STR_PAD_LEFT) }} - {{ count($allWeights) }} case(s) saisie(s)
    </div>

</body>
</html>
