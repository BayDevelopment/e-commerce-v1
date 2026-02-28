<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $userId = Auth::id();

        // TOTAL PRODUK
        $AllProduct = ProductModel::count();

        // ORDER STATS (1 query saja)
        $orderStats = OrderModel::selectRaw("
        COUNT(CASE WHEN status != 'cancel' THEN 1 END) as totalOrders,
        COUNT(CASE WHEN status = 'process' THEN 1 END) as orderProcess,
        COUNT(CASE WHEN status = 'done' THEN 1 END) as orderDone,
        SUM(CASE WHEN status = 'done' THEN total_price ELSE 0 END) as totalSpent
    ")
            ->where('user_id', $userId)
            ->first();

        // CATEGORY STATS
        $categoryStats = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'product_variants.id', '=', 'order_items.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')

            ->where('orders.user_id', Auth::id())
            ->where('orders.status', '!=', 'cancel')

            ->select(
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total')
            )

            ->groupBy('categories.name')

            ->get();

        return view('customer.dashboard', [

            'title' => 'Dashboard',
            'navlink' => 'dashboard',

            'AllProduct' => $AllProduct,

            'totalOrders' => $orderStats->totalOrders ?? 0,
            'orderProcess' => $orderStats->orderProcess ?? 0,
            'orderDone' => $orderStats->orderDone ?? 0,
            'totalSpent' => $orderStats->totalSpent ?? 0,

            'categoryStats' => $categoryStats,
        ]);
    }
}
