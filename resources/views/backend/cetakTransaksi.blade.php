<!DOCTYPE html>
<html>
<head>
    <title>Cetak Transaksi</title>

    <style>
        body {
            font-family: monospace;
            font-size: 13px;
        }

        .struk {
            width: 320px;
            margin: auto;
        }

        hr {
            border: 0;
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="struk">

    <div style="text-align:center;">
        <strong>{{ $app->nama_aplikasi ?? 'Nama Toko' }}</strong><br>
        <small>{{ $app->alamat ?? 'Alamat belum diatur' }}</small>
    </div>

    <hr>

    <div>
        Tanggal : {{ $transaksi->created_at->format('d-m-Y') }} <br>
        Jam     : {{ $transaksi->created_at->format('H:i') }} <br>
        Kode    : {{ $transaksi->kode_transaksi }}
    </div>

    <hr>

    @php $subtotal = 0; @endphp

    @foreach($transaksi->details as $item)

        @php $subtotal += $item->subtotal; @endphp

        <div>
            {{ $item->nama_produk }}
        </div>

        <div style="display:flex; justify-content:space-between;">
            <span>{{ $item->qty }} x {{ number_format($item->harga) }}</span>
            <span>{{ number_format($item->subtotal) }}</span>
        </div>

        <br>

    @endforeach

    <hr>

    <div style="display:flex; justify-content:space-between;">
        <strong>Total</strong>
        <strong>{{ number_format($subtotal) }}</strong>
    </div>

    <div style="margin-top:5px;">
        Status : <strong>{{ strtoupper($transaksi->status) }}</strong>
    </div>

    <hr>

    <div style="text-align:center; margin-top:10px;">

        <div style="display:inline-block;">
            {!! DNS1D::getBarcodeHTML($transaksi->kode_transaksi, 'C128', 1.2, 50) !!}
        </div>

        <div style="margin-top:5px;">
            {{ $transaksi->kode_transaksi }}
        </div>

    </div>

    <hr>

    <div style="text-align:center; margin-top:10px;">
        Terima kasih atas kunjungan Anda
    </div>

</div>

</body>
</html>