<h1>Order Received</h1>
<p>Hello {{ $order->customer->user->name }},</p>
<p>Your order #{{ $order->id }} has been received and is now pending processing.</p>
<p>We will notify you once your items are shipped.</p>
<p>Order total: &euro;{{ number_format($order->total_price, 2) }}</p>
<p>
    You can review the order here:
    <a href="{{ route('orders.show', $order) }}">{{ route('orders.show', $order) }}</a>
</p>
<p>Thank you,<br>{{ config('app.name') }}</p>
