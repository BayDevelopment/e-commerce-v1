@extends('layouts.customer')

@section('customer')
    <div class="container-fluid py-4">

        <!-- HEADER -->
        <div class="mb-4">
            <h3 class="fw-bold text-white mb-1">Produk Kami 🛍️</h3>
            <p class="text-muted mb-0">Pilih produk terbaik untuk kebutuhan kamu</p>
        </div>

        <!-- FILTER -->
        <form method="GET" class="td-card p-3 mb-4">
            <div class="row g-3 align-items-end">

                <!-- SEARCH -->
                <div class="col-md-4">
                    <label class="small text-white">Cari Produk</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control td-input"
                        placeholder="Nama produk...">
                </div>

                <!-- MIN PRICE -->
                <div class="col-md-2">
                    <label class="small text-white">Harga Min</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control td-input">
                </div>

                <!-- MAX PRICE -->
                <div class="col-md-2">
                    <label class="small text-white">Harga Max</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control td-input">
                </div>

                <!-- SORT -->
                <div class="col-md-2">
                    <label class="small text-white">Urutkan</label>
                    <select name="sort" class="form-select td-input">
                        <option value="">Terbaru</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                            Harga Termurah
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                            Harga Termahal
                        </option>
                    </select>
                </div>

                <!-- BUTTON -->
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-td w-100">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    <a href="{{ route('customer.product') }}" class="btn btn-outline-light w-100">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <!-- PRODUCT GRID -->
        <div class="row g-4">

            @forelse ($products as $product)
                @php
                    $firstVariant = $product->variants->first();
                    $firstImage = is_array($product->image) && count($product->image) ? $product->image[0] : null;
                    $lowestPrice = $product->variants->min('price');
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="td-product-card h-100 d-flex flex-column">

                        <!-- IMAGE -->
                        <div class="td-product-image">
                            @if ($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}"
                                    class="img-fluid">
                            @else
                                <img src="https://via.placeholder.com/300x200" alt="{{ $product->name }}" class="img-fluid">
                            @endif
                        </div>

                        <!-- BODY -->
                        <div class="td-product-body d-flex flex-column flex-grow-1">

                            <h6 class="fw-semibold text-white text-truncate">
                                {{ $product->name }}
                            </h6>

                            <p class="text-muted small mb-2">
                                {{ \Illuminate\Support\Str::limit($product->description ?? '-', 60) }}
                            </p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">

                                <!-- PRICE -->
                                <div class="fw-bold text-white">
                                    Rp {{ number_format($lowestPrice ?? 0, 0, ',', '.') }}
                                </div>

                                <!-- ADD TO CART -->
                                @if ($firstVariant)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="variant_id" value="{{ $firstVariant->id }}">
                                        <input type="hidden" name="qty" value="1">

                                        <button class="btn btn-sm btn-td">
                                            <i class="fa-solid fa-cart-plus"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>

            @empty

                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Produk belum tersedia 😢
                    </div>
                </div>
            @endforelse

        </div>

        <!-- PAGINATION -->
        @if ($products->count())
            <div class="mt-4 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @endif

    </div>
@endsection
