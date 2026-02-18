@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <h3 class="text-white mb-4">Checkout 🧾</h3>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="row g-4">

                <!-- LEFT -->
                <div class="col-lg-8">

                    <div class="td-card p-4">

                        <h5 class="mb-3">Alamat Pengiriman</h5>

                        <textarea name="shipping_address" class="form-control" rows="4" required></textarea>

                        <h5 class="mt-4 mb-3">Metode Pembayaran</h5>

                        <select name="payment_method" class="form-select" required>
                            <option value="">Pilih metode</option>
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="cod">COD</option>
                        </select>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-lg-4">

                    <div class="td-card p-4">

                        <h5 class="mb-3">Ringkasan</h5>

                        @php $grandTotal = 0; @endphp

                        @foreach ($cart->items as $item)
                            @php
                                $subtotal = $item->variant->price * $item->qty;
                                $grandTotal += $subtotal;
                            @endphp

                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item->variant->product->name }} x{{ $item->qty }}</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <button class="btn btn-td w-100 mt-4">
                            Bayar Sekarang
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>
@endsection
