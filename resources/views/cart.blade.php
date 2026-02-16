@extends('layouts.frontend')

@section('content')

<style>
    .detail-wrapper {
        padding: 80px 0;
    }

</style>

<section class="detail-wrapper">
    <div class="container">
        <h2 class="fw-bold mb-4">Keranjang Belanja</h2>

        @php $total = 0; @endphp

        @if(session('cart') && count(session('cart')) > 0)

        @foreach(session('cart') as $id => $item)

        @php
        $subtotal = $item['harga'] * $item['qty'];
        $total += $subtotal;
        @endphp

        <div class="card border-0 shadow-sm mb-4 rounded-4">
            <div class="card-body d-flex align-items-center justify-content-between">

                <div class="d-flex align-items-center">

                    <div>
                        <h5 class="fw-bold mb-1">
                            {{ $item['nama'] }}
                        </h5>

                        @if(!empty($item['sub']))
                        <small class="text-muted d-block">
                            Varian: <strong>{{ $item['sub'] }}</strong>
                        </small>
                        @endif

                        <p class="mb-1 text-muted mt-1">
                            Rp {{ number_format($item['harga']) }}
                        </p>

                        
                    </div>
                </div>

                <div class="text-end">
                    <h5 class="text-primary fw-bold">
                        Rp {{ number_format($subtotal) }}
                    </h5>

                    <div class="d-flex align-items-center gap-2 mt-2">

                        <form action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="action" value="minus">
                            <button class="btn btn-sm btn-outline-secondary">-</button>
                        </form>
                    
                        <span class="fw-bold">
                            {{ $item['qty'] }}
                        </span>
                    
                        <form action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="action" value="plus">
                            <button class="btn btn-sm btn-outline-secondary">+</button>
                        </form>
                    
                    </div>
                </div>

            </div>
        </div>

        @endforeach

        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-body d-flex justify-content-between align-items-center">

                <h4 class="fw-bold mb-0">Total</h4>

                <h4 class="fw-bold text-primary mb-0">
                    Rp {{ number_format($total) }}
                </h4>

            </div>
        </div>

        <div class="mt-4">
            <a href="#" class="btn btn-primary w-100 shadow-sm py-3">
                <i class="bi bi-credit-card me-2"></i>
                Checkout Sekarang
            </a>
        </div>

        @else

        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size:60px;"></i>
            <h4 class="mt-3">Keranjang masih kosong</h4>
            <a href="/" class="btn btn-primary mt-3">
                Kembali Belanja
            </a>
        </div>

        @endif

    </div>
</section>

@endsection
