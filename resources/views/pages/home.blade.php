@extends('layouts.app')

@section('content')
    <!-- HERO -->
    <section id="home" class="td-hero min-vh-100 d-flex align-items-center td-top-space">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="td-badge mb-3">
                        <span>⚡</span>
                        <span>Katalog e-commerce modern • Cepat • Responsif</span>
                    </div>

                    <h1 class="display-5 fw-bold lh-1">
                        Temukan gaya terbaikmu di <span
                            style="background:linear-gradient(135deg,#a78bfa,#60a5fa);-webkit-background-clip:text;background-clip:text;color:transparent;">Trendora</span>
                    </h1>
                    <p class="lead td-subtitle mt-3">
                        Platform katalog fashion & lifestyle yang rapi, elegan, dan siap scale — dari UMKM sampai brand
                        besar.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="#products" class="btn btn-td btn-lg td-btn-hero">
                                <i class="fa-solid fa-store"></i>
                                <span>Lihat Katalog</span>
                            </a>

                            <a href="#contact" class="btn btn-outline-td btn-lg td-btn-hero">
                                <i class="fa-solid fa-envelope"></i>
                                <span>Hubungi Kami</span>
                            </a>
                        </div>

                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <span class="td-chip">SEO-friendly</span>
                        <span class="td-chip">Mobile-first</span>
                        <span class="td-chip">Admin-ready</span>
                        <span class="td-chip">Fast UI</span>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="td-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="td-icon">🧠</div>
                                <div>
                                    <div class="fw-semibold">Trendora Preview</div>
                                    <div class="small td-subtitle">UI modern untuk katalog produk</div>
                                </div>
                            </div>
                            <span class="td-chip">v1.0</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="td-card p-3" style="border-radius:16px;">
                                    <div class="small td-subtitle">Produk</div>
                                    <div class="h3 fw-bold mb-0">1.2k+</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="td-card p-3" style="border-radius:16px;">
                                    <div class="small td-subtitle">Kategori</div>
                                    <div class="h3 fw-bold mb-0">48</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="td-card p-3" style="border-radius:16px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Rekomendasi Hari Ini</div>
                                            <div class="small td-subtitle">Kurasi tren terbaik untukmu</div>
                                        </div>
                                        <span class="td-chip">New</span>
                                    </div>
                                    <div class="mt-3">
                                        <div class="progress" style="height:10px;background:rgba(255,255,255,.08);">
                                            <div class="progress-bar" style="width:72%;"></div>
                                        </div>
                                        <div class="small td-subtitle mt-2">Kurasi tren: 72% selesai</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="text-center mt-3 small td-subtitle">*Angka hanya contoh tampilan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="td-section py-5">
        <div class="container py-2">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Kenapa Trendora?</h2>
                <p class="td-subtitle mb-0">Solusi katalog yang modern, cepat, dan mudah dikelola.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="td-card p-4">
                        <div class="td-icon mb-3">🛍️</div>
                        <h5 class="fw-semibold">Katalog Lengkap</h5>
                        <p class="td-subtitle mb-0">Tampilkan produk dengan kategori jelas, foto tajam, dan detail yang
                            rapi.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="td-card p-4">
                        <div class="td-icon mb-3">⚡</div>
                        <h5 class="fw-semibold">Cepat & Responsif</h5>
                        <p class="td-subtitle mb-0">Optimasi pengalaman pengguna dengan tampilan mobile-first & performa
                            ringan.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="td-card p-4">
                        <div class="td-icon mb-3">🔒</div>
                        <h5 class="fw-semibold">Aman & Terpercaya</h5>
                        <p class="td-subtitle mb-0">Struktur siap untuk autentikasi admin dan pengelolaan data yang aman.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="td-section py-5"
        style="background: linear-gradient(180deg, #0b1220 0%, #0b1220 60%, rgba(255,255,255,.02) 100%);">
        <div class="container py-2">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Produk Unggulan</h2>
                    <p class="td-subtitle mb-0">Pilihan terbaik dari Trendora (contoh).</p>
                </div>
                <a href="{{ url('/produk') }}" class="btn btn-outline-td">Lihat Semua</a>
            </div>

            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="td-product-card h-100">
                            <a href="{{ url('produk/' . $product->id) }}" class="td-product-thumb">

                                @if ($product->image && count($product->image) > 0)
                                    <img src="{{ asset('storage/' . $product->image[0]) }}" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" alt="{{ $product->name }}">
                                @endif

                                <span class="td-badge {{ $product->is_new ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->is_new ? 'Baru' : 'Lama' }}
                                </span>

                            </a>

                            <div class="td-product-body">
                                <a href="{{ url('produk/' . $product->category->slug . '/' . $product->slug) }}"
                                    class="td-product-title">
                                    {{ $product->name }}
                                </a>

                                <div class="td-price-row">
                                    <div class="td-price">
                                        <span class="td-price-now">
                                            Rp {{ number_format($product->lowest_price ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>


                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ url('produk/' . $product->category->slug . '/' . $product->slug) }}"
                                        class="btn btn-td w-100 td-btn-action">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>Detail</span>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


            <!-- CTA -->
            <div class="td-cta p-4 p-md-5 mt-5">
                <div class="content d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h3 class="fw-bold mb-1">Siap naik level bareng Trendora?</h3>
                        <p class="td-subtitle mb-0">Bangun katalog yang cepat, rapi, dan gampang dikelola.</p>
                    </div>
                    <a href="#contact" class="btn btn-light btn-lg td-btn-cta">
                        <i class="fa-solid fa-rocket"></i>
                        <span>Mulai Sekarang</span>
                    </a>

                </div>
            </div>
        </div>
    </section>


    <!-- CONTACT -->
    <section id="contact" class="td-section py-5">
        <div class="container py-2">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Hubungi Kami</h2>
                <p class="td-subtitle mb-0">Butuh website katalog / e-commerce? Kirim detail, kita diskusi.</p>
            </div>

            <div class="row justify-content-center g-4">
                <div class="col-lg-5">
                    <div class="row g-3">

                        <!-- Email Card -->
                        <div class="col-12">
                            <div class="td-card td-contact-card p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="td-contact-icon">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Email</div>
                                        <div class="td-subtitle text-white">
                                            support@trendora.test
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp Card -->
                        <div class="col-12">
                            <div class="td-card td-contact-card p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="td-contact-icon td-wa">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">WhatsApp</div>
                                        <div class="td-subtitle text-white">
                                            +62 8xx-xxxx-xxxx
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat Card (opsional) -->
                        <div class="col-12">
                            <div class="td-card td-contact-card p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="td-contact-icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Alamat</div>
                                        <div class="td-subtitle text-white">
                                            Jakarta, Indonesia
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-lg-7">
                    <div class="td-card p-4 p-md-5">
                        <form>
                            <div class="row g-3">

                                <!-- Nama -->
                                <div class="col-md-6">
                                    <label class="form-label small">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text td-input-icon">
                                            <i class="fa-solid fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Nama kamu" required>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label small">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text td-input-icon">
                                            <i class="fa-solid fa-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control" placeholder="email@contoh.com"
                                            required>
                                    </div>
                                </div>

                                <!-- Subjek -->
                                <div class="col-12">
                                    <label class="form-label small">Subjek</label>
                                    <div class="input-group">
                                        <span class="input-group-text td-input-icon">
                                            <i class="fa-solid fa-tag"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Judul pesan" required>
                                    </div>
                                </div>

                                <!-- Pesan -->
                                <div class="col-12">
                                    <label class="form-label small">Pesan</label>
                                    <div class="input-group">
                                        <span class="input-group-text td-input-icon align-items-start pt-3">
                                            <i class="fa-solid fa-message"></i>
                                        </span>
                                        <textarea class="form-control" rows="5" placeholder="Tulis pesan kamu..." required></textarea>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-td btn-lg w-100 td-btn-action">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        <span>Kirim Pesan</span>
                                    </button>
                                    <div class="small td-subtitle text-center mt-2">
                                        Dengan mengirim, kamu setuju dihubungi oleh tim Trendora.
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
