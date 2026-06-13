@php($viewer = auth()->user())

<x-layouts::main-content title="Order #{{ $order->id }}" heading="Order Details" subheading="Review the selected items and current status">
    <div class="mx-auto max-w-6xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
            <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
                <div class="border-b border-zinc-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-zinc-900">Ordered Items</h2>
                </div>

                <div class="divide-y divide-zinc-100">
                    @foreach ($order->items as $item)
                        @php($itemColor = '#' . ltrim((string) ($item->color_code ?? 'e4e4e7'), '#'))
                        @php($isCustomImage = (bool) ($item->tshirtImage?->custom ?? $item->custom ?? false))
                        <div class="flex items-start justify-between gap-4 px-6 py-4">
                            <div class="flex items-start gap-4">
                                <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-lg border border-zinc-200" style="background-color: {{ $itemColor }};">
                                    @if ($item->tshirtImage?->image_url)
                                        <img
                                            src="{{ route('public.storage', ['path' => 'tshirt_images/' . $item->tshirtImage->image_url]) }}"
                                            alt="{{ $item->tshirtImage?->name ?? 'Deleted image' }}"
                                            class="h-full w-full object-contain"
                                        >
                                    @endif
                                </div>

                                <div>
                                    <p class="font-medium text-zinc-900">{{ $item->tshirtImage?->name ?? 'Deleted image' }}</p>
                                    <p class="mt-1 text-xs font-medium {{ $isCustomImage ? 'text-indigo-600' : 'text-zinc-500' }}">
                                        {{ $isCustomImage ? 'Custom image' : 'Catalog image' }}
                                    </p>
                                    <p class="mt-1 text-sm text-zinc-500">Color: {{ strtoupper((string) $item->color_code) }} | Size: {{ $item->size }} | Qty: {{ $item->qty }}</p>
                                    @if ($item->tshirtImage?->image_url)
                                        <div class="mt-3 flex flex-wrap items-center gap-2">
                                            <a
                                                href="{{ route('orders.items.image', ['order' => $order, 'item' => $item]) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center justify-center rounded-md border border-zinc-300 px-3 py-1.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50"
                                            >
                                                Preview Image
                                            </a>
                                            <a
                                                href="{{ route('orders.items.download', ['order' => $order, 'item' => $item]) }}"
                                                class="inline-flex items-center justify-center rounded-md border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100"
                                            >
                                                Download Image
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-zinc-500">&euro;{{ number_format($item->unit_price, 2) }} each</p>
                                <p class="font-semibold text-zinc-900">&euro;{{ number_format($item->sub_total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <aside class="space-y-6">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-zinc-900">Summary</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        @if ($viewer?->isStaff())
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-zinc-500">Customer</dt>
                                <dd class="font-medium text-zinc-900">{{ $order->customer?->user?->name ?? 'Unknown customer' }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-zinc-500">NIF</dt>
                                <dd class="font-medium text-zinc-900">{{ $order->nif ?: '-' }}</dd>
                            </div>
                        @endif

                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-zinc-500">Order ID</dt>
                            <dd class="font-medium text-zinc-900">#{{ $order->id }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-zinc-500">Status</dt>
                            <dd>
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                    {{ $order->status === 'closed' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $order->status === 'canceled' ? 'bg-rose-100 text-rose-700' : '' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-zinc-500">Total</dt>
                            <dd class="font-semibold text-zinc-900">&euro;{{ number_format($order->total_price, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-zinc-500">Payment</dt>
                            <dd class="font-medium text-zinc-900">{{ $order->payment_type }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-zinc-500">Reference</dt>
                            <dd class="font-medium text-zinc-900">{{ $order->payment_ref }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-zinc-900">Delivery</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600">{{ $order->address }}</p>

                    @if ($order->notes)
                        <div class="mt-4 rounded-lg bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Notes</p>
                            <p class="mt-2 text-sm text-zinc-700">{{ $order->notes }}</p>
                        </div>
                    @endif

                    @if ($viewer?->isStaff() && $order->status === 'pending')
                        <form method="POST" action="{{ route('orders.updateStatus', $order) }}" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-500">
                                Close Order
                            </button>
                        </form>
                    @endif

                    @if (! $viewer?->isStaff())
                        @if ($order->status === 'closed' && $order->receipt_url)
                            <a href="{{ route('orders.receipt', $order) }}" class="mt-4 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">
                                Download PDF Receipt
                            </a>
                        @else
                            <p class="mt-4 text-sm text-zinc-500">PDF receipt will be available after the order is closed.</p>
                        @endif

                        @can('cancel', $order)
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-rose-300 bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition hover:bg-rose-100">
                                    Cancel order
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </aside>
        </div>
    </div>
</x-layouts::main-content>
