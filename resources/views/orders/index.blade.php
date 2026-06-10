<x-layouts::main-content title="My Orders" heading="Order History" subheading="View status and details of your previous purchases">
    <div class="max-w-7xl mx-auto py-6">
        <div class="bg-zinc-50 border border-zinc-200 rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-100 text-zinc-600 uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4">Order #</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @foreach($orders as $order)
                        <tr class="hover:bg-zinc-100 transition">
                            <td class="px-6 py-4 font-bold">#{{ $order->id }}</td>
                            <td class="px-6 py-4 text-zinc-600">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <flux:badge variant="{{ $order->status === 'closed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->status) }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 font-bold">€{{ number_format($order->total_price, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <flux:button size="sm" href="{{ route('orders.show', $order) }}">View Details</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::main-content>