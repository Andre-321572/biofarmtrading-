<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste Officielle du Personnel – Bio Farm Trading</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 12mm 12mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            background: #ffffff;
            line-height: 1.3;
        }

        /* ===== EN-TÊTE OFFICIEL BFT ===== */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2.5px solid #059669;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .logo-cell {
            width: 85px;
            text-align: left;
        }

        .logo-img {
            width: 72px;
            height: auto;
            max-height: 60px;
        }

        .company-info {
            text-align: center;
        }

        .company-name {
            font-size: 15pt;
            font-weight: bold;
            color: #065f46;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .company-activity {
            font-size: 8pt;
            font-weight: bold;
            color: #1e293b;
            margin-top: 2px;
        }

        .company-legal {
            font-size: 6.8pt;
            color: #64748b;
            margin-top: 3px;
        }

        .header-meta {
            width: 110px;
            text-align: right;
            font-size: 7pt;
            color: #475569;
            line-height: 1.35;
        }

        .header-meta strong {
            color: #065f46;
        }

        /* ===== TITRE DU DOCUMENT ===== */
        .title-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 5px solid #059669;
            padding: 7px 12px;
            margin-bottom: 10px;
        }

        .doc-title {
            font-size: 11.5pt;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-subtitle {
            font-size: 7.2pt;
            color: #047857;
            margin-top: 1px;
            font-style: italic;
        }

        /* ===== STATISTIQUES RÉCAPITULATIVES ===== */
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .kpi-table td {
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .kpi-card-1 { background-color: #f8fafc; border-color: #cbd5e1; }
        .kpi-card-2 { background-color: #ecfdf5; border-color: #a7f3d0; }
        .kpi-card-3 { background-color: #fffbeb; border-color: #fde68a; }
        .kpi-card-4 { background-color: #f5f3ff; border-color: #ddd6fe; }

        .kpi-label {
            font-size: 6.8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.3px;
        }

        .kpi-value {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 1px;
        }

        .kpi-card-1 .kpi-value { color: #0f172a; }
        .kpi-card-2 .kpi-value { color: #047857; }
        .kpi-card-3 .kpi-value { color: #b45309; }
        .kpi-card-4 .kpi-value { color: #6d28d9; }

        /* ===== TABLEAU PRINCIPAL DU PERSONNEL ===== */
        .staff-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.8pt;
            margin-bottom: 14px;
        }

        .staff-table thead tr {
            background-color: #065f46;
            color: #ffffff;
        }

        .staff-table th {
            padding: 6px 6px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 6.8pt;
            letter-spacing: 0.4px;
            border: 1px solid #047857;
            text-align: left;
            vertical-align: middle;
        }

        .staff-table th.center { text-align: center; }
        .staff-table th.right { text-align: right; }

        .staff-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .staff-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .staff-table td {
            padding: 5px 6px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .staff-table td.center { text-align: center; }
        .staff-table td.right { text-align: right; }

        .matricule-tag {
            font-family: monospace;
            font-weight: bold;
            color: #065f46;
            font-size: 7.5pt;
        }

        .name-strong {
            font-weight: bold;
            color: #0f172a;
        }

        .position-name {
            font-weight: bold;
            color: #1e3a8a;
        }

        .dept-name {
            font-size: 6.8pt;
            color: #64748b;
        }

        /* Badges contrat */
        .badge-contract {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 6.5pt;
            font-weight: bold;
        }
        .b-cdi { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .b-cdd { background-color: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; }
        .b-stage { background-color: #ccfbf1; color: #115e59; border: 1px solid #99f6e4; }
        .b-prest { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

        /* Badges statut */
        .badge-status {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .b-actif { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .b-conge { background-color: #fef3c7; color: #854d0e; border: 1px solid #fde68a; }
        .b-susp  { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .b-autre { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

        /* ===== SIGNATURES ===== */
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .signatures-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .sig-box {
            border: 1px dashed #cbd5e1;
            background-color: #fbfcfe;
            border-radius: 4px;
            padding: 8px 12px;
            height: 75px;
        }

        .sig-title {
            font-size: 7.5pt;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
        }

        .sig-sub {
            font-size: 6.5pt;
            color: #94a3b8;
            margin-top: 1px;
        }

        /* ===== FOOTER ===== */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #cbd5e1;
            margin-top: 10px;
            padding-top: 5px;
        }

        .footer-table td {
            border: none;
            font-size: 6.8pt;
            color: #64748b;
        }
    </style>
</head>
<body>

    {{-- EN-TÊTE OFFICIEL AVEC LOGO BIO FARM TRADING --}}
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @php
                    $logoPath = null;
                    if (file_exists(public_path('images/biofarm_logo.jpg'))) {
                        $logoPath = public_path('images/biofarm_logo.jpg');
                    } elseif (file_exists(public_path('images/logo.jpg'))) {
                        $logoPath = public_path('images/logo.jpg');
                    }
                @endphp
                @if($logoPath)
                    <img src="{{ $logoPath }}" class="logo-img" alt="Logo Bio Farm Trading">
                @else
                    <div style="font-size: 20pt; color:#065f46;">🌿</div>
                @endif
            </td>
            <td class="company-info">
                <div class="company-name">BIO FARM TRADING SARL</div>
                <div class="company-activity">Production &bull; Transformation &bull; Commercialisation de Produits Agricoles Biologiques</div>
                <div class="company-legal">
                    Siège social : Lomé, TOGO &nbsp;|&nbsp; RCCM : TG-LOM 2019 B 1488 &nbsp;|&nbsp; NIF : 1001469316<br>
                    Tél : (+228) 92 02 01 10 / 90 00 00 00 &nbsp;|&nbsp; Email : contact@biofarmtrading.com
                </div>
            </td>
            <td class="header-meta">
                <div><strong>SERVICE R.H.</strong></div>
                <div>Édité le : {{ now()->format('d/m/Y') }}</div>
                <div>Heure : {{ now()->format('H:i') }}</div>
                <div style="color:#059669; font-weight:bold; margin-top:2px;">USAGE INTERNE</div>
            </td>
        </tr>
    </table>

    {{-- TITRE DU DOCUMENT --}}
    <div class="title-box">
        <div class="doc-title">📋 Répertoire Officiel du Personnel Permanent</div>
        <div class="doc-subtitle">
            Document administratif officiel recensant exclusivement les cadres, agents administratifs et personnel permanent de Bio Farm Trading (hors ouvriers de production).
        </div>
    </div>

    {{-- RÉCAPITULATIF SYNTHÉTIQUE --}}
    <table class="kpi-table">
        <tr>
            <td class="kpi-card-1" style="width: 25%;">
                <div class="kpi-label">Effectif Total du Personnel</div>
                <div class="kpi-value">{{ $totalStaff }} collaborateur(s)</div>
            </td>
            <td class="kpi-card-2" style="width: 25%;">
                <div class="kpi-label">Personnel Actif en Poste</div>
                <div class="kpi-value">{{ $activeStaff }} actif(s)</div>
            </td>
            <td class="kpi-card-3" style="width: 25%;">
                <div class="kpi-label">En Congé / Suspension</div>
                <div class="kpi-value">{{ $onLeaveStaff }}</div>
            </td>
            <td class="kpi-card-4" style="width: 25%;">
                <div class="kpi-label">Masse Salariale Mensuelle Fixe</div>
                <div class="kpi-value">{{ number_format($totalMonthlyPayroll, 0, ',', ' ') }} <span style="font-size:8pt; font-weight:normal;">FCFA</span></div>
            </td>
        </tr>
    </table>

    {{-- LISTE DU PERSONNEL --}}
    <table class="staff-table">
        <thead>
            <tr>
                <th style="width: 24px;" class="center">N°</th>
                <th style="width: 70px;">Matricule</th>
                <th style="width: 170px;">Nom & Prénoms</th>
                <th style="width: 32px;" class="center">Genre</th>
                <th style="width: 140px;">Poste / Fonction</th>
                <th style="width: 110px;">Département</th>
                <th style="width: 52px;" class="center">Contrat</th>
                <th style="width: 65px;" class="center">Embauche</th>
                <th style="width: 85px;" class="right">Salaire Fixe</th>
                <th style="width: 55px;" class="center">Statut</th>
                <th style="width: 85px;">Téléphone</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $index => $emp)
                <tr>
                    <td class="center" style="color: #64748b; font-size: 7.2pt;">{{ $index + 1 }}</td>

                    <td>
                        <span class="matricule-tag">{{ $emp->matricule ?? '—' }}</span>
                    </td>

                    <td>
                        <span class="name-strong">{{ mb_strtoupper($emp->last_name) }}</span> {{ $emp->first_name }}
                        @if($emp->email)
                            <div style="font-size: 6.5pt; color: #64748b;">{{ $emp->email }}</div>
                        @endif
                    </td>

                    <td class="center">
                        @if($emp->gender === 'M')
                            <span style="font-weight:bold; color:#1d4ed8;">M</span>
                        @elseif($emp->gender === 'F')
                            <span style="font-weight:bold; color:#be185d;">F</span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>

                    <td>
                        <span class="position-name">
                            {{ $emp->jobPosition?->title ?? 'Personnel non affecté' }}
                        </span>
                    </td>

                    <td>
                        <span class="dept-name">
                            {{ $emp->jobPosition?->department ?? 'Général' }}
                        </span>
                    </td>

                    <td class="center">
                        @php
                            $c = strtoupper($emp->contract_type ?? 'CDI');
                            $cClass = match($c) {
                                'CDI' => 'b-cdi',
                                'CDD' => 'b-cdd',
                                'STAGE' => 'b-stage',
                                'PRESTATION' => 'b-prest',
                                default => 'b-cdi',
                            };
                        @endphp
                        <span class="badge-contract {{ $cClass }}">{{ $c }}</span>
                    </td>

                    <td class="center">
                        @if($emp->hired_at)
                            {{ $emp->hired_at->format('d/m/Y') }}
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>

                    <td class="right" style="font-weight: bold; color: #0f172a;">
                        @if($emp->monthly_salary)
                            {{ number_format($emp->monthly_salary, 0, ',', ' ') }} <span style="font-size:6.5pt; color:#64748b; font-weight:normal;">F</span>
                        @else
                            <span style="color:#94a3b8; font-weight:normal; font-style:italic;">—</span>
                        @endif
                    </td>

                    <td class="center">
                        @php
                            $st = strtolower($emp->status ?? 'actif');
                            $stClass = match($st) {
                                'actif' => 'b-actif',
                                'conge' => 'b-conge',
                                'suspendu' => 'b-susp',
                                default => 'b-autre',
                            };
                            $stLabel = match($st) {
                                'actif' => 'Actif',
                                'conge' => 'Congé',
                                'suspendu' => 'Suspendu',
                                'demissionnaire' => 'Démission',
                                'licencie' => 'Licencié',
                                default => ucfirst($st),
                            };
                        @endphp
                        <span class="badge-status {{ $stClass }}">{{ $stLabel }}</span>
                    </td>

                    <td>
                        {{ $emp->phone ?? '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 25px; color: #64748b; font-style: italic;">
                        Aucun membre du personnel enregistré pour le moment.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ZONE DE SIGNATURES OFFICIELLES --}}
    <table class="signatures-table">
        <tr>
            <td style="width: 45%;">
                <div class="sig-box">
                    <div class="sig-title">Le Responsable des Ressources Humaines</div>
                    <div class="sig-sub">Visa, date et mention manuscrite « Lu et approuvé »</div>
                </div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%;">
                <div class="sig-box">
                    <div class="sig-title">La Direction Générale</div>
                    <div class="sig-sub">Signature et Cachet officiel de Bio Farm Trading SARL</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- PIED DE PAGE --}}
    <table class="footer-table">
        <tr>
            <td style="text-align: left;">
                <strong>Bio Farm Trading SARL</strong> &bull; Répertoire Officiel du Personnel &bull; Strictement confidentiel
            </td>
            <td style="text-align: right;">
                Document certifié conforme &bull; Généré le {{ now()->format('d/m/Y à H:i') }} &bull; Total : <strong>{{ $totalStaff }} collaborateur(s)</strong>
            </td>
        </tr>
    </table>

</body>
</html>
