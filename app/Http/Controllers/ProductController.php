<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductModel::query()
            ->where('is_active', true)
            ->with(['variants'])
            ->withMin('variants', 'price'); // ambil lowest price

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 💰 FILTER BERDASARKAN LOWEST PRICE
        if ($request->filled('min_price')) {
            $query->having('variants_min_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->having('variants_min_price', '<=', $request->max_price);
        }

        // 🔄 SORT
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('variants_min_price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('variants_min_price', 'desc');
                break;

            default:
                $query->latest();
                break;
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
