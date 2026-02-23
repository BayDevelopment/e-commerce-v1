<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ORDER LIST
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $orders = OrderModel::with('paymentMethod')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.order', [
            'title' => 'Pesanan Saya | Trendora',
            'navlink' => 'Pesanan Saya',
            'orders' => $orders,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER DETAIL
    |--------------------------------------------------------------------------
    */

    public function show(OrderModel $order)
    {
        // 🔐 pastikan hanya owner yg bisa lihat
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items', 'paymentMethod']);

        return view('customer.view-order', [
            'title' => 'Detail Pesanan',
            'navlink' => 'Detail Pesanan',
            'order' => $order,
        ]);
    }



    public function uploadProof(Request $request, OrderModel $order)
    {
        // 🔐 Pastikan hanya owner yang bisa upload
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // ❌ Tidak boleh upload kalau sudah paid / cancel
        if ($order->payment_status !== 'pending') {
            return back()->withErrors([
                'payment' => 'Pesanan ini tidak bisa upload bukti lagi.'
            ]);
        }

        $request->validate([
            'payment_proof' => 'required|image|max:1024',
        ]);

        // 🔥 Hapus bukti lama kalau ada
        if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'payment_status' => 'pending', // atau bisa pakai 'waiting_confirmation'
        ]);

        return back()->with('success', 'Bukti transfer berhasil diupload.');
    }
}
