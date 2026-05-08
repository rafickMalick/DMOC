<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture DMC</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 12px; }
        h1 { margin: 0 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <h1>Facture DMC - Commande #{{ $order->id }}</h1>
    <p class="muted">Date: {{ $order->created_at?->format('d/m/Y H:i') }}</p>
    <p>Client: {{ $order->user?->name }} ({{ $order->user?->email }})</p>
    <p>Adresse: {{ $order->shipping_address ?? 'N/A' }}</p>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantite</th>
                <th>Prix unitaire</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product?->name ?? 'Produit supprime' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format((int) $item->price_xof, 0, ',', ' ') }} XOF</td>
                    <td>{{ number_format((int) ($item->quantity * $item->price_xof), 0, ',', ' ') }} XOF</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $subtotal = (int) $order->items->sum(fn ($i) => $i->quantity * $i->price_xof);
    @endphp
    <p style="margin-top: 14px;">Sous-total: {{ number_format($subtotal, 0, ',', ' ') }} XOF</p>
    <p>Frais de livraison: {{ number_format((int) $order->delivery_fee_xof, 0, ',', ' ') }} XOF</p>
    <p><strong>Total: {{ number_format((int) $order->total_xof, 0, ',', ' ') }} XOF</strong></p>
</body>
</html>
