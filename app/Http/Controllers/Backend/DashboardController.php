<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $today = Carbon::today();
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::tomorrow();

        // Today's orders
        $todayOrders = Order::whereDate('created_at', $today);

        // Sales totals
        $todaySales = (clone $todayOrders)->sum('total');
        $totalSales = Order::sum('total');

        // Revenue excludes shipping charges
        $todayRevenue = (clone $todayOrders)->sum('subtotal');
        $totalRevenue = Order::sum('subtotal');

        // Sales for the last 7 days
        $salesByDay = Order::query()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate)
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->pluck('total', 'date');

        // Revenue for the last 7 days
        $revenueByDay = Order::query()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate)
            ->selectRaw('DATE(created_at) as date, SUM(subtotal) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->pluck('total', 'date');

        // Include days with no orders
        $chartLabels = [];
        $salesChartData = [];
        $revenueChartData = [];

        for ($date = $startDate->copy(); $date < $endDate; $date->addDay()) {
            $key = $date->toDateString();

            $chartLabels[] = $date->format('D, d M');
            $salesChartData[] = (float) ($salesByDay[$key] ?? 0);
            $revenueChartData[] = (float) ($revenueByDay[$key] ?? 0);
        }

        return view('backend.dashboard', compact(
            'todaySales',
            'totalSales',
            'todayRevenue',
            'totalRevenue',
            'chartLabels',
            'salesChartData',
            'revenueChartData'
        ));
    }
}
