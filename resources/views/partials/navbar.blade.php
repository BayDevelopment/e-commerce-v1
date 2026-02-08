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
                    <a class="nav-link nav-underline {{ $navlink === 'beranda' ? 'active' : '' }}"
                        href="{{ url('/') }}">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-underline td-login-icon" href="#" title="Login">
                        <i class="fa-regular fa-user"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
