<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Selamat Datang | Fashion & Styles',
            'navlink' => 'dashboard'
        ];
        return view('customer.dashboard', $data);
    }
}
