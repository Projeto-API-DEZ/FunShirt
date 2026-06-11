<x-mail::message>
# Thank you for your order!

Hello {{ $order->customer->user->name }},

Your order #{{ $order->id }} has been received and is now **pending** processing.

We will notify you once your items are shipped.

**Order total:** €{{ number_format($order->total_price, 2) }}

Thank you for shopping at FunShirt!

<x-mail::button :url="route('orders.show', $order)">
View Order Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>