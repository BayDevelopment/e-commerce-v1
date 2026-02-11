<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class AdminProdukController extends Controller
{
    public function products(Request $request)
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
