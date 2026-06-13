<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TshirtImage;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Total sales and order counts
        $totalSales = Order::sum('total_price');
        $totalOrders = Order::count();
        $avgOrderValue = $totalOrders ? $totalSales / $totalOrders : 0;

        // Monthly sales (last 12 months) – SQLite compatible
        $monthlySales = Order::select(
                DB::raw("strftime('%Y-%m', date) as month"),
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'closed')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Top 5 best-selling products
        $topProducts = OrderItem::select('tshirt_image_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('tshirt_image_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('tshirtImage')
            ->get();

        // Sales by category (only for catalog images, custom have null category)
        $salesByCategory = OrderItem::join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->leftJoin('categories', 'tshirt_images.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.sub_total) as total'))
            ->groupBy('categories.name')
            ->get();

        // Orders grouped by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Revenue from catalog vs custom images
        $catalogRevenue = OrderItem::join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->whereNull('tshirt_images.customer_id')
            ->sum('order_items.sub_total');

        $customRevenue = OrderItem::join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->whereNotNull('tshirt_images.customer_id')
            ->sum('order_items.sub_total');

        return view('admin.statistics.index', compact(
            'totalSales', 'totalOrders', 'avgOrderValue', 'monthlySales',
            'topProducts', 'salesByCategory', 'ordersByStatus',
            'catalogRevenue', 'customRevenue'
        ));
    }
}