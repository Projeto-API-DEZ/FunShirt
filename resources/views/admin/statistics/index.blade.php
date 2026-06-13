<x-layouts::main-content
    title="Statistics"
    heading="Business Statistics"
    subheading="Sales overview and reporting dashboard"
>
    <div class="space-y-8">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Closed Sales</p>
                <p class="mt-3 text-2xl font-semibold text-zinc-900">&euro;{{ number_format($totalSales, 2) }}</p>
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
                <p class="mt-3 text-2xl font-semibold text-zinc-900">&euro;{{ number_format($avgOrderValue, 2) }}</p>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Catalog / Custom Revenue</p>
                <p class="mt-3 text-lg font-semibold text-zinc-900">&euro;{{ number_format($catalogRevenue, 2) }}</p>
                <p class="mt-1 text-sm text-zinc-500">Custom &euro;{{ number_format($customRevenue, 2) }}</p>
            </article>
        </section>

        @php($monthlyMax = max(1, (float) ($monthlySales->max('total') ?? 0)))
        @php($yearlyMax = max(1, (float) ($yearlySales->max('total') ?? 0)))
        @php($statusMax = max(1, (int) ($ordersByStatus->max('count') ?? 0)))

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm xl:col-span-2">
                <h2 class="text-lg font-semibold text-zinc-900">Monthly Revenue Chart</h2>
                <p class="mt-1 text-sm text-zinc-500">Visual comparison of the latest closed sales by month.</p>

                <div class="mt-6 space-y-4 rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                    @forelse ($monthlySales as $month)
                        @php($width = min(100, (((float) $month->total) / $monthlyMax) * 100))
                        <div class="grid gap-2 sm:grid-cols-[100px_minmax(0,1fr)_120px] sm:items-center">
                            <div class="text-sm font-medium text-zinc-700">{{ $month->month }}</div>
                            <div class="h-4 overflow-hidden rounded-full border border-zinc-200 bg-white">
                                <div class="h-full rounded-full" style="width: {{ $width }}%; background-color: #4f46e5;"></div>
                            </div>
                            <div class="text-right text-sm font-semibold text-zinc-900">&euro;{{ number_format((float) $month->total, 2) }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500">No monthly chart data available.</p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900">Order Status Chart</h2>
                <p class="mt-1 text-sm text-zinc-500">Current platform order distribution.</p>

                <div class="mt-6 space-y-4 rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                    @forelse ($ordersByStatus as $row)
                        @php($width = min(100, (((int) $row->count) / $statusMax) * 100))
                        @php($barColor = $row->status === 'closed' ? '#10b981' : ($row->status === 'pending' ? '#f59e0b' : '#f43f5e'))
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="capitalize font-medium text-zinc-700">{{ $row->status }}</span>
                                <span class="font-semibold text-zinc-900">{{ $row->count }}</span>
                            </div>
                            <div class="h-4 overflow-hidden rounded-full border border-zinc-200 bg-white">
                                <div class="h-full rounded-full" style="width: {{ $width }}%; background-color: {{ $barColor }};"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500">No status chart data available.</p>
                    @endforelse
                </div>
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
                                        <td class="px-4 py-3 text-right font-medium text-zinc-900">&euro;{{ number_format((float) $month->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900">Yearly Sales</h2>
                <p class="mt-1 text-sm text-zinc-500">Closed order revenue grouped by year.</p>

                <div class="mt-5 space-y-3 rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                    @forelse ($yearlySales as $year)
                        @php($width = min(100, (((float) $year->total) / $yearlyMax) * 100))
                        <div class="grid gap-2 sm:grid-cols-[72px_minmax(0,1fr)_120px] sm:items-center">
                            <div class="text-sm font-medium text-zinc-700">{{ $year->year }}</div>
                            <div class="h-4 overflow-hidden rounded-full border border-zinc-200 bg-white">
                                <div class="h-full rounded-full" style="width: {{ $width }}%; background-color: #0284c7;"></div>
                            </div>
                            <div class="text-right text-sm font-semibold text-zinc-900">&euro;{{ number_format((float) $year->total, 2) }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500">No yearly chart data available.</p>
                    @endforelse
                </div>

                <div class="mt-6 overflow-hidden rounded-lg border border-zinc-200">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-zinc-700">Year</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 bg-white">
                            @if ($yearlySales->isEmpty())
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-zinc-500">No closed yearly sales yet.</td>
                                </tr>
                            @else
                                @foreach ($yearlySales as $year)
                                    <tr>
                                        <td class="px-4 py-3 text-zinc-700">{{ $year->year }}</td>
                                        <td class="px-4 py-3 text-right font-medium text-zinc-900">&euro;{{ number_format((float) $year->total, 2) }}</td>
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
                                        <td class="px-4 py-3 text-right font-medium text-zinc-900">&euro;{{ number_format((float) $category->total, 2) }}</td>
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
                <h2 class="text-lg font-semibold text-zinc-900">Top Customers by Spending</h2>
                <p class="mt-1 text-sm text-zinc-500">Highest total closed-order spending across the platform.</p>

                <div class="mt-6 overflow-hidden rounded-lg border border-zinc-200">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-zinc-700">Customer</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700">Orders</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700">Spent</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 bg-white">
                            @if ($topCustomers->isEmpty())
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-zinc-500">No customer spending data available yet.</td>
                                </tr>
                            @else
                                @foreach ($topCustomers as $customer)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-zinc-900">{{ $customer->name }}</div>
                                            <div class="text-xs text-zinc-500">{{ $customer->email }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-medium text-zinc-900">{{ $customer->total_orders }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-zinc-900">&euro;{{ number_format((float) $customer->total_spent, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900">Customer Spending Chart</h2>
                <p class="mt-1 text-sm text-zinc-500">Visual ranking of the strongest customers by total closed-order value.</p>

                @php($customerMax = max(1, (float) ($topCustomers->max('total_spent') ?? 0)))
                <div class="mt-6 space-y-4 rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                    @forelse ($topCustomers->take(8) as $customer)
                        @php($width = min(100, (((float) $customer->total_spent) / $customerMax) * 100))
                        <div class="grid gap-2 sm:grid-cols-[minmax(0,180px)_minmax(0,1fr)_120px] sm:items-center">
                            <div class="truncate text-sm font-medium text-zinc-700">{{ $customer->name }}</div>
                            <div class="h-4 overflow-hidden rounded-full border border-zinc-200 bg-white">
                                <div class="h-full rounded-full" style="width: {{ $width }}%; background-color: #c026d3;"></div>
                            </div>
                            <div class="text-right text-sm font-semibold text-zinc-900">&euro;{{ number_format((float) $customer->total_spent, 2) }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500">No customer chart data available.</p>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
</x-layouts::main-content>
