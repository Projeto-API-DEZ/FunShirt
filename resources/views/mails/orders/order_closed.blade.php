<h1>Order Closed</h1>
<p>Hello {{ $order->customer->user->name }},</p>
<p>Your order #{{ $order->id }} has been marked as <strong>closed</strong>.</p>
<p>Your receipt is attached to this email when available.</p>
<p>Total Price: &euro;{{ number_format($order->total_price, 2) }}</p>
<p>Thank you for shopping with FunShirt!</p>
