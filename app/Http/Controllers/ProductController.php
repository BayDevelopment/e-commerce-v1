<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function products()
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
}
