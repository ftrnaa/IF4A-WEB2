<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with('batik')
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->latest()
            ->get();

        $totalPembelian = $orders->sum('total');
         $orderCount = $orders->count();


       return view('pages.users.dashboard', compact(
    'orders',
    'totalPembelian',
    'orderCount'
));
    }
    
}