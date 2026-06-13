<x-layouts::main-content title="Business Statistics" heading="Store Analytics" subheading="Overview of sales and performance metrics">
    <div class="max-w-7xl mx-auto py-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <p class="text-sm font-medium text-zinc-500">Total Sales</p>
                <p class="text-2xl font-bold">€{{ number_format($stats['total_sales'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <p class="text-sm font-medium text-zinc-500">Orders Closed</p>
                <p class="text-2xl font-bold">{{ $stats['orders_closed'] }}</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <p class="text-sm font-medium text-zinc-500">Orders Pending</p>
                <p class="text-2xl font-bold">{{ $stats['orders_pending'] }}</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <p class="text-sm font-medium text-zinc-500">Total Customers</p>
                <p class="text-2xl font-bold">{{ $stats['total_customers'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <h3 class="text-lg font-bold mb-4">Monthly Sales</h3>
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-zinc-500">
                            <th class="pb-2">Month</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['sales_by_month'] as $month)
                            <tr class="border-t border-zinc-100 dark:border-zinc-800">
                                <td class="py-2">{{ $month->month }}</td>
                                <td class="py-2 text-right">€{{ number_format($month->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <h3 class="text-lg font-bold mb-4">Recent Sales</h3>
                <div class="space-y-4">
                    @foreach($stats['recent_sales'] as $order)
                        <div class="flex justify-between items-center border-b border-zinc-50 dark:border-zinc-800 pb-2">
                            <div>
                                <p class="font-medium">Order #{{ $order->id }}</p>
                                <p class="text-xs text-zinc-500">{{ $order->customer->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-sm">€{{ number_format($order->total_price, 2) }}</p>
                                <p class="text-[10px] text-zinc-400">{{ $order->date }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts::main-content>