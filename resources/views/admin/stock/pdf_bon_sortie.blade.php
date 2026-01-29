<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bon de Sortie</title>
    <style>
        @page { margin: 5mm; }
        body { font-family: 'Times New Roman', serif; font-size: 10pt; margin: 0; padding: 0; color: #000; }
        .header { display: table; width: 100%; border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
        .logo-box { display: table-cell; width: 15%; vertical-align: middle; border-right: 1px solid #000; padding-right: 10px; text-align: center; }
        .title-box { display: table-cell; width: 85%; vertical-align: middle; padding-left: 10px; text-align: center; }
        
        h1 { color: #16a34a; font-size: 16pt; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-weight: 900; line-height: 1; }
        .subtitle { font-size: 6pt; text-transform: uppercase; margin-top: 3px; font-weight: bold; }
        
        .info-section { width: 100%; margin-bottom: 10px; }
        .date-row { text-align: right; margin-bottom: 5px; font-size: 9pt; }
        .info-row { margin-bottom: 5px; font-size: 10pt; }
        .label { font-weight: bold; text-decoration: underline; }
        .value { font-family: monospace; font-size: 11pt; font-weight: bold; text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; border: 1px solid #000; }
        th, td { border: 1px solid #000; padding: 3px 5px; font-size: 9pt; }
        th { background-color: #f3f3f3; font-weight: bold; text-align: center; border-bottom: 1px solid #000; }
        .col-ref { width: 30px; text-align: center; }
        .col-desc { text-align: left; padding-left: 5px; font-weight: bold; }
        .col-qty { width: 80px; text-align: center; font-weight: bold; font-family: monospace; font-size: 11pt; }

        .footer { width: 100%; margin-top: 15px; page-break-inside: avoid; }
        .footer-cols { display: table; width: 100%; }
        .footer-left { display: table-cell; width: 50%; }
        .footer-right { display: table-cell; width: 50%; text-align: center; }
        .sign-title { font-weight: bold; text-decoration: underline; margin-bottom: 40px; font-size: 10pt; }
        .sign-line { border-bottom: 1px dashed #000; width: 70%; margin: 0 auto; }
        
        .legal { margin-top: 10px; border-top: 1px solid #000; padding-top: 3px; text-align: center; font-size: 6pt; color: #555; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-box">
            <!-- Using absolute path for PDF is safer usually, or base64 -->
            <img src="{{ public_path('images/logo.jpg') }}" style="width: 100px; height: auto;">
        </div>
        <div class="title-box">
            <h1>Bio Farm Trading</h1>
            <div class="subtitle">Production - Transformation - Commercialisation des produits agricoles biologiques</div>
        </div>
    </div>

    <div class="info-section">
        <div class="date-row">
            <strong>Dates :</strong> <span style="border-bottom: 1px dotted #000; padding: 0 20px;">{{ date('d / m / Y') }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Bon de sortie N°</span> : 
            <span class="value">{{ $bonNumero ?? '..........' }}</span>
        </div>

        <div class="info-row">
            <span class="label">Client/destination</span> : 
            <span class="value">{{ $destinationName ?? '........................................' }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-ref">Réf.</th>
                <th class="col-desc">Description et désignation du produit</th>
                <th class="col-qty">Nombre de<br>conditionnement</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
                <tr>
                    <td class="col-ref">{{ $index + 1 }}</td>
                    <td class="col-desc">{{ $item['name'] }}</td>
                    <td class="col-qty">{{ $item['quantity'] }}</td>
                </tr>
            @endforeach
            <!-- Fill empty rows if few items -->
            @for($i = 0; $i < max(0, 15 - count($items)); $i++)
                <tr>
                    <td class="col-ref"></td>
                    <td class="col-desc" style="height: 25px;"></td>
                    <td class="col-qty"></td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-cols">
            <div class="footer-left"></div>
            <div class="footer-right">
                <div class="sign-title">Responsable gestion des stocks</div>
                <div class="sign-line"></div>
            </div>
        </div>
    </div>

    <div class="legal">
        <strong>BIO FARM TRADING</strong> - Produits bios Certifiés Par Ecocert S.A.S<br>
        RCCM: TG-LOM 2019 B 1488 - NIF 1001469316
    </div>

</body>
</html>
