<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - Order #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { font-size: 28px; font-weight: bold; color: #4f46e5; }
        .company { font-size: 14px; color: #666; }
        .customer-info, .order-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .total { font-size: 18px; font-weight: bold; text-align: right; margin-top: 20px; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FunShirt</div>
        <div class="company">Custom T-Shirts Studio</div>
    </div>

    <div class="customer-info">
        <strong>Customer:</strong> {{ $order->customer->user->name }}<br>
        <strong>NIF:</strong> {{ $order->nif }}<br>
        <strong>Address:</strong> {{ $order->address }}
    </div>

    <div class="order-info">
        <strong>Order #:</strong> {{ $order->id }}<br>
        <strong>Date:</strong> {{ optional($order->date)->format('d/m/Y') ?? optional($order->created_at)->format('d/m/Y') }}<br>
        <strong>Status:</strong> {{ ucfirst($order->status) }}
    </div>

    <h3>Items</h3>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Color / Size</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->tshirtImage->name }}</td>
                    <td>{{ $item->color_code }} / {{ $item->size }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>&euro;{{ number_format($item->unit_price, 2) }}</td>
                    <td>&euro;{{ number_format($item->sub_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total: &euro;{{ number_format($order->total_price, 2) }}
    </div>

    <div class="footer">
        Thank you for your purchase!<br>
        FunShirt - Your style, your shirt.
    </div>
</body>
</html>
