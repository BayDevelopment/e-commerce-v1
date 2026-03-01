@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                <div class="td-card p-4 p-md-5">

                    <div class="text-center mb-4">
                        <div class="td-icon mx-auto mb-3">
                            <i class="fa-solid fa-key"></i>
                        </div>

                        <h4 class="fw-bold text-white">Lupa Password</h4>

                        <p class="text-secondary small">
                            Masukkan email untuk reset password
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">

                            <label>Email</label>

                            <div class="input-group">

                                <span class="input-group-text td-input-icon">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>

                                <input type="email" name="email" class="form-control" placeholder="Masukan email anda"
                                    required>

                            </div>

                        </div>

                        <button class="btn btn-td w-100">
                            <i class="fa-solid fa-paper-plane"></i>
                            Kirim Link Reset
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection
