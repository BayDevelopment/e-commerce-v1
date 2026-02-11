<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = ProductModel::where('is_active', true)
            ->latest()       // urut berdasarkan created_at desc
            ->take(3)        // ambil 3 data saja
            ->get();

        return view('pages.products', [
            'title'    => 'Produk Terbaru | Trendora',
            'navlink'  => 'produk',
            'products' => $products,
        ]);
    }

    public function productsCustomer(Request $request)
    {
        $products = ProductModel::query()
            ->where('is_active', true)

            // SEARCH
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })

            // PRICE FILTER
            ->when($request->min_price, function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            })
            ->when($request->max_price, function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            })

            // SORTING
            ->when($request->sort, function ($q) use ($request) {
                match ($request->sort) {
                    'price_asc'  => $q->orderBy('price', 'asc'),
                    'price_desc' => $q->orderBy('price', 'desc'),
                    default      => $q->latest(),
                };
            }, fn($q) => $q->latest())

            ->paginate(12)
            ->withQueryString(); // biar filter kebawa pagination

        return view('customer.product', [
            'title'    => 'Semua Produk | Trendora',
            'navlink'  => 'produk',
            'products' => $products,
        ]);
    }
}
