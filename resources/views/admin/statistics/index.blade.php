<x-layouts::main-content
    title="Statistics"
    heading="Business Statistics"
    subheading="Sales overview and reporting dashboard"
>
    <div class="space-y-8">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Closed Sales</p>
                <p class="mt-3 text-2xl font-semibold text-zinc-900">€{{ number_format($totalSales, 2) }}</p>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Total Orders</p>
                <p class="mt-3 text-2xl font-semibold text-zinc-900">{{ $totalOrders }}</p>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Closed Orders</p>
                <p class="mt-3 text-2xl font-semibold text-zinc-900">{{ $closedOrders }}</p>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Average Closed Order</p>
                <p class="mt-3 text-2xl font-semibold text-zinc-900">€{{ number_format($avgOrderValue, 2) }}</p>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Catalog / Custom Revenue</p>
                <p class="mt-3 text-lg font-semibold text-zinc-900">€{{ number_format($catalogRevenue, 2) }}</p>
                <p class="mt-1 text-sm text-zinc-500">Custom €{{ number_format($customRevenue, 2) }}</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <article class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900">Monthly Sales</h2>
                <p class="mt-1 text-sm text-zinc-500">Closed order revenue grouped by month.</p>

                <div class="mt-6 overflow-hidden rounded-lg border border-zinc-200">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-zinc-700">Month</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 bg-white">
                            @if ($monthlySales->isEmpty())
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-zinc-500">No closed sales yet.</td>
                                </tr>
                            @else
                                @foreach ($monthlySales as $month)
                                    <tr>
                                        <td class="px-4 py-3 text-zinc-700">{{ $month->month }}</td>
                                        <td class="px-4 py-3 text-right font-medium text-zinc-900">€{{ number_format((float) $month->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900">Orders by Status</h2>
                <p class="mt-1 text-sm text-zinc-500">Current distribution of order states.</p>

                <div class="mt-6 overflow-hidden rounded-lg border border-zinc-200">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-zinc-700">Status</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700">Count</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 bg-white">
                            @if ($ordersByStatus->isEmpty())
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-zinc-500">No orders available.</td>
                                </tr>
                            @else
                                @foreach ($ordersByStatus as $row)
                                    <tr>
                                        <td class="px-4 py-3 capitalize text-zinc-700">{{ $row->status }}</td>
                                        <td class="px-4 py-3 text-right font-medium text-zinc-900">{{ $row->count }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <article class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900">Top 5 Best-Selling Designs</h2>
                <p class="mt-1 text-sm text-zinc-500">Products ordered by sold quantity.</p>

                <div class="mt-6 overflow-hidden rounded-lg border border-zinc-200">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-zinc-700">Design</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700">Sold</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 bg-white">
                            @if ($topProducts->isEmpty())
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-zinc-500">No product sales recorded yet.</td>
                                </tr>
                            @else
                                @foreach ($topProducts as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-zinc-700">{{ $item->tshirtImage->name ?? 'Deleted image' }}</td>
                                        <td class="px-4 py-3 text-right font-medium text-zinc-900">{{ $item->total_qty }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900">Sales by Category</h2>
                <p class="mt-1 text-sm text-zinc-500">Catalog revenue grouped by category.</p>

                <div class="mt-6 overflow-hidden rounded-lg border border-zinc-200">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-zinc-700">Category</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 bg-white">
                            @if ($salesByCategory->isEmpty())
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-zinc-500">No category revenue available yet.</td>
                                </tr>
                            @else
                                @foreach ($salesByCategory as $category)
                                    <tr>
                                        <td class="px-4 py-3 text-zinc-700">{{ $category->name ?? 'Uncategorized' }}</td>
                                        <td class="px-4 py-3 text-right font-medium text-zinc-900">€{{ number_format((float) $category->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </div>
</x-layouts::main-content>
