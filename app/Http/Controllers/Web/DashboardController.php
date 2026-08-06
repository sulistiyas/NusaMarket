<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $recentOrders = Order::with(['buyer', 'store'])->latest()->take(5)->get();

        return view('pages.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'recentOrders'
        ));
    }
}
