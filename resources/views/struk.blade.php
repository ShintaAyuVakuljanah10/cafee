@extends('layouts.frontend')

@section('content')

<section class="py-5">
    <div class="container d-flex justify-content-center">

        <div style="width:350px; font-family:monospace; font-size:14px;" class="border p-4">

            <div class="text-center">
                <h5 class="fw-bold mb-0">{{ $app->nama_aplikasi }}</h5>
                <small>{{ $app->alamat ?? 'Alamat belum diatur' }}</small>
            </div>

            <hr>

            <div>
                <div>Tanggal : {{ $transaksi->created_at->format('d-m-Y') }}</div>
                <div>Jam     : {{ $transaksi->created_at->format('H:i') }}</div>
                <div>Kode    : {{ $transaksi->kode_transaksi }}</div>
            </div>

            <hr>

            @php $subtotal = 0; @endphp

            @foreach($transaksi->details as $item)

                @php $subtotal += $item->subtotal; @endphp

                <div>
                    {{ $item->nama_produk }}
                </div>

                <div class="d-flex justify-content-between">
                    <span>{{ $item->qty }} x {{ number_format($item->harga) }}</span>
                    <span>{{ number_format($item->subtotal) }}</span>
                </div>

                <br>

            @endforeach

            <hr>

            <div class="d-flex justify-content-between">
                <strong>Total</strong>
                <strong>{{ number_format($subtotal) }}</strong>
            </div>

            <div class="mt-2">
                Status : 
                <strong>
                    {{ strtoupper($transaksi->status) }}
                </strong>
            </div>

            <hr>

            <div style="width:100%; text-align:center; margin-top:15px;">
    
                <div style="display:inline-block;">
                    {!! DNS1D::getBarcodeHTML($transaksi->kode_transaksi, 'C128', 1.2, 50) !!}
                </div>
            
                <div style="margin-top:5px;">
                    {{ $transaksi->kode_transaksi }}
                </div>
            
            </div>

            <hr>

            <div class="text-center mt-2">
                Terima kasih atas kunjungan Anda
            </div>

        </div>

    </div>
</section>

@endsection