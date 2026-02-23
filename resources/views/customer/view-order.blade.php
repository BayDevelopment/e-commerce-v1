@extends('layouts.customer')

@section('customer')
    <section class="order-detail-page">
        <div class="container py-5">

            {{-- BACK BUTTON --}}
            <div class="mb-4">
                <a href="{{ route('customer.orders') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Pesanan</span>
                </a>
            </div>

            {{-- ORDER HEADER --}}
            <div class="glass-card mb-4">

                @php
                    $statusColor = match ($order->status) {
                        'pending' => 'badge-pending',
                        'process' => 'badge-process',
                        'done' => 'badge-done',
                        'cancel' => 'badge-cancel',
                        default => 'badge-default',
                    };
                @endphp

                <div class="order-header">
                    <div>
                        <h4 class="order-title">
                            Order #{{ $order->id }}
                        </h4>
                        <div class="order-date">
                            {{ $order->created_at->format('d M Y H:i') }}
                        </div>
                    </div>

                    <span class="order-badge {{ $statusColor }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="order-meta mt-3">
                    <span>Metode Pembayaran:</span>
                    <strong>{{ $order->paymentMethod->name ?? '-' }}</strong>
                </div>

            </div>


            {{-- DATA REKENING --}}
            @if ($order->payment_status === 'pending')
                <div class="glass-card mb-4">

                    <h6 class="section-title">
                        <i class="fa-solid fa-building-columns"></i>
                        Informasi Rekening
                    </h6>

                    <div class="rekening-grid">
                        <div class="rekening-item">
                            <div class="label">Bank</div>
                            <div class="value">{{ $order->bank_name }}</div>
                        </div>

                        <div class="rekening-item">
                            <div class="label">Nomor Rekening</div>
                            <div class="value" id="rekeningNumber">
                                {{ $order->bank_account_number }}
                            </div>
                        </div>

                        <div class="rekening-item">
                            <div class="label">Atas Nama</div>
                            <div class="value">{{ $order->bank_account_name }}</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn-copy"
                            onclick="copyRekening('{{ $order->bank_account_number }}', this)">
                            <i class="fa-solid fa-copy"></i>
                            Copy Nomor Rekening
                        </button>
                    </div>

                    <div class="warning-note mt-3">
                        * Pastikan nominal transfer sesuai total pesanan.
                    </div>

                    {{-- UPLOAD --}}
                    <form method="POST" action="{{ route('customer.orders.upload', $order->id) }}"
                        enctype="multipart/form-data" class="upload-box mt-4">
                        @csrf

                        <div class="upload-title">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Upload Bukti Transfer</span>
                        </div>

                        <div class="file-upload-wrapper">
                            <input type="file" name="payment_proof" id="paymentProof-{{ $order->id }}"
                                class="file-input" accept="image/*" required>

                            <label for="paymentProof-{{ $order->id }}" class="file-label">
                                <i class="fa-solid fa-image"></i>
                                <span class="file-name-text">Pilih File Gambar</span>
                            </label>
                        </div>
                        <button type="submit" class="btn-primary mt-3 w-100">
                            <i class="fa-solid fa-paper-plane"></i>
                            Kirim Bukti
                        </button>
                    </form>

                </div>
            @endif


            {{-- BUKTI --}}
            @if ($order->payment_proof)
                <div class="glass-card mb-4 text-center">
                    <h6 class="section-title">
                        <i class="fa-solid fa-image"></i>
                        Bukti Transfer
                    </h6>

                    <img src="{{ asset('storage/' . $order->payment_proof) }}" class="payment-proof-img">
                </div>
            @endif


            {{-- ITEMS --}}
            <div class="glass-card">
                <h6 class="section-title">
                    <i class="fa-solid fa-box"></i>
                    Item Pesanan
                </h6>

                @foreach ($order->items as $item)
                    <div class="item-row">
                        <div class="item-info">
                            <div class="item-name">
                                {{ $item->product_name }}
                            </div>

                            <div class="item-variant">
                                {{ $item->variant_color }}
                                {{ $item->variant_size ? '- ' . $item->variant_size : '' }}
                            </div>

                            <div class="item-price">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                × {{ $item->quantity }}
                            </div>
                        </div>

                        <div class="item-subtotal">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach

                <hr>

                <div class="total-row">
                    <span>Total</span>
                    <strong>
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </strong>
                </div>

            </div>

        </div>
    </section>
