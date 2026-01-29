<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport de Ventes - {{ $monthName }}</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 100px; height: auto; margin-bottom: 10px; }
        .shop-info { margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; bg-color: #e9ecef; }
        h1 { color: #2d6a4f; margin-bottom: 5px; }
        h2 { font-size: 16px; color: #555; margin-top: 0; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.jpg') }}" class="logo" alt="Bio Farm Trading">
        <h1>BIO FARM TRADING</h1>
        <h2>Rapport Mensuel de Ventes</h2>
    </div>

    <div class="shop-info">
        <strong>Boutique :</strong> {{ $shop->name }}<br>
        <strong>Mois :</strong> {{ $monthName }}<br>
        <strong>Date d'émission :</strong> {{ now()->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th class="text-right">Quantité Vendue</th>
                <th class="text-right">Chiffre d'Affaires</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productStats as $stat)
                <tr>
                    <td>{{ $stat->name }}</td>
                    <td class="text-right">{{ $stat->total_qty }}</td>
                    <td class="text-right">{{ number_format($stat->total_revenue, 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL</td>
                <!-- Sum qty if needed, or leave empty -->
                <td class="text-right">-</td>
                <td class="text-right">{{ number_format($monthTotal, 0, ',', ' ') }} FCFA</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #888;">
        <p>Ce document est généré automatiquement par le système Bio Farm Trading.</p>
    </div>
</body>
</html>
