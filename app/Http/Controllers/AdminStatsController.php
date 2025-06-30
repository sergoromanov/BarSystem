<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminStatsController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();

        $ordersLast7Days = Order::where('created_at', '>=', now()->subDays(7))->count();

        $totalRevenue = OrderItem::whereHas('order', function ($q) {
            $q->where('is_paid', true);
        })->sum('price');

        $topDrinks = OrderItem::select('drink_name', DB::raw('COUNT(*) as count'))
            ->groupBy('drink_name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $ordersByDay = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get()
            ->reverse();

        return view('admin.stats.index', compact(
            'totalOrders', 'ordersLast7Days', 'totalRevenue', 'topDrinks', 'ordersByDay'
        ));
    }
}
