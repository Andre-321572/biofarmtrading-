<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>État Mensuel des Salaires et Émargement - Bio Farm Trading</title>
    <style>
        @page { 
            margin: 8mm 10mm 15mm 10mm; 
        }
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 8.5pt; 
            margin: 0; 
            padding: 0; 
            color: #1e293b; 
        }

        .header { 
            width: 100%; 
            margin-bottom: 8px; 
            border-bottom: 2px solid #16a34a; 
            padding-bottom: 8px; 
        }
        .header-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .header-table td { 
            vertical-align: middle; 
            border: none; 
            padding: 0; 
        }

        .title { 
            font-size: 15pt; 
            font-weight: bold; 
            color: #15803d; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            margin: 0; 
        }
        .subtitle { 
            font-size: 7.5pt; 
            font-weight: bold; 
            color: #475569; 
            margin-top: 2px; 
        }

        .doc-title-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .doc-title {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            text-align: center;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .meta-table td {
            font-size: 8pt;
            border: none;
            padding: 2px 4px;
        }

        .kpi-badge {
            background-color: #dcfce7;
            color: #166534;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
        }

        table.data-table { 
            width: 100%; 
            border-collapse: collapse; 
            border: 1px solid #94a3b8; 
            margin-bottom: 10px; 
        }
        table.data-table th, 
        table.data-table td { 
            border: 1px solid #cbd5e1; 
            padding: 4px 5px; 
            font-size: 7.5pt; 
            vertical-align: middle; 
        }
        table.data-table thead th { 
            background-color: #f1f5f9; 
            color: #0f172a; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 7pt;
            text-align: center;
        }

        .col-num { width: 4%; text-align: center; }
        .col-name { width: 18%; text-align: left; font-weight: bold; text-transform: uppercase; }
        .col-prenom { width: 18%; text-align: left; font-style: italic; }
        .col-shift { width: 7%; text-align: center; }
        .col-days { width: 7%; text-align: center; }
        .col-hours { width: 12%; text-align: center; font-weight: bold; }
        .col-bonus { width: 10%; text-align: right; font-weight: bold; color: #b45309; }
        .col-salary { width: 12%; text-align: right; font-weight: bold; color: #15803d; }
        .col-sign { width: 12%; text-align: center; height: 28px; }

        .section-header {
            background-color: #e2e8f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5pt;
            color: #1e293b;
            padding: 4px 6px;
        }

        .total-row {
            background-color: #f8fafc;
            font-weight: bold;
            font-size: 8pt;
        }
        .grand-total-row {
            background-color: #dcfce7;
            font-weight: bold;
            font-size: 9pt;
            color: #14532d;
            border-top: 2px solid #16a34a;
        }

        /* Signatures block */
        .signatures-box {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signatures-table td {
            width: 33.33%;
            border: 1px dashed #94a3b8;
            padding: 10px;
            vertical-align: top;
            height: 75px;
            background-color: #ffffff;
        }
        .sign-title {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .sign-mention {
            font-size: 7pt;
            color: #64748b;
            margin-top: 4px;
        }

        /* Footer */
        .footer { 
            position: fixed; 
            bottom: -5mm; 
            left: 0; 
            right: 0; 
            height: 10mm; 
            font-size: 6.5pt; 
            color: #64748b; 
            border-top: 1px solid #e2e8f0; 
            padding-top: 4px; 
        }
        .footer-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .footer-table td { 
            border: none; 
            padding: 0; 
        }
    </style>
</head>
<body>

    <!-- Header Bio Farm Trading -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 75px;">
                    <img src="{{ public_path('images/biofarm_logo.jpg') }}" style="width: 65px; height: auto;">
                </td>
                <td style="text-align: center; padding-right: 75px;">
                    <div class="title">BIO FARM TRADING SARL</div>
                    <div class="subtitle">Production - Transformation - Commercialisation de Produits Agricoles Biologiques</div>
                    <div style="font-size: 7pt; color: #64748b; margin-top: 1px;">
                        RCCM : TG-LOM 2019 B 1488 | NIF : 1001469316 | Lomé, TOGO | Tél : (+228) 92 02 01 10
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Document Info Box (Taux horaire masqué aux ouvriers) -->
    <div class="doc-title-box">
        <div class="doc-title">
            ÉTAT RÉCAPITULATIF DES SALAIRES ET ÉMARGEMENT DES OUVRIERS • {{ mb_strtoupper($month->locale('fr')->translatedFormat('F Y')) }}
        </div>
        <table class="meta-table">
            <tr>
                <td style="width: 25%;">
                    <strong>Période :</strong> {{ \Carbon\Carbon::parse($start_of_month)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($end_of_month)->format('d/m/Y') }}
                </td>
                <td style="width: 25%;">
                    <strong>Effectif rémunéré :</strong> {{ $summary['active_workers'] }} / {{ $summary['total_workers'] }} ouvriers
                </td>
                <td style="width: 25%;">
                    <strong>Total Primes :</strong> 
                    <span class="kpi-badge" style="background-color: #fef3c7; color: #92400e;">
                        {{ number_format($summary['total_bonuses'], 0, ',', ' ') }} FCFA
                    </span>
                </td>
                <td style="width: 25%; text-align: right;">
                    <strong>Net Total à Décaisser :</strong> 
                    <span style="font-size: 9.5pt; font-weight: bold; color: #15803d;">
                        {{ number_format($summary['total_salary'], 0, ',', ' ') }} FCFA
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Table (Sans la colonne de taux horaire pour confidentialité lors de l'émargement) -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-num">N°</th>
                <th class="col-name">Nom</th>
                <th class="col-prenom">Prénoms</th>
                <th class="col-shift">Équipe</th>
                <th class="col-days">Présences</th>
                <th class="col-hours">Heures Effectives</th>
                <th class="col-bonus">Prime</th>
                <th class="col-salary">Net à Payer</th>
                <th class="col-sign">Émargement / Signature</th>
            </tr>
        </thead>
        <tbody>
            @php $globalCounter = 1; @endphp

            <!-- Section 1 : Équipe de Jour -->
            @if($dayItems->count() > 0)
                <tr>
                    <td colspan="9" class="section-header">
                        ÉQUIPE DE JOUR ({{ $dayItems->count() }} ouvriers) — Total à payer : {{ number_format($summary['day_salary'], 0, ',', ' ') }} FCFA
                    </td>
                </tr>
                @foreach($dayItems as $item)
                    <tr>
                        <td class="col-num">{{ $globalCounter++ }}</td>
                        <td class="col-name">{{ $item['worker']->last_name }}</td>
                        <td class="col-prenom">{{ $item['worker']->first_name }}</td>
                        <td class="col-shift">Jour</td>
                        <td class="col-days">{{ $item['days_present'] }} j</td>
                        <td class="col-hours">
                            {{ $item['hours_formatted'] }}
                            <span style="font-size: 6.5pt; color: #64748b;">({{ number_format($item['hours_decimal'], 1, ',', ' ') }}h)</span>
                        </td>
                        <td class="col-bonus">
                            @if($item['bonus_amount'] > 0)
                                +{{ number_format($item['bonus_amount'], 0, ',', ' ') }} F
                            @else
                                <span style="color: #cbd5e1;">-</span>
                            @endif
                        </td>
                        <td class="col-salary">
                            {{ number_format($item['salary'], 0, ',', ' ') }} F
                        </td>
                        <td class="col-sign"></td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; padding-right: 10px;">SOUS-TOTAL ÉQUIPE DE JOUR :</td>
                    <td class="col-days">{{ $dayItems->sum('days_present') }} j</td>
                    <td class="col-hours">{{ floor($dayItems->sum('total_minutes') / 60) }}h{{ sprintf('%02d', $dayItems->sum('total_minutes') % 60) }}</td>
                    <td class="col-bonus">{{ number_format($dayItems->sum('bonus_amount'), 0, ',', ' ') }} F</td>
                    <td class="col-salary">{{ number_format($summary['day_salary'], 0, ',', ' ') }} FCFA</td>
                    <td></td>
                </tr>
            @endif

            <!-- Section 2 : Équipe de Nuit -->
            @if($nightItems->count() > 0)
                <tr>
                    <td colspan="9" class="section-header" style="background-color: #e0e7ff; color: #1e1b4b;">
                        ÉQUIPE DE NUIT ({{ $nightItems->count() }} ouvriers) — Total à payer : {{ number_format($summary['night_salary'], 0, ',', ' ') }} FCFA
                    </td>
                </tr>
                @foreach($nightItems as $item)
                    <tr>
                        <td class="col-num">{{ $globalCounter++ }}</td>
                        <td class="col-name">{{ $item['worker']->last_name }}</td>
                        <td class="col-prenom">{{ $item['worker']->first_name }}</td>
                        <td class="col-shift">Nuit</td>
                        <td class="col-days">{{ $item['days_present'] }} j</td>
                        <td class="col-hours">
                            {{ $item['hours_formatted'] }}
                            <span style="font-size: 6.5pt; color: #64748b;">({{ number_format($item['hours_decimal'], 1, ',', ' ') }}h)</span>
                        </td>
                        <td class="col-bonus">
                            @if($item['bonus_amount'] > 0)
                                +{{ number_format($item['bonus_amount'], 0, ',', ' ') }} F
                            @else
                                <span style="color: #cbd5e1;">-</span>
                            @endif
                        </td>
                        <td class="col-salary">
                            {{ number_format($item['salary'], 0, ',', ' ') }} F
                        </td>
                        <td class="col-sign"></td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; padding-right: 10px;">SOUS-TOTAL ÉQUIPE DE NUIT :</td>
                    <td class="col-days">{{ $nightItems->sum('days_present') }} j</td>
                    <td class="col-hours">{{ floor($nightItems->sum('total_minutes') / 60) }}h{{ sprintf('%02d', $nightItems->sum('total_minutes') % 60) }}</td>
                    <td class="col-bonus">{{ number_format($nightItems->sum('bonus_amount'), 0, ',', ' ') }} F</td>
                    <td class="col-salary">{{ number_format($summary['night_salary'], 0, ',', ' ') }} FCFA</td>
                    <td></td>
                </tr>
            @endif

            <!-- Total Général -->
            <tr class="grand-total-row">
                <td colspan="4" style="text-align: right; padding-right: 10px; font-size: 8.5pt;">
                    TOTAL GÉNÉRAL À DÉCAISSER ({{ $summary['total_workers'] }} ouvriers) :
                </td>
                <td class="col-days" style="font-weight: bold;">
                    {{ $items->sum('days_present') }} j
                </td>
                <td class="col-hours" style="font-weight: bold;">
                    {{ $summary['total_hours_formatted'] }}
                </td>
                <td class="col-bonus" style="font-weight: bold;">
                    {{ number_format($summary['total_bonuses'], 0, ',', ' ') }} F
                </td>
                <td class="col-salary" style="font-size: 10pt; font-weight: bold;">
                    {{ number_format($summary['total_salary'], 0, ',', ' ') }} FCFA
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- Signatures pour décaissement comptable -->
    <div class="signatures-box">
        <table class="signatures-table">
            <tr>
                <td>
                    <div class="sign-title">1. Établi par les Ressources Humaines</div>
                    <div class="sign-mention">Date : ____ / ____ / ________</div>
                    <div class="sign-mention">Signature & Cachet :</div>
                </td>
                <td>
                    <div class="sign-title">2. Bon à Payer - Service Comptable</div>
                    <div class="sign-mention">Montant décaissé : ___________________ FCFA</div>
                    <div class="sign-mention">Mode : [ ] Espèces  [ ] Virement  [ ] Chèque</div>
                    <div class="sign-mention">Signature & Cachet :</div>
                </td>
                <td>
                    <div class="sign-title">3. Visa de la Direction Générale</div>
                    <div class="sign-mention">Date : ____ / ____ / ________</div>
                    <div class="sign-mention">Signature & Approbation :</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="width: 60%; text-align: left;">
                    Bio Farm Trading SARL - Fiche d'émargement et de paie mensuelle • Généré le {{ $generationDate->format('d/m/Y à H:i') }}
                </td>
                <td style="width: 40%; text-align: right;">
                    Certifié biologique par Ecocert International • TG-BIO-154
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
