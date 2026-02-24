@extends('layouts.frontend')

@section('content')

<section class="detail-wrapper text-center">
    <div class="container">

        <div class="card border-0 shadow-sm rounded-4 p-4">

            <!-- No Meja -->
            <h4 class="fw-bold">
                No Meja: {{ $transaksi->nomor_meja }}
            </h4>
            
            <h5 class="mb-4">
                {{ $transaksi->nama_customer }}
            </h5>

            <div class="my-4 text-center">  
                {!! DNS1D::getBarcodeHTML($transaksi->kode_transaksi, 'C128', 2, 90) !!}
            </div>

            <!-- Kode transaksi -->
            <p class="fw-bold">
                {{ $transaksi->kode_transaksi }}
            </p>

            <!-- Text info -->
            <div class="alert alert-warning mt-4">
                Serahkan barcode ke kasir untuk melanjutkan pembayaran
            </div>

        </div>

    </div>
</section>

@endsection