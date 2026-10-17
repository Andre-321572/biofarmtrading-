<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Feuille d'Émargement des Ouvriers - Bio Farm Trading</title>
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
        .doc-subtitle {
            font-size: 7.5pt;
            color: #64748b;
            text-align: center;
            margin-top: 2px;
            font-style: italic;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
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

        .confidentiality-notice {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            font-size: 7pt;
            padding: 4px 8px;
            border-radius: 4px;
            margin-top: 6px;
            text-align: center;
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
            padding: 5px 6px; 
            font-size: 8pt; 
            vertical-align: middle; 
        }
        table.data-table thead th { 
            background-color: #f1f5f9; 
            color: #0f172a; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 7.5pt;
            text-align: center;
        }

        .col-num { width: 4%; text-align: center; }
        .col-name { width: 18%; text-align: left; font-weight: bold; text-transform: uppercase; }
        .col-prenom { width: 18%; text-align: left; font-style: italic; }
        .col-shift { width: 8%; text-align: center; }
        .col-days { width: 10%; text-align: center; font-weight: bold; }
        .col-hours { width: 14%; text-align: center; font-weight: bold; color: #1e40af; }
        .col-sign { width: 28%; text-align: center; height: 36px; vertical-align: bottom; }

        .section-header {
            background-color: #e2e8f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            color: #1e293b;
            padding: 5px 8px;
        }

        .total-row {
            background-color: #f8fafc;
            font-weight: bold;
            font-size: 8pt;
        }
        .grand-total-row {
            background-color: #dcfce7;
            font-weight: bold;
            font-size: 8.5pt;
            color: #14532d;
            border-top: 2px solid #16a34a;
        }

        .sign-placeholder {
            border-bottom: 1px dotted #94a3b8;
            height: 24px;
            margin: 4px 10px;
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
                    <img src="{{ public_path('images/biofarm_logo.jpg') }}" style="width: 65px; height: auto;" onerror="this.style.display='none'">
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

    <!-- Document Info Box (Confidentiel : Salaires omis) -->
    <div class="doc-title-box">
        <div class="doc-title">
            FEUILLE D'ÉMARGEMENT ET RECONNAISSANCE DES HEURES DES OUVRIERS • {{ mb_strtoupper($month->locale('fr')->translatedFormat('F Y')) }}
        </div>
        <div class="doc-subtitle">
            Attestation collective de présence et confirmation des volumes horaires travaillés
        </div>
        <table class="meta-table">
            <tr>
                <td style="width: 33%;">
                    <strong>Période :</strong> {{ \Carbon\Carbon::parse($start_of_month)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($end_of_month)->format('d/m/Y') }}
                </td>
                <td style="width: 33%; text-align: center;">
                    <strong>Effectif Présent :</strong> 
                    <span class="kpi-badge">
                        {{ $summary['active_workers'] }} / {{ $summary['total_workers'] }} ouvriers
                    </span>
                </td>
                <td style="width: 34%; text-align: right;">
                    <strong>Volume Total Heures :</strong> 
                    <span style="font-size: 9pt; font-weight: bold; color: #1e40af;">
                        {{ $summary['total_hours_formatted'] }} ({{ number_format($summary['total_hours_decimal'], 1, ',', ' ') }} h)
                    </span>
                </td>
            </tr>
        </table>
        <div class="confidentiality-notice">
            <strong>DOCUMENT DESTINÉ À L'ÉMARGEMENT COLLECTIF :</strong> Conformément aux règles de confidentialité des Ressources Humaines, les données salariales individuelles sont strictement masquées sur cette feuille.
        </div>
    </div>

    <!-- Data Table d'Émargement -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-num">N°</th>
                <th class="col-name">Nom</th>
                <th class="col-prenom">Prénoms</th>
                <th class="col-shift">Équipe</th>
                <th class="col-days">Jours Présents</th>
                <th class="col-hours">Volume Horaire</th>
                <th class="col-sign">Date & Émargement (Signature Ouvrier)</th>
            </tr>
        </thead>
        <tbody>
            @php $globalCounter = 1; @endphp

            {{-- 1. ÉQUIPE DE JOUR --}}
            @if($dayItems->count() > 0)
                <tr>
                    <td colspan="7" class="section-header">
                        ☀️ ÉQUIPE DE JOUR ({{ $dayItems->count() }} Ouvriers) &bull; Déduction pause réglementaire (2h/j)
                    </td>
                </tr>
                @foreach($dayItems as $item)
                    @php $w = $item['worker']; @endphp
                    <tr>
                        <td class="col-num">{{ $globalCounter++ }}</td>
                        <td class="col-name">{{ $w->last_name }}</td>
                        <td class="col-prenom">{{ $w->first_name }}</td>
                        <td class="col-shift">
                            <span style="color: #b45309; font-weight: bold;">JOUR</span>
                        </td>
                        <td class="col-days">
                            {{ $item['days_present'] }} j
                        </td>
                        <td class="col-hours">
                            {{ $item['hours_formatted'] }}
                        </td>
                        <td class="col-sign">
                            <div class="sign-placeholder"></div>
                        </td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; text-transform: uppercase;">Sous-total Équipe de Jour :</td>
                    <td style="text-align: center;">{{ $dayItems->sum('days_present') }} j</td>
                    <td style="text-align: center; color: #1e40af;">{{ sprintf('%dh%02d', floor($dayItems->sum('total_minutes') / 60), $dayItems->sum('total_minutes') % 60) }}</td>
                    <td style="background-color: #f1f5f9;"></td>
                </tr>
            @endif

            {{-- 2. ÉQUIPE DE NUIT --}}
            @if($nightItems->count() > 0)
                <tr>
                    <td colspan="7" class="section-header" style="background-color: #e0e7ff; color: #3730a3;">
                        🌙 ÉQUIPE DE NUIT ({{ $nightItems->count() }} Ouvriers) &bull; Forfait fixe (4h/présence)
                    </td>
                </tr>
                @foreach($nightItems as $item)
                    @php $w = $item['worker']; @endphp
                    <tr>
                        <td class="col-num">{{ $globalCounter++ }}</td>
                        <td class="col-name">{{ $w->last_name }}</td>
                        <td class="col-prenom">{{ $w->first_name }}</td>
                        <td class="col-shift">
                            <span style="color: #4338ca; font-weight: bold;">NUIT</span>
                        </td>
                        <td class="col-days">
                            {{ $item['days_present'] }} j
                        </td>
                        <td class="col-hours">
                            {{ $item['hours_formatted'] }}
                        </td>
                        <td class="col-sign">
                            <div class="sign-placeholder"></div>
                        </td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; text-transform: uppercase;">Sous-total Équipe de Nuit :</td>
                    <td style="text-align: center;">{{ $nightItems->sum('days_present') }} j</td>
                    <td style="text-align: center; color: #1e40af;">{{ sprintf('%dh%02d', floor($nightItems->sum('total_minutes') / 60), $nightItems->sum('total_minutes') % 60) }}</td>
                    <td style="background-color: #f1f5f9;"></td>
                </tr>
            @endif

            {{-- TOTAL GÉNÉRAL --}}
            <tr class="grand-total-row">
                <td colspan="4" style="text-align: right; text-transform: uppercase; font-size: 8.5pt;">TOTAL GÉNÉRAL ÉMARGEMENT :</td>
                <td style="text-align: center; font-size: 8.5pt;">{{ $items->sum('days_present') }} j</td>
                <td style="text-align: center; font-size: 8.5pt; color: #1e40af;">{{ $summary['total_hours_formatted'] }}</td>
                <td style="text-align: center; font-size: 7.5pt; color: #166534; font-weight: bold;">{{ $summary['active_workers'] }} SIGNATURES ATTENDUES</td>
            </tr>
        </tbody>
    </table>


    <!-- Footer -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="width: 50%;">
                    Bio Farm Trading SARL &bull; Système de Gestion &bull; Feuille d'émargement mensuelle des ouvriers (Confidentielle)
                </td>
                <td style="width: 50%; text-align: right;">
                    Document imprimé le {{ $generationDate->format('d/m/Y à H:i') }} &bull; Page <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
