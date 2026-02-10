@extends('layouts.customer')

@section('customer')
    <div class="container-fluid py-2">

        <!-- HEADER -->
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div>
                <h3 class="fw-bold text-white mb-1">
                    Produk Kami 🛍️
                </h3>
                <p class="text-muted mb-0">
                    Pilih produk terbaik untuk kebutuhan kamu
                </p>
            </div>
        </div>

        <!-- PRODUCT GRID -->
        <div class="row g-4">

            @forelse ($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="td-product-card h-100">

                        <!-- IMAGE -->
                        <div class="td-product-image">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200' }}"
                                alt="{{ $product->name }}">
                        </div>

                        <!-- BODY -->
                        <div class="td-product-body">
                            <h6 class="fw-semibold text-white mb-1">
                                {{ $product->name }}
                            </h6>

                            <p class="text-muted small mb-2">
                                {{ Str::limit($product->description, 60) }}
                            </p>

                            <div class="d-flex align-items-center justify-content-between mt-auto">
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
                    <div class="td-card p-4 text-center text-muted">
                        Produk belum tersedia 😢
                    </div>
                </div>
            @endforelse

        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>

    </div>
@endsection
