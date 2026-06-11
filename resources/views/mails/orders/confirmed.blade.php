<h1>Order Confirmed</h1>
<p>Hello {{ $order->customer->user->name }},</p>
<p>Your order #{{ $order->id }} has been successfully placed and is now being processed.</p>
<p>Total Price: €{{ number_format($order->total_price, 2) }}</p>
<p>Thank you for shopping with FunShirt!</p>