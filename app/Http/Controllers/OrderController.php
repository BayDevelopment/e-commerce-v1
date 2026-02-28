<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ORDER LIST (Pesanan Saya)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $allowedStatuses = ['pending', 'process', 'done', 'cancel'];

        $query = OrderModel::with(['paymentMethod', 'branch']) // tambah branch kalau ada relasi
            ->where('user_id', Auth::id());

        // Filter status hanya jika valid
        if ($request->filled('status')) {
            $query->when(
                in_array($request->status, $allowedStatuses),
                fn($q) => $q->where('status', $request->status)
            )->when(
                !in_array($request->status, $allowedStatuses),
                fn($q) => $q->whereRaw('1 = 0') // kosongkan kalau status invalid
            );
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('customer.order', compact('orders'))
            ->with([
                'title'   => 'Pesanan Saya | Trendora',
                'navlink' => 'Pesanan Saya',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER DETAIL
    |--------------------------------------------------------------------------
    */
    public function show(OrderModel $order)
    {
        $this->authorizeOrder($order);

        $order->load([
            'items.variant.product',     // load detail item + variant + produk
            'paymentMethod',
            'branch',                    // kalau sudah ada relasi branch
        ]);

        return view('customer.view-order', [
            'title'   => 'Detail Pesanan #' . $order->id,
            'navlink' => 'Detail Pesanan',
            'order'   => $order,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD BUKTI TRANSFER
    |--------------------------------------------------------------------------
    */
    public function uploadProof(Request $request, OrderModel $order)
    {
        $this->authorizeOrder($order);

        // Hanya boleh upload kalau pending
        if ($order->payment_status !== 'pending') {
            return back()->withErrors(['payment' => 'Bukti sudah diupload atau pesanan tidak bisa diubah lagi.']);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // naikkan ke 2MB, lebih nyaman
        ]);

        try {
            // Hapus bukti lama kalau ada
            if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
                Storage::disk('public')->delete($order->payment_proof);
            }

            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            $order->update([
                'payment_proof'   => $path,
                'payment_status'  => 'pending',
            ]);

            return back()->with('success', 'Bukti transfer berhasil diupload. Admin akan segera memverifikasi.');
        } catch (\Exception $e) {
            return back()->withErrors(['payment' => 'Gagal upload bukti: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper: Authorize order milik user
     */
    private function authorizeOrder(OrderModel $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Ini bukan pesanan Anda.');
        }
    }
}
