<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderModel::where('user_id', Auth::id());

        // Optional filter tanggal
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->latest()->get();

        // Statistik
        $totalOrders  = $orders->count();
        $totalDone    = $orders->where('status', 'done')->count();
        $totalProcess = $orders->where('status', 'process')->count();
        $totalPending = $orders->where('status', 'pending')->count();
        $totalSpent   = $orders->where('status', 'done')->sum('total_price');

        return view('customer.laporan', compact(
            'orders',
            'totalOrders',
            'totalDone',
            'totalProcess',
            'totalPending',
            'totalSpent'
        ), [
            'title' => 'Laporan Belanja | Trendora',
            'navlink' => 'laporan',
        ]);
    }

    public function export()
    {
        $orders = OrderModel::where('user_id', Auth::id())
            ->latest()
            ->get();

        $pdf = Pdf::loadView('customer.laporan-pdf', compact('orders'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-belanja-saya.pdf');
    }
}
