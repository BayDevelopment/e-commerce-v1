<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{


    public function index()
    {
        $userId = Auth::id();

        $AllProduct = ProductModel::count();

        // 🔥 Pesanan aktif (tidak termasuk cancel)
        $totalOrders = OrderModel::where('user_id', $userId)
            ->whereNotIn('status', ['cancel'])
            ->count();

        // 🔄 Sedang diproses
        $orderProcess = OrderModel::where('user_id', $userId)
            ->where('status', 'process')
            ->count();

        // ✅ Selesai
        $orderDone = OrderModel::where('user_id', $userId)
            ->where('status', 'done')
            ->count();

        return view('customer.dashboard', [
            'title' => 'Selamat Datang | Fashion & Styles',
            'navlink' => 'dashboard',
            'AllProduct' => $AllProduct,
            'totalOrders' => $totalOrders,
            'orderProcess' => $orderProcess,
            'orderDone' => $orderDone,
        ]);
    }
}
