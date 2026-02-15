<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 30px; }
        .header { display: table; width: 100%; margin-bottom: 30px; }
        .header-left, .header-right { display: table-cell; vertical-align: top; }
        .header-right { text-align: right; }
        .company-name { font-size: 22px; font-weight: bold; color: #4F46E5; margin-bottom: 5px; }
        .invoice-title { font-size: 28px; font-weight: bold; color: #4F46E5; margin-bottom: 20px; }
        .info-block { margin-bottom: 20px; }
        .info-block h3 { font-size: 11px; text-transform: uppercase; color: #888; margin-bottom: 5px; }
        .info-block p { margin: 2px 0; }
        .meta-table { display: table; width: 100%; margin-bottom: 30px; }
        .meta-left, .meta-right { display: table-cell; vertical-align: top; width: 50%; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th { background: #4F46E5; color: white; padding: 8px 12px; text-align: left; font-size: 11px; text-transform: uppercase; }
        table.items td { padding: 8px 12px; border-bottom: 1px solid #eee; }
        table.items tr:nth-child(even) { background: #f9fafb; }
        .totals { width: 300px; margin-left: auto; }
        .totals table { width: 100%; }
        .totals td { padding: 5px 0; }
        .totals .total-row { font-size: 16px; font-weight: bold; border-top: 2px solid #4F46E5; padding-top: 10px; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="company-name">{{ $company['name'] }}</div>
            <p>{{ $company['address'] }}</p>
            @if($company['phone'])<p>{{ $company['phone'] }}</p>@endif
            @if($company['email'])<p>{{ $company['email'] }}</p>@endif
        </div>
        <div class="header-right">
            <div class="invoice-title">FACTURE</div>
        </div>
    </div>

    <div class="meta-table">
        <div class="meta-left">
            <div class="info-block">
                <h3>Facturé à</h3>
                <p><strong>{{ $order->user->name }}</strong></p>
                <p>{{ $order->user->email }}</p>
                @if($order->user->phone)<p>{{ $order->user->phone }}</p>@endif
                @if($order->user->address)<p>{{ $order->user->address }}</p>@endif
                @if($order->user->city)<p>{{ $order->user->city }}</p>@endif
            </div>
        </div>
        <div class="meta-right" style="text-align: right;">
            <div class="info-block">
                <h3>Détails facture</h3>
                <p><strong>N° :</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Date :</strong> {{ $invoice->issued_at->format('d/m/Y') }}</p>
                <p><strong>Commande :</strong> {{ $order->reference }}</p>
            </div>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Site source</th>
                <th style="text-align: center;">Qté</th>
                <th style="text-align: right;">Prix unit.</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ Str::limit($item->scraped_title, 60) }}</td>
                    <td>{{ $item->source_site }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->scraped_price, 2, ',', ' ') }} {{ $item->scraped_currency }}</td>
                    <td style="text-align: right;">{{ number_format($item->line_total, 2, ',', ' ') }} {{ $item->scraped_currency }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Sous-total</td>
                <td style="text-align: right;">{{ number_format($order->subtotal, 0, ',', ' ') }} {{ $order->currency }}</td>
            </tr>
            <tr>
                <td>Frais de service</td>
                <td style="text-align: right;">{{ number_format($order->service_fee, 0, ',', ' ') }} {{ $order->currency }}</td>
            </tr>
            @if($order->shipping_fee > 0)
            <tr>
                <td>Frais de livraison</td>
                <td style="text-align: right;">{{ number_format($order->shipping_fee, 0, ',', ' ') }} {{ $order->currency }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>TOTAL</td>
                <td style="text-align: right;">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->currency }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>{{ $company['name'] }} - {{ $company['address'] }}</p>
        <p>Merci pour votre confiance !</p>
    </div>
</body>
</html>
