@php($viewer = auth()->user())

<x-layouts::main-content title="My Orders" heading="Order History" subheading="Track the status of your submitted orders">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
            <div class="border-b border-zinc-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-zinc-900">Orders</h2>
                <p class="mt-1 text-sm text-zinc-500">
                    @if ($viewer?->isStaff())
                        Staff can review pending orders only and close them after processing.
                    @elseif ($viewer?->isAdmin())
                        Administrators can review every order in the platform.
                    @else
                        Customers see only their own orders.
                    @endif
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm">
                    <thead class="bg-zinc-50">
                        <tr class="text-left text-zinc-600">
                            <th class="px-6 py-3 font-semibold">Order</th>
                            <th class="px-6 py-3 font-semibold">Customer</th>
                            <th class="px-6 py-3 font-semibold">Date</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3 font-semibold">Total</th>
                            <th class="px-6 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 bg-white">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="px-6 py-4 font-medium text-zinc-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 text-zinc-600">{{ $order->customer?->user?->name ?? 'Unknown customer' }}</td>
                                <td class="px-6 py-4 text-zinc-600">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                        {{ $order->status === 'closed' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $order->status === 'canceled' ? 'bg-rose-100 text-rose-700' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-zinc-900">&euro;{{ number_format($order->total_price, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">
                                        View
                                    </a>

                                    @if ($viewer?->isStaff() && $order->status === 'pending')
                                        <form method="POST" action="{{ route('orders.updateStatus', $order) }}" class="ml-2 inline-flex">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="closed">
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-500">
                                                Close
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-zinc-500">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-layouts::main-content>
