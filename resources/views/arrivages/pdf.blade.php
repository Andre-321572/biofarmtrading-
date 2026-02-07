<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Arrivage #{{ $arrivage->id }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            margin: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .info-table { 
            width: 100%; 
            margin-bottom: 20px; 
            border: 1px solid #ddd;
        }
        .info-table td { 
            padding: 8px; 
            border: 1px solid #ddd;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
        }
        .totals { 
            background-color: #f9f9f9; 
            padding: 15px; 
            margin-top: 20px; 
            border: 2px solid #ddd;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
            padding: 5px;
            background-color: #e8e8e8;
        }
        .section-ananas {
            background-color: #e3f2fd;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
        }
        .section-papaye {
            background-color: #fff3e0;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #FF9800;
        }
        .total-line {
            font-weight: bold;
            padding: 5px 0;
        }
        .grand-total {
            background-color: #e8e8e8;
            padding: 10px;
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
            border: 2px solid #333;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.jpg') }}" alt="Logo Bio Farm Trading" class="logo">
        <h1>BON D'ARRIVAGE #{{ $arrivage->id }}</h1>
        <p style="font-size: 14px;">{{ $arrivage->date_arrivage->format('d/m/Y') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 50%;"><strong>Chauffeur:</strong> {{ $arrivage->chauffeur }}</td>
            <td style="width: 50%;"><strong>Matricule:</strong> {{ $arrivage->matricule_camion }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Zone de Provenance:</strong> {{ $arrivage->zone_provenance }}</td>
        </tr>
    </table>

    <h3 style="margin-top: 30px;">Détails des Fruits</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Fruit</th>
                <th style="width: 35%;">Variété</th>
                <th style="width: 25%; text-align: right;">Poids (kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($arrivage->details as $detail)
                @if($detail->poids > 0)
                <tr>
                    <td>{{ ucfirst($detail->fruit) }}</td>
                    <td>
                        @if($detail->variete === 'cayenne_lisse')
                            Cayenne Lisse
                        @elseif($detail->variete === 'braza')
                            Braza
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($detail->poids, 2) }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <h3 style="margin-top: 0;">Récapitulatif</h3>
        
        <!-- Section ANANAS -->
        @if($arrivage->total_ananas > 0)
        <div class="section-ananas">
            <div class="section-title" style="background-color: #2196F3; color: white;">
                🍍 ANANAS
            </div>
            <div class="total-line">
                <span>Total Ananas Cayenne Lisse:</span>
                <span style="float: right; color: #2196F3;">
                    {{ number_format($arrivage->total_ananas_cayenne, 2) }} kg
                </span>
            </div>
            <div class="total-line">
                <span>Total Ananas Braza:</span>
                <span style="float: right; color: #2196F3;">
                    {{ number_format($arrivage->total_ananas_braza, 2) }} kg
                </span>
            </div>
            <hr style="border-top: 2px solid #2196F3;">
            <div class="total-line" style="font-size: 13px;">
                <span>TOTAL ANANAS (toutes variétés):</span>
                <span style="float: right; color: #2196F3;">
                    {{ number_format($arrivage->total_ananas, 2) }} kg
                </span>
            </div>
        </div>
        @endif

        <!-- Section PAPAYE -->
        @if($arrivage->total_papaye > 0)
        <div class="section-papaye">
            <div class="section-title" style="background-color: #FF9800; color: white;">
                🥭 PAPAYE
            </div>
            <div class="total-line">
                <span>Total Papaye:</span>
                <span style="float: right; color: #FF9800;">
                    {{ number_format($arrivage->total_papaye, 2) }} kg
                </span>
            </div>
        </div>
        @endif

        <!-- Total Général -->
        <div class="grand-total">
            <span>TOTAL GÉNÉRAL (tous fruits):</span>
            <span style="float: right;">
                {{ number_format($arrivage->total_general, 2) }} kg
            </span>
        </div>
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 10px; color: #666;">
        Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
