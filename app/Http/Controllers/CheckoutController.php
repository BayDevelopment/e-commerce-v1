<?php

namespace App\Http\Controllers;

use App\Models\CartModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PayMethodModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $userId = Auth::id();

        $cart = CartModel::with(['items.variant.product'])
            ->where('user_id', $userId)
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $paymentMethods = PayMethodModel::where('is_active', true)
            ->latest()
            ->get();

        $total = $cart->items->sum(function ($item) {
            return ($item->variant?->price ?? 0) * ($item->qty ?? 0);
        });

        return view('customer.checkout', [
            'title' => 'Checkout | Trendora',
            'navlink' => 'checkout',
            'cart' => $cart,
            'paymentMethods' => $paymentMethods,
            'total' => $total,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $cart = CartModel::with(['items.variant.product'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        try {

            DB::transaction(function () use ($cart, $request) {

                $totalPrice = 0;

                // 🔐 VALIDASI STOK ULANG
                foreach ($cart->items as $item) {

                    if ($item->variant->stock < $item->qty) {
                        throw new \Exception(
                            'Stok tidak cukup untuk ' . $item->variant->product->name
                        );
                    }

                    $totalPrice += $item->variant->price * $item->qty;
                }

                // 🔥 AMBIL PAYMENT METHOD
                $payment = PayMethodModel::findOrFail($request->payment_method_id);

                // 🔹 BUAT ORDER + SNAPSHOT BANK
                $order = OrderModel::create([
                    'user_id' => Auth::id(),
                    'payment_method_id' => $payment->id,
                    'total_price' => $totalPrice,

                    // 🔥 SNAPSHOT DATA REKENING
                    'bank_name' => $payment->bank_name,
                    'bank_account_number' => $payment->account_number,
                    'bank_account_name' => $payment->account_name,

                    'payment_status' => 'pending',
                    'status' => 'pending',
                ]);

                // 🔹 BUAT ORDER ITEMS
                foreach ($cart->items as $item) {

                    OrderItemModel::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $item->variant_id,
                        'quantity' => $item->qty,
                        'price' => $item->variant->price,
                        'subtotal' => $item->variant->price * $item->qty,

                        // Snapshot product
                        'product_name' => $item->variant->product->name,
                        'variant_sku' => $item->variant->sku,
                        'variant_color' => $item->variant->color,
                        'variant_size' => $item->variant->size,
                    ]);

                    // 🔥 KURANGI STOK
                    $item->variant->decrement('stock', $item->qty);
                }

                // 🔥 HAPUS CART
                $cart->items()->delete();
            });

            return redirect()->route('customer.orders')
                ->with('success', 'Pesanan berhasil dibuat 🎉');
        } catch (\Throwable $e) {

            return back()->withErrors([
                'checkout' => $e->getMessage(),
            ]);
        }
    }
}
