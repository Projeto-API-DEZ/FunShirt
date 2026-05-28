<x-layouts::main-content title="Order #{{ $order->id }}" heading="Order Details" subheading="Reviewing individual transaction #{{ $order->id }}">
    <div class="max-w-5xl mx-auto py-6 space-y-6">
        <div class="p-6 bg-zinc-50 border border-zinc-200 dark:bg-gray-900 rounded-xl shadow-sm">
            <h3 class="font-bold text-lg mb-4 text-zinc-900 dark:text-zinc-100">Ordered Items</h3>
            <ul class="space-y-4">
                @foreach($order->items as $item)
                    <li class="flex justify-between items-center border-b pb-4 last:border-0 last:pb-0">
                        <div class="flex items-center gap-4">
                            <div class="size-12 bg-zinc-200 dark:bg-zinc-800 rounded-lg" style="background-color: #{{ $item->color_code }}"></div>
                            <div>
                                <p class="font-bold">{{ $item->tshirtImage->name }}</p>
                                <p class="text-xs text-zinc-500">Size: {{ $item->size }} | Qty: {{ $item->quantity }}</p>
                            </div>
                        </div>
                        <span class="font-bold">€{{ number_format($item->subtotal, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-zinc-50 border border-zinc-200 dark:bg-gray-900 rounded-xl shadow-sm">
                <h3 class="font-bold mb-2">Shipping Information</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $order->address }}</p>
            </div>
            <div class="p-6 bg-zinc-50 border border-zinc-200 dark:bg-gray-900 rounded-xl shadow-sm">
                <h3 class="font-bold mb-2">Payment Details</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Method: {{ $order->payment_type }}</p>
                <p class="text-sm font-mono text-zinc-500">{{ $order->payment_ref }}</p>
            </div>
        </div>
        
        @can('cancel', $order)
            <form action="{{ route('orders.cancel', $order) }}" method="POST">
                @csrf
                <flux:button variant="danger" type="submit">Cancel Order</flux:button>
            </form>
        @endcan
    </div>
</x-layouts::main-content>