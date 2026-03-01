@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">

                <div class="td-card p-4">

                    <h4 class="text-white mb-3">Reset Password</h4>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">

                            <label>Email</label>

                            <input type="email" name="email" class="form-control" placeholder="Masukan email anda"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Password Baru</label>

                            <input type="password" name="password" class="form-control" placeholder="Password baru"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Konfirmasi Password</label>

                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Konfirmasi password" required>

                        </div>

                        <button class="btn btn-td w-100">
                            Reset Password
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection
