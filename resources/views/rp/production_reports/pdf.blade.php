<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rapport Journalier de Production - {{ $report->reference }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 15px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-b: 2px solid #059669;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
        }
        .company-sub {
            font-size: 10px;
            color: #6b7280;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            color: #111827;
        }
        .report-ref {
            font-size: 11px;
            color: #059669;
            font-weight: bold;
            text-align: right;
        }
        .info-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        .info-box td {
            padding: 8px 12px;
            font-size: 10px;
        }
        .tools-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: #0f172a;
            color: #f8fafc;
            border-radius: 6px;
        }
        .tools-box td {
            padding: 6px 12px;
            font-size: 9px;
        }
        .section-pelage {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .section-pelage-title {
            font-weight: bold;
            color: #92400e;
            text-transform: uppercase;
            font-size: 10px;
            margin-bottom: 3px;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-data th {
            background-color: #d1fae5;
            color: #065f46;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px;
            border: 1px solid #a7f3d0;
            text-align: left;
        }
        .table-data td {
            padding: 6px;
            border: 1px solid #e5e7eb;
            font-size: 9px;
        }
        .table-data tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .tfoot-total td {
            background-color: #ecfdf5;
            font-weight: bold;
            color: #065f46;
            border-top: 2px solid #10b981;
        }
        .signatures {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 10px;
            font-weight: bold;
        }
        .sig-line {
            margin-top: 50px;
            border-bottom: 1px solid #9ca3af;
            width: 180px;
            margin-left: auto;
            margin-right: auto;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-rose { color: #e11d48; }
        .text-emerald { color: #047857; }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 75px; vertical-align: top; padding-right: 12px;">
                <img src="{{ public_path('images/logo.jpg') }}" style="width: 70px; height: auto;" alt="Logo Bio Farm Trading">
            </td>
            <td style="vertical-align: top;">
                <div class="company-name">BIO FARM TRADING</div>
                <div class="company-sub">Unité de Transformation & Conditionnement de Fruits Séchés Bio</div>
                <div class="company-sub">Lomé, Togo</div>
            </td>
            <td style="vertical-align: top;" class="text-right">
                <div class="report-title">RAPPORT JOURNALIER DE PRODUCTION</div>
                <div class="report-ref">Réf : {{ $report->reference }}</div>
                <div class="company-sub" style="margin-top: 4px;">Date : {{ $report->date_rapport->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- Info Box -->
    <table class="info-box">
        <tr>
            <td>
                <strong>Effectif Ouvriers :</strong> 
                <span style="color: #047857; font-weight: bold;">{{ $report->nombre_ouvriers }} Présent(s)</span> | 
                <span style="color: #e11d48; font-weight: bold;">{{ $report->nombre_ouvriers_absents ?? 0 }} Absent(s)</span> | 
                <span style="color: #d97706; font-weight: bold;">{{ $report->nombre_ouvriers_repos ?? 0 }} Repos</span>
            </td>
            <td><strong>Auteur :</strong> {{ $report->user->name ?? 'Responsable Production' }}</td>
            <td class="text-right"><strong>Saisi le :</strong> {{ $report->created_at->format('d/m/Y à H:i') }}</td>
        </tr>
    </table>

    <!-- Tools Box -->
    <table class="tools-box">
        <tr>
            <td><strong>Couteaux (Début / Fin) :</strong> {{ $report->nombre_couteaux_debut ?? 0 }} / {{ $report->nombre_couteaux_fin ?? 0 }} pcs</td>
            <td><strong>Ciseaux (Début / Fin) :</strong> {{ $report->nombre_ciseaux_debut ?? 0 }} / {{ $report->nombre_ciseaux_fin ?? 0 }} pcs</td>
        </tr>
    </table>

    <!-- Section Pelage -->
    @if($report->lots_peles)
        <div class="section-pelage">
            <div class="section-pelage-title">Pelage / Épluchage du Jour :</div>
            <div style="font-size: 10px; font-weight: bold; color: #78350f;">{{ $report->lots_peles }}</div>
        </div>
    @endif

    <!-- Data Tables by Fruit Variety -->
    @foreach($report->grouped_lots as $produit => $lotsGroup)
        <div style="font-size: 10px; font-weight: bold; background-color: #1f2937; color: #ffffff; padding: 4px 8px; text-transform: uppercase; margin-top: 10px; border-radius: 3px;">
            VARIÉTÉ : {{ $produit }}
        </div>
        <table class="table-data" style="margin-bottom: 10px;">
            <thead>
                <tr>
                    <th>N° Lot</th>
                    <th class="text-right">Qté Déclayée</th>
                    <th class="text-center">Poids par Coupe (kg)</th>
                    <th class="text-center">Conditionnements (Sachets)</th>
                    <th class="text-right">Déchets (kg)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotsGroup as $lot)
                    <tr>
                        <td class="font-bold">{{ $lot->numero_lot }}</td>
                        <td class="text-right font-bold">{{ $lot->quantite_declayee_kg > 0 ? number_format($lot->quantite_declayee_kg, 2) . ' kg' : '-' }}</td>
                        
                        <!-- Poids par coupe -->
                        <td class="text-center">
                            @php
                                $poidsDetails = [];
                                if($lot->poids_rcl_kg > 0) $poidsDetails[] = "RCL: " . number_format($lot->poids_rcl_kg, 2) . "kg";
                                if($lot->poids_mcl_kg > 0) $poidsDetails[] = "MCL: " . number_format($lot->poids_mcl_kg, 2) . "kg";
                                if($lot->poids_rps_kg > 0) $poidsDetails[] = "RPS: " . number_format($lot->poids_rps_kg, 2) . "kg";
                                if($lot->poids_mps_kg > 0) $poidsDetails[] = "MPS: " . number_format($lot->poids_mps_kg, 2) . "kg";
                                if($lot->poids_lpa_kg > 0) $poidsDetails[] = "LPA: " . number_format($lot->poids_lpa_kg, 2) . "kg";
                                if($lot->cl_ttpm_kg > 0) $poidsDetails[] = "TTPM: " . number_format($lot->cl_ttpm_kg, 2) . "kg";
                            @endphp
                            {{ count($poidsDetails) > 0 ? implode(' | ', $poidsDetails) : '-' }}
                        </td>

                        <!-- Sachets -->
                        <td class="text-center">
                            @php
                                $sachetDetails = [];
                                if($lot->sachets_rcl_2kg > 0) $sachetDetails[] = "RCL 2kg: {$lot->sachets_rcl_2kg}";
                                if($lot->sachets_rcl_1kg > 0) $sachetDetails[] = "RCL 1kg: {$lot->sachets_rcl_1kg}";
                                if($lot->sachets_mcl_2kg > 0) $sachetDetails[] = "MCL 2kg: {$lot->sachets_mcl_2kg}";
                                if($lot->sachets_mcl_1kg > 0) $sachetDetails[] = "MCL 1kg: {$lot->sachets_mcl_1kg}";
                                if($lot->sachets_rps_2kg > 0) $sachetDetails[] = "RPS 2kg: {$lot->sachets_rps_2kg}";
                                if($lot->sachets_rps_1kg > 0) $sachetDetails[] = "RPS 1kg: {$lot->sachets_rps_1kg}";
                                if($lot->sachets_mps_2kg > 0) $sachetDetails[] = "MPS 2kg: {$lot->sachets_mps_2kg}";
                                if($lot->sachets_mps_1kg > 0) $sachetDetails[] = "MPS 1kg: {$lot->sachets_mps_1kg}";
                                if($lot->sachets_lpa_2kg > 0) $sachetDetails[] = "LPA 2kg: {$lot->sachets_lpa_2kg}";
                                if($lot->sachets_lpa_1kg > 0) $sachetDetails[] = "LPA 1kg: {$lot->sachets_lpa_1kg}";
                                if($lot->sachets_lpa_100g > 0) $sachetDetails[] = "LPA 100g: {$lot->sachets_lpa_100g}";
                                if($lot->sachets_ttpm_1kg > 0) $sachetDetails[] = "TTPM 1kg: {$lot->sachets_ttpm_1kg}";
                                if($lot->sachets_2kg > 0) $sachetDetails[] = "2kg: {$lot->sachets_2kg}";
                                if($lot->sachets_1kg > 0) $sachetDetails[] = "1kg: {$lot->sachets_1kg}";
                            @endphp
                            {{ count($sachetDetails) > 0 ? implode(', ', $sachetDetails) : '-' }}
                        </td>
                        <td class="text-right text-rose font-bold">{{ $lot->dechets_kg > 0 ? number_format($lot->dechets_kg, 2) . ' kg' : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-total">
                    <td class="font-bold">SOUS-TOTAL {{ strtoupper($produit) }}</td>
                    <td class="text-right">{{ number_format($lotsGroup->sum('quantite_declayee_kg'), 2) }} kg</td>
                    <td class="text-center font-bold">
                        @php
                            $subPoids = [];
                            if($lotsGroup->sum('poids_rcl_kg') > 0) $subPoids[] = "RCL: " . number_format($lotsGroup->sum('poids_rcl_kg'), 2) . "kg";
                            if($lotsGroup->sum('poids_mcl_kg') > 0) $subPoids[] = "MCL: " . number_format($lotsGroup->sum('poids_mcl_kg'), 2) . "kg";
                            if($lotsGroup->sum('poids_rps_kg') > 0) $subPoids[] = "RPS: " . number_format($lotsGroup->sum('poids_rps_kg'), 2) . "kg";
                            if($lotsGroup->sum('poids_mps_kg') > 0) $subPoids[] = "MPS: " . number_format($lotsGroup->sum('poids_mps_kg'), 2) . "kg";
                            if($lotsGroup->sum('poids_lpa_kg') > 0) $subPoids[] = "LPA: " . number_format($lotsGroup->sum('poids_lpa_kg'), 2) . "kg";
                            if($lotsGroup->sum('cl_ttpm_kg') > 0) $subPoids[] = "TTPM: " . number_format($lotsGroup->sum('cl_ttpm_kg'), 2) . "kg";
                        @endphp
                        {{ count($subPoids) > 0 ? implode(' | ', $subPoids) : '-' }}
                    </td>
                    <td class="text-center font-bold">{{ $lotsGroup->sum(fn($l) => $l->total_sachets) }} sach. total</td>
                    <td class="text-right text-rose">{{ number_format($lotsGroup->sum('dechets_kg'), 2) }} kg</td>
                </tr>
            </tfoot>
        </table>
    @endforeach

    <!-- Total General Table -->
    <table class="table-data" style="margin-top: 10px; margin-bottom: 15px;">
        <tfoot>
            <tr style="background-color: #065f46; color: white; font-weight: bold; font-size: 10px;">
                <td style="padding: 6px; font-weight: bold; text-transform: uppercase;" colspan="2">TOTAL GÉNÉRAL DE PRODUCTION</td>
                <td class="text-right" style="padding: 6px;">{{ number_format($report->total_declaye, 2) }} kg</td>
                <td class="text-center" style="padding: 6px;">{{ $report->total_sachets }} sachets au total</td>
                <td class="text-right" style="padding: 6px; color: #fecdd3;">{{ number_format($report->total_dechets, 2) }} kg</td>
            </tr>
        </tfoot>
    </table>

    <!-- Legend -->
    <div style="margin-top: 10px; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 6px 10px; border-radius: 4px;">
        <div style="font-weight: bold; font-size: 8px; text-transform: uppercase; color: #065f46; margin-bottom: 3px;">Légende des Abréviations :</div>
        <table style="width: 100%; font-size: 8px; border-collapse: collapse;">
            <tr>
                <td><strong>RCL</strong> = Rondelles Cayenne Lisse</td>
                <td><strong>MCL</strong> = Morceaux Cayenne Lisse</td>
                <td><strong>RPS</strong> = Rondelles Pain de Sucre</td>
                <td><strong>MPS</strong> = Morceaux Pain de Sucre</td>
                <td><strong>LPA</strong> = Lamelles de Papaye</td>
                <td><strong>TTPM</strong> = Très Très Petits Morceaux (1KG)</td>
            </tr>
        </table>
    </div>

    <!-- Notes -->
    @if($report->notes)
        <div style="margin-top: 10px;">
            <div style="font-weight: bold; font-size: 9px; text-transform: uppercase; color: #4b5563; margin-bottom: 3px;">Observations / Remarques :</div>
            <div style="background-color: #f3f4f6; padding: 6px 10px; font-size: 9px; border-radius: 4px; border: 1px solid #e5e7eb;">
                {{ $report->notes }}
            </div>
        </div>
    @endif

    <!-- Signatures -->
    <table class="signatures">
        <tr>
            <td>
                Le Responsable Production (RP)
                <div class="sig-line"></div>
                <span style="font-size: 8px; color: #6b7280; font-style: italic;">{{ $report->user->name ?? 'Signature' }}</span>
            </td>
            <td>
                La Direction Générale (DG)
                <div class="sig-line"></div>
                <span style="font-size: 8px; color: #6b7280; font-style: italic;">Visa & Accord</span>
            </td>
        </tr>
    </table>

</body>
</html>
