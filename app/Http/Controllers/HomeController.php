<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Trendora | Fashion & Lifestyle',
            'navlink' => 'beranda'
        ];
        return view('pages.home', $data);
    }

    public function details($slug)
    {
        // dummy produk
        $product = [
            'name' => 'Kaos Oversize Pria',
            'slug' => $slug,
            'price' => 129000,
            'old_price' => 179000,
            'rating' => 4.7,
            'sold' => 1200,
            'stock' => 24,
            'badge' => 'Best Seller',
            'description' => 'Kaos oversize berbahan cotton combed 24s, adem dan nyaman untuk dipakai harian.',
            'images' => [
                asset('images/baju.png'),
            ],
            'variants' => [
                'Ukuran' => ['S', 'M', 'L', 'XL'],
                'Warna' => ['Putih', 'Hitam', 'Navy'],
            ],
            'specs' => [
                'Material' => 'Cotton Combed 24s',
                'Model' => 'Oversize',
                'Berat' => '250 gram',
                'Perawatan' => 'Cuci terbalik, jangan pemutih',
            ],
        ];

        $data = [
            'title'   => 'Detail ' . $product['name'] . ' | Fashion & Lifestyle',
            'navlink' => 'detail',
            'product' => $product,
        ];

        return view('pages.detail-product', $data);
    }
}
