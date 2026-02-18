@extends('layouts.customer')

@section('customer')
    <section class="td-page td-page--after-navbar" style="background:#0b1220;">
        <div class="container py-5">

            @if ($cart->items->count())
                @php $grandTotal = 0; @endphp

                <div class="row g-4">
                    <div class="col-lg-8">

                        @foreach ($cart->items as $item)
                            @php
                                $subtotal = $item->variant->price * $item->qty;
                                $grandTotal += $subtotal;
                            @endphp

                            <div class="td-cart-card mb-3 p-4 rounded-4 shadow-sm">

                                <div class="d-flex flex-column flex-lg-row align-items-start gap-4">

                                    {{-- LEFT SECTION (IMAGE + INFO + QTY) --}}
                                    <div class="d-flex gap-3 flex-grow-1">

                                        {{-- IMAGE --}}
                                        <div class="td-cart-img">
                                            <img src="{{ $item->variant->product->image && count($item->variant->product->image)
                                                ? asset('storage/' . $item->variant->product->image[0])
                                                : asset('images/no-image.png') }}"
                                                alt="{{ $item->variant->product->name }}">
                                        </div>

                                        {{-- INFO --}}
                                        <div class="flex-grow-1">

                                            <h6 class="fw-semibold text-white mb-1">
                                                {{ $item->variant->product->name }}
                                            </h6>

                                            <div class="text-secondary small mb-2">
                                                {{ $item->variant->color }}
                                                {{ $item->variant->size ? '• ' . $item->variant->size : '' }}
                                            </div>

                                            {{-- PRICE --}}
                                            <div class="text-white fw-medium mb-2">
                                                Rp {{ number_format($item->variant->price, 0, ',', '.') }}
                                            </div>

                                            {{-- QTY --}}
                                            <div class="td-qty-control">

                                                <button type="button" onclick="qtyMinus(this)">−</button>

                                                <input type="number" value="{{ $item->qty }}" min="1"
                                                    max="{{ $item->variant->stock }}"
                                                    data-price="{{ $item->variant->price }}" class="cart-qty">

                                                <button type="button" onclick="qtyPlus(this)">+</button>

                                            </div>

                                            <div class="td-stock small text-secondary mt-1">
                                                Stok: {{ $item->variant->stock }}
                                            </div>

                                        </div>

                                    </div>

                                    {{-- RIGHT SECTION (SUBTOTAL + DELETE) --}}
                                    <div class="d-flex flex-column align-items-end justify-content-between gap-3">

                                        <div class="fw-bold fs-5 text-white cart-subtotal"
                                            data-subtotal="{{ $subtotal }}">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </div>

                                        <button type="button" class="td-delete btn-remove"
                                            data-url="{{ route('customer.cart.remove', $item->id) }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>

                    {{-- Summary --}}
                    <div class="col-lg-4">
                        <div class="p-4 rounded" style="background:rgba(255,255,255,.05);">

                            <h5 class="text-white mb-3">Ringkasan Belanja</h5>

                            <div class="d-flex justify-content-between mb-2 text-white">
                                <span>Total</span>
                                <strong id="grandTotal">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </strong>
                            </div>

                            @auth
                                <button onclick="submitBuyNow()" class="btn btn-td w-100">
                                    <i class="fa-solid fa-bolt"></i>
                                    Beli Sekarang
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-td w-100">
                                    <i class="fa-solid fa-bolt"></i>
                                    Login untuk Beli
                                </a>
                            @endauth

                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa-solid fa-cart-shopping fa-3x mb-3" style="color:rgba(255,255,255,.3);"></i>
                    <h5 class="text-white">Keranjang Kosong</h5>
                    <p style="color:#aaa;">Yuk mulai belanja dan temukan produk terbaik!</p>
                    <a href="{{ url('/produk') }}" class="btn btn-td mt-3">Lihat Produk</a>
                </div>
            @endif

        </div>
    </section>
@endsection
@section('styles')
    <style>
        .td-cart-card {
            background: rgba(255, 255, 255, .05);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, .08);
            transition: all .2s ease;
        }

        .td-cart-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, .15);
        }

        .td-cart-img img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 12px;
        }

        .td-qty-control {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, .08);
            border-radius: 50px;
            padding: 4px;
        }

        .td-qty-control button {
            background: none;
            border: none;
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            transition: .2s;
        }

        .td-qty-control button:hover {
            background: rgba(255, 255, 255, .15);
        }

        .td-qty-control input {
            width: 50px;
            text-align: center;
            background: transparent;
            border: none;
            color: #fff;
            outline: none;
        }

        .td-delete {
            background: rgba(239, 68, 68, .15);
            border: none;
            color: #ef4444;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            transition: .2s;
        }

        .td-delete:hover {
            background: #ef4444;
            color: #fff;
        }
    </style>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const qtyInputs = document.querySelectorAll('.cart-qty');
            const grandTotalEl = document.getElementById('grandTotal');

            function formatRupiah(number) {
                return 'Rp ' + number.toLocaleString('id-ID');
            }

            function calculateGrandTotal() {
                let total = 0;
                document.querySelectorAll('.cart-subtotal').forEach(sub => {
                    total += parseInt(sub.dataset.subtotal);
                });
                grandTotalEl.innerText = formatRupiah(total);
            }

            qtyInputs.forEach(input => {

                input.addEventListener('input', function() {

                    let qty = parseInt(this.value);
                    const max = parseInt(this.max);
                    const price = parseInt(this.dataset.price);

                    if (qty > max) {
                        qty = max;
                        this.value = max;

                        Swal.fire({
                            icon: 'warning',
                            title: 'Stok tidak mencukupi',
                            text: 'Jumlah melebihi stok tersedia.',
                            confirmButtonColor: '#6366f1'
                        });
                    }

                    if (qty < 1) {
                        qty = 1;
                        this.value = 1;
                    }

                    const subtotal = qty * price;
                    const subtotalEl = this.closest('.row').querySelector('.cart-subtotal');

                    subtotalEl.dataset.subtotal = subtotal;
                    subtotalEl.innerText = formatRupiah(subtotal);

                    calculateGrandTotal();
                });
            });

            // DELETE CONFIRMATION
            document.querySelectorAll('.btn-remove').forEach(button => {

                button.addEventListener('click', function() {

                    const url = this.dataset.url;

                    Swal.fire({
                        title: 'Hapus item?',
                        text: "Item akan dihapus dari keranjang.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {

                        if (result.isConfirmed) {

                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = url;

                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = '{{ csrf_token() }}';

                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'DELETE';

                            form.appendChild(csrf);
                            form.appendChild(method);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });


        });

        function qtyMinus(btn) {
            const input = btn.parentElement.querySelector('.cart-qty');
            input.stepDown();
            input.dispatchEvent(new Event('input'));
        }

        function qtyPlus(btn) {
            const input = btn.parentElement.querySelector('.cart-qty');
            input.stepUp();
            input.dispatchEvent(new Event('input'));
        }
    </script>
@endsection
