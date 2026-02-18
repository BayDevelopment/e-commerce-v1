<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = ProductModel::where('is_active', true)
            ->latest()       // urut berdasarkan created_at desc
            ->take(3)        // ambil 3 data saja
            ->get();

        $data = [
            'title' => 'Trendora | Fashion & Lifestyle',
            'navlink' => 'beranda',
            'products' => $products,
        ];
        return view('pages.home', $data);
    }

    public function show($categorySlug, $productSlug)
    {
        $product = ProductModel::with(['variants', 'category'])
            ->where('slug', $productSlug)
            ->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            })
            ->firstOrFail();

        return view('pages.detail-product', [
            'title' => 'Detail | Fashion & Lifestyle',
            'navlink' => 'Detail',
            'product' => $product,
        ]);
    }
}
