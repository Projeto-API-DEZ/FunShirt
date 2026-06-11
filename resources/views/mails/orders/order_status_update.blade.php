<h1>Order Status Updated</h1>
<p>Hello {{ $order->customer->user->name }},</p>
<p>The status of your order #{{ $order->id }} has been updated to: <strong>{{ ucfirst($order->status) }}</strong>.</p>
@if($order->status == 'closed')
    <p>Your order has been shipped! You can find the receipt attached or download it from your profile.</p>
@elseif($order->status == 'canceled')
    <p>We regret to inform you that your order has been canceled.</p>
    @if($order->reason_for_cancellation)
        <p>Reason: {{ $order->reason_for_cancellation }}</p>
    @endif
@endif
<p>Thank you for choosing FunShirt!</p>