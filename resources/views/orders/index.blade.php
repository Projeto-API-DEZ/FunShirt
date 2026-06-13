@php($viewer = auth()->user())

<x-layouts::main-content title="My Orders" heading="Order History" subheading="Track the status of your submitted orders">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('orders.index') }}" class="space-y-5">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <div>
                        <label for="order_id" class="mb-1 block text-sm font-medium text-zinc-700">Order ID</label>
                        <input
                            id="order_id"
                            name="order_id"
                            type="text"
                            value="{{ $filters['order_id'] ?? '' }}"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Example: 1024"
                        >
                    </div>

                    @if (! $viewer?->isStaff())
                        <div>
                            <label for="status" class="mb-1 block text-sm font-medium text-zinc-700">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="">All statuses</option>
                                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                                <option value="closed" @selected(($filters['status'] ?? '') === 'closed')>Closed</option>
                                <option value="canceled" @selected(($filters['status'] ?? '') === 'canceled')>Canceled</option>
                            </select>
                        </div>
                    @endif

                    @if ($viewer?->isAdmin() || $viewer?->isStaff())
                        <div>
                            <label for="customer" class="mb-1 block text-sm font-medium text-zinc-700">Customer</label>
                            <input
                                id="customer"
                                name="customer"
                                type="text"
                                value="{{ $filters['customer'] ?? '' }}"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                placeholder="Search customer name"
                            >
                        </div>

                        <div>
                            <label for="nif" class="mb-1 block text-sm font-medium text-zinc-700">NIF</label>
                            <input
                                id="nif"
                                name="nif"
                                type="text"
                                value="{{ $filters['nif'] ?? '' }}"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                placeholder="Search NIF"
                            >
                        </div>
                    @endif

                    <div>
                        <label for="date_from" class="mb-1 block text-sm font-medium text-zinc-700">Date From</label>
                        <input
                            id="date_from"
                            name="date_from"
                            type="date"
                            value="{{ $filters['date_from'] ?? '' }}"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>

                    <div>
                        <label for="date_to" class="mb-1 block text-sm font-medium text-zinc-700">Date To</label>
                        <input
                            id="date_to"
                            name="date_to"
                            type="date"
                            value="{{ $filters['date_to'] ?? '' }}"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                        Apply Filters
                    </button>
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

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
                            <th class="px-6 py-3 font-semibold">Cancellation</th>
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
                                <td class="px-6 py-4 text-zinc-600">
                                    {{ $order->reason_for_cancellation ?: '—' }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-zinc-900">&euro;{{ number_format($order->total_price, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">
                                        View
                                    </a>

                                    @if (($viewer?->isStaff() || $viewer?->isAdmin()) && $order->status === 'pending')
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
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-zinc-500">
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
