@extends('layouts.app')

@section('content')
    <section class="td-page td-page--after-navbar" style="background:#0b1220;">
        <div class="container">

            {{-- Breadcrumb --}}
            <nav class="td-breadcrumb mb-4" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ '/' }}" class="td-bc-link">Beranda</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ '/produk' }}" class="td-bc-link">Produk</a>
                    </li>
                    <li class="breadcrumb-item active td-bc-active" aria-current="page">
                        Kaos Oversize
                    </li>
                </ol>
            </nav>


            <div class="row g-4 align-items-start">
                {{-- Gallery --}}
                <div class="col-lg-6">
                    <div class="td-detail-gallery">
                        <div class="td-main-img">
                            <img id="mainProductImg" src="{{ $product['images'][0] }}" alt="{{ $product['name'] }}">
                            @if (!empty($product['badge']))
                                <span class="td-badge">{{ $product['badge'] }}</span>
                            @endif
                        </div>

                        <div class="td-thumbs mt-3">
                            @foreach ($product['images'] as $img)
                                <button class="td-thumb-btn" type="button" onclick="setMainImg('{{ $img }}')">
                                    <img src="{{ $img }}" alt="thumb">
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Info --}}
                <div class="col-lg-6">
                    <div class="td-detail-card">
                        <h1 class="td-detail-title">{{ $product['name'] }}</h1>

                        <div class="d-flex align-items-center gap-2 mb-3 td-detail-meta">
                            <div class="td-rating">
                                <i class="fa-solid fa-star"></i>
                                <span>{{ number_format($product['rating'], 1) }}</span>
                            </div>
                            <span class="td-dot">•</span>
                            <span class="td-sold">{{ number_format($product['sold']) }} terjual</span>
                            <span class="td-dot">•</span>
                            <span class="td-stock {{ $product['stock'] > 0 ? '' : 'is-out' }}">
                                {{ $product['stock'] > 0 ? 'Stok: ' . $product['stock'] : 'Stok habis' }}
                            </span>
                        </div>

                        @php
                            $discount = !empty($product['old_price'])
                                ? round((($product['old_price'] - $product['price']) / $product['old_price']) * 100)
                                : null;
                        @endphp

                        <div class="td-detail-price mb-4">
                            <div class="td-price-now">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>
                            @if (!empty($product['old_price']))
                                <div class="d-flex align-items-center gap-2">
                                    <div class="td-price-old">Rp
                                        {{ number_format($product['old_price'], 0, ',', '.') }}
                                    </div>
                                    <span class="td-discount">-{{ $discount }}%</span>
                                </div>
                            @endif
                        </div>

                        {{-- Variants --}}
                        <div class="row g-3 mb-4">
                            @foreach ($product['variants'] as $label => $options)
                                <div class="col-12">
                                    <div class="td-variant">
                                        <div class="td-variant-label">{{ $label }}</div>
                                        <div class="td-variant-options">
                                            @foreach ($options as $opt)
                                                <label class="td-pill">
                                                    <input type="radio"
                                                        name="variant_{{ \Illuminate\Support\Str::slug($label) }}"
                                                        value="{{ $opt }}">
                                                    <span>{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-12">
                                <div class="td-qty">
                                    <div class="td-variant-label">Jumlah</div>
                                    <div class="td-qty-control">
                                        <button type="button" class="td-qty-btn" onclick="qtyMinus()">−</button>
                                        <input id="qtyInput" class="td-qty-input" type="number" value="1"
                                            min="1">
                                        <button type="button" class="td-qty-btn" onclick="qtyPlus()">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-td td-btn-action w-100">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span>Tambah ke Keranjang</span>
                            </a>

                            <a href="#" class="btn btn-td td-btn-action w-100">
                                <i class="fa-solid fa-bolt"></i>
                                <span>Beli Sekarang</span>
                            </a>

                        </div>

                        <hr style="border-color: rgba(255,255,255,.12)" class="my-4">

                        {{-- Short desc --}}
                        <p class="td-subtitle mb-0">{{ $product['description'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="td-detail-tabs mt-5">
                <ul class="nav nav-pills gap-2 mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active td-pilltab" data-bs-toggle="pill" data-bs-target="#tab-desc"
                            type="button">Deskripsi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link td-pilltab" data-bs-toggle="pill" data-bs-target="#tab-spec"
                            type="button">Spesifikasi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link td-pilltab" data-bs-toggle="pill" data-bs-target="#tab-review"
                            type="button">Ulasan</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-desc">
                        <div class="td-detail-panel">
                            <p class="mb-0" style="color:rgba(255,255,255,.85)">{{ $product['description'] }}</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-spec">
                        <div class="td-detail-panel">
                            <div class="row g-2">
                                @foreach ($product['specs'] as $k => $v)
                                    <div class="col-12 col-md-6">
                                        <div class="td-spec-row">
                                            <span class="td-spec-key">{{ $k }}</span>
                                            <span class="td-spec-val">{{ $v }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-review">
                        <div class="td-detail-panel">
                            <div class="td-review">
                                <div class="d-flex justify-content-between">
                                    <strong style="color:#fff">Aulia</strong>
                                    <span style="color:rgba(255,255,255,.7)">5.0 ★</span>
                                </div>
                                <p class="mb-0" style="color:rgba(255,255,255,.85)">Bahannya enak, cuttingnya cakep.
                                    Pengiriman cepat.</p>
                            </div>
                            <div class="td-review mt-3">
                                <div class="d-flex justify-content-between">
                                    <strong style="color:#fff">Rizky</strong>
                                    <span style="color:rgba(255,255,255,.7)">4.5 ★</span>
                                </div>
                                <p class="mb-0" style="color:rgba(255,255,255,.85)">Sesuai foto, worth it untuk
                                    harganya.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@section('scripts')
    {{-- JS kecil untuk gallery + qty --}}
    <script>
        function setMainImg(src) {
            document.getElementById('mainProductImg').src = src;
        }

        function qtyMinus() {
            const el = document.getElementById('qtyInput');
            el.value = Math.max(1, (parseInt(el.value || 1) - 1));
        }

        function qtyPlus() {
            const el = document.getElementById('qtyInput');
            el.value = Math.max(1, (parseInt(el.value || 1) + 1));
        }
    </script>
@endsection
