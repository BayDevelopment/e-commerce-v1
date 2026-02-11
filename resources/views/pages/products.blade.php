@extends('layouts.customer')

@section('customer')
    <div class="container-fluid py-2">

        <!-- HEADER -->
        <div class="mb-4">
            <h3 class="fw-bold text-white mb-1">Produk Kami 🛍️</h3>
            <p class="text-muted mb-0">Pilih produk terbaik untuk kebutuhan kamu</p>
        </div>

        <!-- FILTER -->
        <form method="GET" class="td-card p-3 mb-4">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="small text-white">Cari Produk</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control td-input"
                        placeholder="Nama produk...">
                </div>

                <div class="col-md-2">
                    <label class="small text-white">Harga Min</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control td-input">
                </div>

                <div class="col-md-2">
                    <label class="small text-white">Harga Max</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control td-input">
                </div>

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

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-td w-100">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-light w-100">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <!-- PRODUCT GRID -->
        <div class="row g-4">

            @forelse ($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="td-product-card h-100">

                        <div class="td-product-image">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200' }}"
                                alt="{{ $product->name }}">
                        </div>

                        <div class="td-product-body d-flex flex-column">
                            <h6 class="fw-semibold text-white text-truncate">
                                {{ $product->name }}
                            </h6>

                            <p class="text-muted small">
                                {{ Str::limit($product->description ?? '-', 60) }}
                            </p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <div class="fw-bold text-white">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>

                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-td">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </form>
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
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif

    </div>
@endsection
