<x-layouts::main-content title="Statistics" heading="Business Statistics" subheading="Sales overview and insights">
    <!-- KPI cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="text-sm text-gray-500">Total Sales</div>
            <div class="text-2xl font-bold">€{{ number_format($totalSales, 2) }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="text-sm text-gray-500">Total Orders</div>
            <div class="text-2xl font-bold">{{ $totalOrders }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="text-sm text-gray-500">Average Order</div>
            <div class="text-2xl font-bold">€{{ number_format($avgOrderValue, 2) }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="text-sm text-gray-500">Catalog / Custom Revenue</div>
            <div class="text-lg font-bold">€{{ number_format($catalogRevenue, 2) }} / €{{ number_format($customRevenue, 2) }}</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Monthly Sales (last 12 months)</h3>
            <canvas id="monthlyChart" height="200"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Orders by Status</h3>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>

    <!-- Top products -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-2">Top 5 Best‑Selling Products</h3>
        <ul class="list-disc pl-5">
            @forelse($topProducts as $item)
                <li>{{ $item->tshirtImage->name ?? 'Deleted' }} – {{ $item->total_qty }} sold</li>
            @empty
                <li>No sales yet.</li>
            @endforelse
        </ul>
    </div>

    <!-- Sales by category -->
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-2">Sales by Category</h3>
        <ul class="list-disc pl-5">
            @forelse($salesByCategory as $cat)
                <li>{{ $cat->name ?? 'Uncategorized' }} – €{{ number_format($cat->total, 2) }}</li>
            @empty
                <li>No data.</li>
            @endforelse
        </ul>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlyLabels = @json($monthlySales->pluck('month'));
        const monthlyTotals = @json($monthlySales->pluck('total'));
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: { labels: monthlyLabels, datasets: [{ label: 'Sales (€)', data: monthlyTotals, borderColor: '#4f46e5' }] }
        });

        const statusLabels = @json($ordersByStatus->pluck('status'));
        const statusCounts = @json($ordersByStatus->pluck('count'));
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: { labels: statusLabels, datasets: [{ data: statusCounts, backgroundColor: ['#22c55e', '#ef4444', '#f59e0b'] }] }
        });
    </script>
</x-layouts::main-content>