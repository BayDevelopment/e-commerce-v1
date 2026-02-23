@extends('layouts.customer')

@section('customer')
    <div class="container-fluid pt-5 pb-2">

        <!-- HEADER -->
        <div class="mb-1 ">
            <h3 class="fw-bold text-white mb-1">
                Halo, {{ Auth::user()->name }} 👋
            </h3>
            <p class="text-muted mb-0">
                Selamat datang di dashboard belanja kamu
            </p>
        </div>

        <!-- STAT CARDS -->
        <div class="row g-4 mb-4">

            <div class="col-lg-3 col-md-6">
                <a href="{{ route('customer.product') }}" class="td-card-link text-decoration-none">
                    <div class="td-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-secondary small mb-1">Total Produk</p>
                                <h4 class="fw-bold text-white mb-0">
                                    {{ $AllProduct ?? 0 }}
                                </h4>
                            </div>
                            <div class="td-dashboard-icon bg-primary">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- PESANAN SAYA -->
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('customer.orders') }}" class="td-card-link text-decoration-none">
                    <div class="td-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-secondary small mb-1">Pesanan Saya</p>
                                <h4 class="fw-bold text-white mb-0">
                                    {{ $totalOrders ?? 0 }}
                                </h4>
                            </div>
                            <div class="td-dashboard-icon bg-info">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- DIPROSES -->
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('customer.orders', ['status' => 'process']) }}" class="td-card-link text-decoration-none">
                    <div class="td-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-secondary small mb-1">Sedang Diproses</p>
                                <h4 class="fw-bold text-white mb-0">
                                    {{ $orderProcess ?? 0 }}
                                </h4>
                            </div>
                            <div class="td-dashboard-icon bg-warning">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- SELESAI -->
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('customer.orders', ['status' => 'done']) }}" class="td-card-link text-decoration-none">
                    <div class="td-card p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-secondary small mb-1">Pesanan Selesai</p>
                                <h4 class="fw-bold text-white mb-0">
                                    {{ $orderDone ?? 0 }}
                                </h4>
                            </div>
                            <div class="td-dashboard-icon bg-success">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <!-- QUICK ACTION -->
        <div class="row g-4">

            <div class="col-lg-8">
                <div class="td-card p-4 h-100">
                    <h5 class="fw-bold text-white mb-4">Status Akun</h5>

                    <div class="row g-4">

                        <!-- EMAIL VERIFIED -->
                        <div class="col-md-6">
                            <div class="td-status-card">
                                <div class="td-status-icon {{ Auth::user()->email_verified_at ? 'success' : 'danger' }}">
                                    <i
                                        class="fa-solid {{ Auth::user()->email_verified_at ? 'fa-envelope-circle-check' : 'fa-envelope-open-text' }}"></i>
                                </div>

                                <div>
                                    <div class="fw-semibold text-white mb-1">
                                        Email
                                    </div>
                                    <div
                                        class="small {{ Auth::user()->email_verified_at ? 'text-success' : 'text-danger' }}">
                                        {{ Auth::user()->email_verified_at ? 'Sudah diverifikasi' : 'Belum diverifikasi' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DATA DIRI -->
                        <div class="col-md-6">
                            <div class="td-status-card">
                                @php
                                    $profileComplete = Auth::user()->name && Auth::user()->email;
                                @endphp

                                <div class="td-status-icon {{ $profileComplete ? 'info' : 'warning' }}">
                                    <i class="fa-solid {{ $profileComplete ? 'fa-id-card' : 'fa-user-pen' }}"></i>
                                </div>

                                <div>
                                    <div class="fw-semibold text-white mb-1">
                                        Data Diri
                                    </div>
                                    <div class="small {{ $profileComplete ? 'text-info' : 'text-warning' }}">
                                        {{ $profileComplete ? 'Sudah lengkap' : 'Belum lengkap' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- OPTIONAL CTA -->
                    @if (!Auth::user()->email_verified_at || !$profileComplete)
                        <div class="mt-4">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-td w-100 td-btn-action">
                                <i class="fa-solid fa-user-gear"></i>
                                Lengkapi Akun
                            </a>
                        </div>
                    @endif

                </div>
            </div>


            <!-- CTA -->
            <div class="col-lg-4">
                <div class="td-cta p-4 h-100">
                    <div class="content">
                        <h5 class="fw-bold text-white mb-2">
                            Mulai Belanja 🚀
                        </h5>
                        <p class="text-secondary mb-3">
                            Jelajahi produk terbaik dan promo hari ini
                        </p>
                        <a href="{{ route('products.index') }}" class="btn btn-td w-100 td-btn-action">
                            <i class="fa-solid fa-bag-shopping"></i>
                            Belanja Sekarang
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
@section('styles')
    <style>
        /* DASHBOARD ICON */
        .td-dashboard-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            background: linear-gradient(135deg,
                    rgba(111, 66, 193, 0.55),
                    rgba(59, 130, 246, 0.45));
        }

        /* ACTIVITY DOT */
        .td-activity-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-top: 6px;
        }

        /* STATUS CARD */
        .td-status-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.25s ease;
        }

        .td-status-card:hover {
            border-color: rgba(111, 66, 193, 0.45);
            transform: translateY(-2px);
        }

        /* ICON */
        .td-status-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
        }

        /* ICON VARIANT */
        .td-status-icon.success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .td-status-icon.danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .td-status-icon.info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .td-status-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
    </style>
@endsection
