<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index(): View
    {
        // Apenas encomendas fechadas contam como receita efetiva.
        $totalSales = (float) Order::where('status', 'closed')->sum('total_price');
        $totalOrders = Order::count();
        $closedOrders = Order::where('status', 'closed')->count();
        $avgOrderValue = $closedOrders > 0 ? $totalSales / $closedOrders : 0;

        // Serie mensal compativel com SQLite.
        $monthlySales = Order::select(
                DB::raw("strftime('%Y-%m', date) as month"),
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'closed')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->sortBy('month')
            ->values();

        // Produtos mais vendidos entre encomendas fechadas.
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'closed')
            ->select('order_items.tshirt_image_id', DB::raw('SUM(order_items.qty) as total_qty'))
            ->groupBy('order_items.tshirt_image_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('tshirtImage')
            ->get();

        // Receita por categoria apenas para imagens do catalogo.
        $salesByCategory = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'closed')
            ->join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->leftJoin('categories', 'tshirt_images.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.sub_total) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        // Distribuicao do estado das encomendas.
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->sortBy('status')
            ->values();

        // Receita por origem: catalogo vs personalizadas.
        $catalogRevenue = (float) OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'closed')
            ->join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->whereNull('tshirt_images.customer_id')
            ->sum('order_items.sub_total');

        $customRevenue = (float) OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'closed')
            ->join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->whereNotNull('tshirt_images.customer_id')
            ->sum('order_items.sub_total');

        return view('admin.statistics.index', compact(
            'totalSales',
            'totalOrders',
            'closedOrders',
            'avgOrderValue',
            'monthlySales',
            'topProducts',
            'salesByCategory',
            'ordersByStatus',
            'catalogRevenue',
            'customRevenue'
        ));
    }
}
