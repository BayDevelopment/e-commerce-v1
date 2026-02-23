@extends('layouts.customer')

@section('customer')
    <section class="td-page td-page--after-navbar" style="background:#0b1220;">
        <div class="container">

            <h3 class="text-white mb-4">Pesanan Saya</h3>

            @forelse ($orders as $order)
                @php
                    $statusColor = match ($order->status) {
                        'pending' => 'badge-pending',
                        'process' => 'badge-process',
                        'done' => 'badge-done',
                        'cancel' => 'badge-cancel',
                        default => 'badge-default',
                    };
                @endphp

                <div class="order-card">

                    <div class="order-left">
                        <div class="order-id">
                            Order #{{ $order->id }}
                        </div>

                        <div class="order-date">
                            {{ $order->created_at->format('d M Y H:i') }}
                        </div>

                        <span class="order-badge {{ $statusColor }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="order-right">
                        <div class="order-price">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </div>

                        <a href="{{ route('customer.orders.show', $order->id) }}"
                            class="btn-modern d-inline-flex align-items-center gap-2 text-decoration-none">
                            <i class="fa-solid fa-eye"></i>
                            <span>Lihat Detail</span>
                        </a>
                    </div>

                </div>

            @empty
                <div class="empty-state">
                    <i class="fa-solid fa-box-open"></i>
                    <h5>Belum ada pesanan</h5>
                    <p>Yuk mulai belanja sekarang 🔥</p>
                </div>
            @endforelse

            <div class="mt-3">
                {{ $orders->links() }}
            </div>

        </div>
    </section>
@endsection
@section('styles')
    <style>
        .order-page {
            background: linear-gradient(135deg, #0b1220, #0f172a);
            min-height: 100vh;
        }

        .page-title {
            color: #fff;
            font-weight: 600;
        }

        .order-card {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all .25s ease;
            flex-wrap: wrap;
        }

        .order-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, .2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .4);
        }

        .order-left {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .order-id {
            color: #fff;
            font-weight: 600;
        }

        .order-date {
            font-size: 13px;
            color: #9ca3af;
        }

        .order-price {
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .order-right {
            text-align: right;
        }

        .order-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 6px;
        }

        /* Status Colors */
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

        .badge-default {
            background: rgba(156, 163, 175, .2);
            color: #9ca3af;
        }

        /* Modern Button */
        .btn-modern {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 12px;
            padding: 8px 16px;
            color: #fff !important;
            font-weight: 600;
            transition: all .2s ease;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, .4);
            color: #fff !important;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, .3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .order-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-right {
                width: 100%;
                text-align: left;
                margin-top: 12px;
            }
        }

        .pagination-wrapper .pagination {
            justify-content: center;
        }
    </style>
@endsection
