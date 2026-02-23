@extends('layouts.customer')

@section('customer')
    <section class="td-page td-page--after-navbar checkout-page">
        <div class="container py-5">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="checkout-title">Checkout</h3>
                <a href="{{ route('customer.cart.index') }}" class="btn btn-back">
                    ← Kembali ke Keranjang
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">

                {{-- LEFT --}}
                <div class="col-lg-8">
                    <div class="glass-card p-4">

                        <h5 class="section-title mb-4">Ringkasan Item</h5>

                        @foreach ($cart->items as $item)
                            @php
                                $variant = $item->variant;
                                $product = $variant?->product;
                                $img = is_array($product?->image) && count($product->image) ? $product->image[0] : null;
                                $price = (int) ($variant?->price ?? 0);
                                $qty = (int) ($item->qty ?? 0);
                                $subtotal = $price * $qty;
                            @endphp

                            <div class="checkout-item">

                                <div class="item-img">
                                    <img src="{{ $img ? asset('storage/' . $img) : asset('images/no-image.png') }}">
                                </div>

                                <div class="item-info">
                                    <div class="item-name">
                                        {{ $product?->name ?? '-' }}
                                    </div>

                                    <div class="item-variant">
                                        {{ $variant?->color ?? '-' }}
                                        {{ $variant?->size ? ' • ' . $variant->size : '' }}
                                    </div>

                                    <div class="item-price">
                                        Rp {{ number_format($price, 0, ',', '.') }} × {{ $qty }}
                                    </div>
                                </div>

                                <div class="item-subtotal">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </div>

                            </div>
                        @endforeach

                        <div class="checkout-total">
                            <span>Total</span>
                            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>

                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="col-lg-4">
                    <form method="POST" action="{{ route('customer.checkout.store') }}">
                        @csrf

                        <div class="glass-card p-4">

                            <h5 class="section-title mb-4">Metode Pembayaran</h5>

                            @foreach ($paymentMethods as $pm)
                                <label class="payment-option">
                                    <input type="radio" name="payment_method_id" value="{{ $pm->id }}" required>

                                    <div class="payment-content">
                                        <div class="payment-title">
                                            {{ $pm->name }}
                                        </div>
                                        <div class="payment-desc">
                                            {{ $pm->bank_name }}
                                            • {{ $pm->account_number }}
                                            • a.n {{ $pm->account_name }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                            <div class="mt-4">
                                <label class="form-label text-secondary small">
                                    Catatan (opsional)
                                </label>
                                <textarea name="note" rows="3" class="form-control checkout-textarea"
                                    placeholder="Contoh: warna sesuai foto ya...">{{ old('note') }}</textarea>
                            </div>

                            <div class="checkout-summary mt-4">
                                <span>Total</span>
                                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                            </div>

                            <button type="submit" class="btn btn-modern w-100 mt-3">
                                <i class="fa-solid fa-lock"></i>
                                Buat Pesanan
                            </button>

                            <div class="small text-muted mt-2">
                                * Stok akan divalidasi ulang saat proses checkout.
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('styles')
    <style>
        .checkout-page {
            background: linear-gradient(135deg, #0b1220, #0f172a);
            min-height: 100vh;
        }

        .checkout-title {
            color: #fff;
            font-weight: 600;
        }

        /* ================= BUTTON BACK ================= */
        .btn-back {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .15);
            color: #fff;
            border-radius: 10px;
            padding: 6px 14px;
            transition: all .2s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, .15);
            border-color: rgba(255, 255, 255, .25);
            color: #fff;
            /* 🔥 penting supaya text tidak hilang */
            transform: translateY(-1px);
        }


        /* ================= BUTTON MODERN ================= */
        .btn-modern {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            color: #fff !important;
            /* 🔥 cegah override bootstrap */
            transition: all .25s ease;
        }

        .btn-modern:hover {
            background: linear-gradient(135deg, #5b5eea, #7c3aed);
            color: #fff !important;
            /* 🔥 text tetap putih */
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, .45);
        }

        .btn-modern:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-modern:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .3);
        }

        .glass-card {
            background: rgba(255, 255, 255, .05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 18px;
        }

        .section-title {
            color: #fff;
            font-weight: 600;
        }

        .checkout-item {
            display: flex;
            gap: 16px;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .item-img img {
            width: 75px;
            height: 75px;
            object-fit: cover;
            border-radius: 12px;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            color: #fff;
            font-weight: 600;
        }

        .item-variant,
        .item-price {
            font-size: 13px;
            color: #9ca3af;
        }

        .item-subtotal {
            color: #fff;
            font-weight: 600;
        }

        .checkout-total,
        .checkout-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            font-size: 18px;
            color: #fff;
        }

        .payment-option {
            display: flex;
            gap: 12px;
            padding: 14px;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 14px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all .2s ease;
        }

        .payment-option:hover {
            background: rgba(255, 255, 255, .05);
        }

        .payment-option input {
            margin-top: 6px;
        }

        .payment-option input:checked+.payment-content {
            color: #6366f1;
        }

        .payment-title {
            font-weight: 600;
            color: #fff;
        }

        .payment-desc {
            font-size: 13px;
            color: #9ca3af;
        }

        .checkout-textarea {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .1);
            color: #fff;
        }

        .btn-modern {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, .4);
        }
    </style>
@endsection
