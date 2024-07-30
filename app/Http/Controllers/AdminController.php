<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        // Get today's sales
        $todaysSales = Order::whereDate('created_at', $today)->sum('total_price');

        // Get yesterday's sales
        $yesterdaysSales = Order::whereDate('created_at', $yesterday)->sum('total_price');

        // Calculate the percentage increase or decrease
        if ($yesterdaysSales > 0) {
            $percentageChange = (($todaysSales - $yesterdaysSales) / $yesterdaysSales) * 100;
        } else {
            $percentageChange = $todaysSales > 0 ? 100 : 0;
        }

        $totalPrice = \DB::table('orders')->sum('total_price');
        $todaySales = \DB::table('orders')->whereDate('created_at', today())->sum('total_price');
        $yesterdaySales = \DB::table('orders')->whereDate('created_at', today()->subDay())->sum('total_price');
        $weekSales = \DB::table('orders')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_price');
        $monthSales = \DB::table('orders')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_price');

        return view('admin.dashboard', compact('percentageChange','totalPrice','todaySales', 'yesterdaySales', 'weekSales', 'monthSales'));
    }
}
