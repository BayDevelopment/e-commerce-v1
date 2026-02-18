<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductModel::where('is_active', true)
            ->with(['variants']);

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 💰 FILTER HARGA (pakai lowest variant price)
        if ($request->filled('min_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // 🔄 SORT
        if ($request->sort == 'price_asc') {
            $query->withMin('variants', 'price')
                ->orderBy('variants_min_price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->withMin('variants', 'price')
                ->orderBy('variants_min_price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('pages.products', [
            'title'    => 'Produk Kami | Trendora',
            'navlink'  => 'produk',
            'products' => $products,
        ]);
    }

    public function productsCustomer(Request $request)
    {
        // 🔎 BASE QUERY
        $baseQuery = ProductModel::query()
            ->where('is_active', true)
            ->with(['variants'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });

        // 🆕 3 DATA TERBARU (hanya jika tidak search)
        $latestProducts = null;

        if (!$request->search) {
            $latestProducts = (clone $baseQuery)
                ->latest()
                ->take(3)
                ->get();
        }

        // 📦 PAGINATED DATA
        $products = (clone $baseQuery)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('customer.product', [
            'title'   => 'Semua Produk | Trendora',
            'navlink' => 'produk',
            'latestProducts' => $latestProducts,
            'products' => $products,
        ]);
    }
}
