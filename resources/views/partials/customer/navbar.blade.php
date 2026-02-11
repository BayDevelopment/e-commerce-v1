<nav class="navbar navbar-expand-lg fixed-top td-navbar">
    <div class="container">
        <a class="navbar-brand td-brand" href="#">
            Trendora
        </a>

        <button class="navbar-toggler td-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link nav-underline {{ $navlink === 'dashboard' ? 'active' : '' }}"
                        href="{{ url('/customer/dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-underline {{ $navlink === 'product' ? 'active' : '' }}"
                        href="{{ url('/customer/product') }}">
                        Product
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-underline {{ $navlink === 'laporan' ? 'active' : '' }}"
                        href="{{ url('/customer/laporan') }}">
                        Laporan
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center gap-2 td-user-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">

                        <!-- AVATAR -->
                        <div class="td-user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <!-- ICON -->
                        <i class="fa-solid fa-chevron-down td-user-caret d-none d-md-inline"></i>
                    </a>

                    <!-- DROPDOWN -->
                    <ul class="dropdown-menu dropdown-menu-end td-user-dropdown">
                        <li class="px-3 py-2">
                            <div class="td-user-mini">
                                <div class="td-user-avatar sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="ms-2">
                                    <div class="fw-semibold text-white">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="small text-secondary">
                                        {{ Auth::user()->email }}
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                <i class="fa-regular fa-user me-2"></i>
                                Profile
                            </a>
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout.proses') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
