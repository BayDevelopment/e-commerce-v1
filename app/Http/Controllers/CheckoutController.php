<?php

namespace App\Http\Controllers;

use App\Models\CartModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = CartModel::where('user_id', Auth::id())
            ->with('items.variant.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        return view('pages.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:pay_method_models,id',
        ]);

        $cart = CartModel::where('user_id', Auth::id())
            ->with('items.variant.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        DB::transaction(function () use ($cart, $request) {

            $totalPrice = 0;

            // 🔐 Validasi stok ulang
            foreach ($cart->items as $item) {

                if ($item->quantity > $item->variant->stock) {
                    throw new \Exception('Stok tidak cukup untuk ' . $item->variant->product->name);
                }

                $totalPrice += $item->variant->price * $item->quantity;
            }

            // 🔹 Buat Order
            $order = OrderModel::create([
                'user_id' => Auth::id(),
                'payment_method_id' => $request->payment_method_id,
                'total_price' => $totalPrice,
                'payment_status' => 'unpaid',
                'status' => 'pending',
            ]);

            // 🔹 Buat Order Items
            foreach ($cart->items as $item) {

                OrderItemModel::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->variant->price,
                    'subtotal' => $item->variant->price * $item->quantity,

                    // Snapshot data
                    'product_name' => $item->variant->product->name,
                    'variant_sku' => $item->variant->sku,
                    'variant_color' => $item->variant->color,
                    'variant_size' => $item->variant->size,
                ]);

                // Kurangi stok
                $item->variant->decrement('stock', $item->quantity);
            }

            // 🔹 Hapus cart
            $cart->items()->delete();
        });

        return redirect()->route('customer.orders')
            ->with('success', 'Pesanan berhasil dibuat 🎉');
    }
}
