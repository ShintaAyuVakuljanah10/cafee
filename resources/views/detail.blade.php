@extends('layouts.frontend')

@section('content')

<style>
    .detail-wrapper {
        padding: 80px 0;
    }

    .product-img {
        width: 100%;
        max-height: 450px;
        object-fit: cover;
        border-radius: 20px;
    }

    .price-tag {
        font-size: 28px;
        font-weight: 700;
        color: #0d6efd;
    }

    .varian-item {
        border: 1px solid #eaeaea;
        border-radius: 14px;
        padding: 12px 18px;
        margin-bottom: 12px;
        transition: 0.2s ease;
        cursor: pointer;
    }

    .varian-item:hover {
        border-color: #0d6efd;
        background: #f8fbff;
    }

    .qty-box {
        width: 90px;
        text-align: center;
    }
</style>

<section class="detail-wrapper">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="{{ asset('storage/'.$makanan->gambar) }}"
                     class="product-img shadow-sm">
            </div>

            <div class="col-lg-6">

                <h1 class="fw-bold mb-2">
                    {{ $makanan->nama }}
                </h1>

                <div class="price-tag mb-3">
                    Rp {{ number_format($makanan->harga) }}
                </div>

                @if($makanan->deskripsi)
                    <p class="text-muted mb-4">
                        {{ $makanan->deskripsi }}
                    </p>
                @endif

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf

                    <input type="hidden" name="makanan_id" value="{{ $makanan->id_makanan }}">
                    <input type="hidden" name="nama" value="{{ $makanan->nama }}">
                    <input type="hidden" name="harga" value="{{ $makanan->harga }}">
                    <input type="hidden" name="gambar" value="{{ asset('storage/'.$makanan->gambar) }}">

                    @if($makanan->subMakanans->count() > 0)
                        <h5 class="fw-bold mb-3">Pilih Varian</h5>

                        @foreach($makanan->subMakanans as $sub)
                            <label class="varian-item w-100 d-flex justify-content-between align-items-center">
                                <div>
                                    <input type="radio"
                                           name="sub_makanan"
                                           value="{{ $sub->id_sub_makanan }}"
                                           class="form-check-input me-2" required>
                                    {{ $sub->nama }}
                                </div>
                                <span class="fw-bold text-primary">
                                    +Rp {{ number_format($sub->tambahan_harga) }}
                                </span>
                            </label>
                        @endforeach
                    @endif

                    <div class="mt-4">
                        <label class="fw-bold mb-2">Jumlah</label>
                        <input type="number"
                               name="qty"
                               class="form-control qty-box"
                               value="1"
                               min="1">
                    </div>

                    <button type="submit"
                            class="btn btn-primary w-100 mt-4 shadow-sm">
                        <i class="bi bi-cart-plus me-2"></i>
                        Tambah ke Keranjang
                    </button>
                </form>

                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-secondary w-100 mt-3 rounded-3">
                    Kembali
                </a>

            </div>
        </div>
    </div>
</section>

@endsection