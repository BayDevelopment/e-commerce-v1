<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $AllProduct = ProductModel::count();

        $data = [
            'title' => 'Selamat Datang | Fashion & Styles',
            'navlink' => 'dashboard',
            'AllProduct' => $AllProduct

        ];
        return view('customer.dashboard', $data);
    }
}
