<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function products()
    {
        $products = [
            [
                'name' => 'Kaos Oversize Pria',
                'price' => 129000,
                'old_price' => 179000,
                'rating' => 4.7,
                'sold' => 1200,
                'badge' => 'Best Seller',
                'category' => 'Fashion Pria',
                'img' => asset('images/baju.png'), // ganti ke gambar kamu
            ],
            [
                'name' => 'Dress Wanita Casual',
                'price' => 219000,
                'old_price' => null,
                'rating' => 4.6,
                'sold' => 860,
                'badge' => 'Trending',
                'category' => 'Fashion Wanita',
                'img' => asset('images/tas.png'),
            ],
            [
                'name' => 'Jam Tangan Minimalis',
                'price' => 299000,
                'old_price' => 349000,
                'rating' => 4.8,
                'sold' => 540,
                'badge' => 'New',
                'category' => 'Aksesoris',
                'img' => asset('images/jam.png'),
            ],
        ];

        // bikin slug
        $products = array_map(function ($p) {
            $p['slug'] = Str::slug($p['name']);
            return $p;
        }, $products);

        return view('pages.products', [
            'title' => 'Semua Produk | Trendora',
            'navlink' => 'produk',
            'products' => $products,
            'categories' => ['Semua', 'Fashion Pria', 'Fashion Wanita', 'Aksesoris'],
        ]);
    }
}
