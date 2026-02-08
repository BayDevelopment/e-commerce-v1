@extends('layouts.app')

@section('content')
    <section class="td-page td-page--after-navbar" style="background:#0b1220;">
        <div class="container">

            {{-- Header --}}
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
                <div>
                    <h2 class="fw-bold mb-1 text-white">Semua Produk</h2>
                    <p class="td-subtitle mb-0">Cari produk terbaik untuk gaya kamu.</p>
                </div>

                {{-- Search --}}
                <div class="td-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="form-control td-search-input" placeholder="Cari produk...">
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="td-filterbar td-card p-3 mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md-5">
                        <label class="form-label small mb-1">Kategori</label>
                        <select class="form-select">
                            @foreach ($categories as $cat)
                                <option>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small mb-1">Urutkan</label>
                        <select class="form-select">
                            <option>Terbaru</option>
                            <option>Terlaris</option>
                            <option>Harga: Rendah ke Tinggi</option>
                            <option>Harga: Tinggi ke Rendah</option>
                            <option>Rating Tertinggi</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label small mb-1">Tampilan</label>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-td w-100 td-btn-action td-view-btn active" data-view="grid"
                                type="button">
                                <i class="fa-solid fa-border-all"></i>
                                <span>Grid</span>
                            </button>

                            <button class="btn btn-outline-td w-100 td-btn-action td-view-btn" data-view="list"
                                type="button">
                                <i class="fa-solid fa-list"></i>
                                <span>List</span>
                            </button>
                        </div>


                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            <div id="productWrapper" class="td-view-grid">
                <div class="row g-4">
                    @foreach ($products as $item)
                        @php
                            $discount = $item['old_price']
                                ? round((($item['old_price'] - $item['price']) / $item['old_price']) * 100)
                                : null;
                        @endphp

                        <div class="col-12 col-sm-6 col-lg-4 td-product-item">
                            <div class="td-product-card h-100">
                                <a href="{{ url('produk/' . $item['slug']) }}" class="td-product-thumb">
                                    <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}">
                                    <span class="td-badge">{{ $item['badge'] }}</span>
                                    @if ($discount)
                                        <span class="td-discount">-{{ $discount }}%</span>
                                    @endif
                                </a>

                                <div class="td-product-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="td-chip">{{ $item['category'] }}</span>
                                        <div class="td-rating">
                                            <i class="fa-solid fa-star"></i>
                                            <span>{{ number_format($item['rating'], 1) }}</span>
                                        </div>
                                    </div>

                                    <a href="{{ url('produk/' . $item['slug']) }}" class="td-product-title">
                                        {{ $item['name'] }}
                                    </a>

                                    <div class="td-price-row">
                                        <div class="td-price">
                                            <span class="td-price-now">Rp
                                                {{ number_format($item['price'], 0, ',', '.') }}</span>
                                            @if ($item['old_price'])
                                                <span class="td-price-old">Rp
                                                    {{ number_format($item['old_price'], 0, ',', '.') }}</span>
                                            @endif
                                        </div>

                                        <a href="#" class="td-cart-btn" title="Tambah ke keranjang">
                                            <i class="fa-solid fa-cart-plus"></i>
                                        </a>
                                    </div>

                                    <div class="d-flex gap-2 mt-3">
                                        <a href="{{ url('produk/' . $item['slug']) }}"
                                            class="btn btn-td w-100 td-btn-action">
                                            <i class="fa-solid fa-eye"></i><span>Detail</span>
                                        </a>
                                        <a href="#" class="btn btn-outline-td w-100 td-btn-action">
                                            <i class="fa-solid fa-bag-shopping"></i><span>Beli</span>
                                        </a>
                                    </div>

                                    <div class="small td-subtitle mt-3">
                                        <i class="fa-solid fa-fire me-1"></i>{{ number_format($item['sold']) }} terjual
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pagination (dummy UI) --}}
            <div class="d-flex justify-content-center mt-5">
                <nav>
                    <ul class="pagination td-pagination">
                        <li class="page-item disabled"><a class="page-link" href="#">‹</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">›</a></li>
                    </ul>
                </nav>
            </div>

        </div>
    </section>
@endsection
@section('scripts')
    <script>
        document.querySelectorAll('.td-view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.dataset.view;
                const wrapper = document.getElementById('productWrapper');

                // toggle wrapper class
                wrapper.classList.remove('td-view-grid', 'td-view-list');
                wrapper.classList.add('td-view-' + view);

                // active button state
                document.querySelectorAll('.td-view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
@endsection
