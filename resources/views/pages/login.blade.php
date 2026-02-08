@extends('layouts.auth')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                <div class="td-card p-4 p-md-5">
                    <!-- HEADER -->
                    <div class="text-center mb-4">
                        <div class="td-icon mx-auto mb-3">
                            <i class="fa-solid fa-user-lock"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-1">Selamat Datang Kembali</h4>
                        <p class="text-muted small mb-0">
                            Login untuk melanjutkan belanja
                        </p>
                    </div>

                    <!-- FORM -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text td-input-icon">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control" placeholder="email@contoh.com"
                                    required>
                            </div>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text td-input-icon">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- REMEMBER -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <a href="#" class="small text-decoration-none" style="color:#d9c7ff;">
                                Lupa password?
                            </a>
                        </div>

                        <!-- BUTTON -->
                        <button type="submit" class="btn btn-td w-100 td-btn-action">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Login
                        </button>
                    </form>

                    <!-- FOOTER -->
                    <div class="text-center mt-4">
                        <span class="small" style="color: var(--td-primary);">
                            Belum punya akun?
                        </span>
                        <a href="{{ route('register') }}" class="ms-1 small text-decoration-none" style="color:#d9c7ff;">
                            Daftar sekarang
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
