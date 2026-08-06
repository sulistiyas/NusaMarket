<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalSales = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

        $status = $request->get('status');
        $query = Order::with(['buyer', 'store']);

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $recentOrders = $query->latest()->paginate(5)->withQueryString();

        return view('pages.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'recentOrders',
            'status'
        ));
    }
}
