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
        // search bebas (optional)
        if ($request->search) {
            $query->where('invoice', 'like', '%' . $request->search . '%')
                ->orWhere('customer_name', 'like', '%' . $request->search . '%');
        }

        // filter status done
        if ($request->status == 'done') {
            $query->where('status', 'done');
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

    public function exportPdf(Request $request)
    {
        $query = OrderModel::with(['paymentMethod', 'branch'])
            ->where('user_id', Auth::id());

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // FILTER DATE FROM
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // FILTER DATE TO
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // SEARCH
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {

                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('branch', function ($branch) use ($request) {
                        $branch->where('name', 'like', "%{$request->search}%");
                    });
            });
        }

        $orders = $query->latest()->get();

        $totalOrders = $orders->count();
        $totalAmount = $orders->sum('total_price');

        $pdf = Pdf::loadView('customer.laporan-pdf', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'totalAmount' => $totalAmount,
            'filters' => $request->all(),
            'exportedAt' => now()
        ])->setPaper('A4', 'portrait');

        return $pdf->download('Laporan-Pesanan-Trendora.pdf');
    }
}