@endsection
@section('styles')
    <style>
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .order-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .order-date {
            font-size: 13px;
            color: #9ca3af;
        }

        .order-meta {
            font-size: 13px;
            color: #9ca3af;
        }

        .order-meta strong {
            color: #fff;
        }

        .rekening-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .rekening-item {
            background: rgba(255, 255, 255, .05);
            padding: 12px;
            border-radius: 12px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
        }

        .item-info {
            max-width: 70%;
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
            font-weight: 600;
            color: #fff;
        }

        @media(max-width:768px) {
            .item-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .item-info {
                max-width: 100%;
            }
        }

        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(10px);
            transition: .3s;
        }

        .glass-card:hover {
            border-color: rgba(255, 255, 255, .15);
            box-shadow: 0 15px 40px rgba(0, 0, 0, .4);
        }

        /* Badge */
        .order-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pending {
            background: rgba(251, 191, 36, .2);
            color: #fbbf24;
        }

        .badge-process {
            background: rgba(59, 130, 246, .2);
            color: #3b82f6;
        }

        .badge-done {
            background: rgba(34, 197, 94, .2);
            color: #22c55e;
        }

        .badge-cancel {
            background: rgba(239, 68, 68, .2);
            color: #ef4444;
        }

        /* Section */
        .section-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .section-title i {
            margin-right: 8px;
        }

        /* Rekening */
        .rekening-box {
            background: rgba(255, 255, 255, .05);
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 15px;
        }

        .label {
            font-size: 12px;
            color: #9ca3af;
        }

        .value {
            color: #fff;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .btn-copy {
            background: rgba(99, 102, 241, .2);
            border: none;
            color: #6366f1;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            transition: .2s;
        }

        .btn-copy:hover {
            background: #6366f1;
            color: #fff;
        }

        /* Upload */
        .upload-box input {
            display: block;
            margin-bottom: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            padding: 10px;
            border-radius: 12px;
            color: #fff;
            width: 100%;
        }

        /* Item */
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
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

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            color: #fff;
            font-size: 16px;
        }

        .payment-proof-img {
            max-width: 300px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .5);
        }

        /* Back Button */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, .08);
            padding: 8px 14px;
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            transition: .2s;
        }

        .btn-back:hover {
            background: #6366f1;
            color: #fff;
        }

        .warning-note {
            font-size: 13px;
            color: #fbbf24;
            margin-bottom: 10px;
        }

        @media(max-width:768px) {
            .item-row {
                flex-direction: column;
                gap: 5px;
            }
        }

        /* Hide default file input */
        /* HILANGKAN DEFAULT INPUT FILE */
        .file-input {
            position: absolute;
            left: -9999px;
            visibility: hidden;
        }

        /* STYLE LABEL CUSTOM */
        .file-label {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(99, 102, 241, .15);
            color: #6366f1;
            padding: 12px 16px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 500;
            transition: .2s ease;
        }

        .file-label:hover {
            background: #6366f1;
            color: #fff;
        }

        /* Upload button improve */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            padding: 12px;
            border-radius: 14px;
            color: #fff;
            font-weight: 600;
            transition: .3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, .4);
        }
    </style>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // COPY REKENING WITH FEEDBACK
            window.copyRekening = function(rekening, btn) {
                navigator.clipboard.writeText(rekening).then(() => {

                    const original = btn.innerHTML;

                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Berhasil Disalin';
                    btn.style.background = '#22c55e';
                    btn.style.color = '#fff';

                    setTimeout(() => {
                        btn.innerHTML = original;
                        btn.style.background = 'rgba(99,102,241,.2)';
                        btn.style.color = '#6366f1';
                    }, 2000);
                });
            };

            // FILE NAME UPDATE
            document.querySelectorAll('.file-input').forEach(input => {
                input.addEventListener('change', function() {
                    const label = this.nextElementSibling.querySelector('.file-name-text');

                    if (this.files.length > 0) {
                        label.textContent = this.files[0].name;
                    }
                });
            });

        });
    </script>
@endsection
