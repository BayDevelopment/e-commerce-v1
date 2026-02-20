<?php

namespace App\Http\Controllers;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\ProductVariantModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    private function getCart()
    {
        if (Auth::check()) {
            return CartModel::firstOrCreate([
                'user_id' => Auth::id(),
            ]);
        }

        return CartModel::firstOrCreate([
            'session_id' => session()->getId(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW CART
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $cart = $this->getCart()->load([
            'items.variant.product'
        ]);

        $viewPrefix = 'pages';

        if (Auth::check() && Auth::user()->role === 'customer') {
            $viewPrefix = 'customer';
        }

        return view($viewPrefix . '.cart', [
            'title'   => 'Keranjang | Trendora',
            'navlink' => 'cart',
            'cart'    => $cart,
        ]);
    }

    // index customer
    public function indexCustomer()
    {
        $cart = $this->getCart()->load([
            'items.variant.product'
        ]);

        return view('customer.cart', [
            'title'   => 'Keranjang | Trendora',
            'navlink' => 'cart',
            'cart'    => $cart,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ADD TO CART
    |--------------------------------------------------------------------------
    */
    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'qty' => 'required|integer|min:1',
        ]);

        $variant = ProductVariantModel::findOrFail($request->variant_id);

        if ($request->qty > $variant->stock) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        $cart = $this->getCart();

        $item = CartItemModel::where('cart_id', $cart->id)
            ->where('variant_id', $variant->id)
            ->first();

        if ($item) {

            $newQty = $item->qty + $request->qty;

            if ($newQty > $variant->stock) {
                return back()->with('error', 'Jumlah melebihi stok tersedia.');
            }

            $item->update([
                'qty' => $newQty
            ]);
        } else {

            CartItemModel::create([
                'cart_id' => $cart->id,
                'variant_id' => $variant->id,
                'qty' => $request->qty,
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // untuk customer
    public function addCustomer(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'qty'        => 'required|integer|min:1',
        ]);

        $variant = ProductVariantModel::find($validated['variant_id']);

        // Cek stok awal
        if ($validated['qty'] > $variant->stock) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        $cart = $this->getCart();

        $item = CartItemModel::where('cart_id', $cart->id)
            ->where('variant_id', $variant->id)
            ->first();

        if ($item) {

            $newQty = $item->qty + $validated['qty'];

            if ($newQty > $variant->stock) {
                return back()->with('error', 'Jumlah melebihi stok tersedia.');
            }

            $item->update([
                'qty' => $newQty
            ]);
        } else {

            CartItemModel::create([
                'cart_id'   => $cart->id,
                'variant_id' => $variant->id,
                'qty'       => $validated['qty'],
            ]);
        }

        return redirect()
            ->route('customer.cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE QTY
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $cart = $this->getCart();

        $item = CartItemModel::where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        if ($request->qty > $item->variant->stock) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $item->update([
            'qty' => $request->qty
        ]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    // Update Customer
    public function updateCustomer(Request $request, CartItemModel $item)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart();

        // Pastikan item milik cart ini
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }

        if ($validated['qty'] > $item->variant->stock) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $item->update([
            'qty' => $validated['qty'],
        ]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE ITEM
    |--------------------------------------------------------------------------
    */
    public function remove($id)
    {
        $cart = $this->getCart();

        $item = CartItemModel::where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    // Remove Customer
    public function removeCustomer($id)
    {
        $cart = $this->getCart();

        $item = CartItemModel::where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
