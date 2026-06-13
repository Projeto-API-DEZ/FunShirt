<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sales' => Order::where('status', 'closed')->sum('total_price'),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'orders_closed' => Order::where('status', 'closed')->count(),
            'total_customers' => Customer::count(),
            'recent_sales' => Order::where('status', 'closed')->latest()->take(5)->get(),
            'sales_by_month' => Order::where('status', 'closed')
                ->select(DB::raw('strftime("%Y-%m", date) as month'), DB::raw('SUM(total_price) as total'))
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get(),
        ];

        return view('admin.statistics.index', compact('stats'));
    }
}