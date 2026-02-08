<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (icon) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- CSS KAMU -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="td-layout">

    <main class="d-flex align-items-center justify-content-center py-5">
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

                        {{-- SUCCESS MESSAGE --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="fa-solid fa-circle-check me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- ERROR MESSAGE (opsional, tapi bagus) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

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
                                    <input type="email" name="email" class="form-control"
                                        placeholder="email@contoh.com" required>
                                </div>
                            </div>

                            <!-- PASSWORD -->
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text td-input-icon">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••"
                                        required>
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
                            <a href="{{ route('register') }}" class="ms-1 small text-decoration-none"
                                style="color:#d9c7ff;">
                                Daftar sekarang
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

</body>

</html>
