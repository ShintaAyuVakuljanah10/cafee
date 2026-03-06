<!DOCTYPE html>
<html>

<head>
    <title>Cetak Transaksi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #e9ecef;
            /* Latar belakang abu-abu terang */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* Card Wrapper untuk tampilan di Layar */
        .preview-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Tampilan Struk (Area Thermal) */
        .ticket {
            width: 280px;
            /* Ukuran standar kertas thermal */
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            line-height: 1.4;
            color: #000;
            padding: 10px;
            border: 1px dashed #ddd;
            /* Garis bantu potong di layar */
        }

        .line {
            margin: 5px 0;
            text-align: center;
        }

        .d-flex-between {
            display: flex;
            justify-content: space-between;
        }

        .item-block {
            margin-bottom: 8px;
        }

        .item-name {
            text-transform: uppercase;
        }

        .logo-text {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .footer-msg {
            margin-top: 15px;
            font-weight: bold;
        }

        /* Tombol melayang untuk cetak ulang jika dibutuhkan */
        .no-print-btn {
            margin-bottom: 20px;
        }

        /* ATURAN PRINT */
        @media print {

            /* Sembunyikan latar belakang, card, dan tombol saat print */
            body {
                background: none;
                padding: 0;
                display: block;
            }

            .preview-card {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }

            .no-print-btn,
            .btn {
                display: none !important;
            }

            .ticket {
                border: none;
                margin: 0 auto;
                /* Menengahkan di kertas printer */
            }

            /* Hilangkan elemen header/footer browser */
            @page {
                size: auto;
                margin: 0mm;
            }
        }

    </style>
</head>

<body>

    <div class="preview-card">
        <div class="no-print-btn text-center">
            <h5 class="mb-3 text-muted">Preview Struk</h5>
            <button onclick="window.print()" class="btn btn-primary btn-sm shadow-sm">
                <i class="fa fa-print"></i> Cetak Sekarang
            </button>
            <a href="{{ route('backend.transaksi.kasir') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                Kembali ke Kasir
            </a>
        </div>

        <div id="struk-pembayaran">
            <div class="ticket">
                <center>
                    <div class="logo-text"><strong>{{ $app->nama_aplikasi }}</strong></div>
                    <div>{{ $app->alamat }}</div>
                    <div>{{ $app->telepon }}</div>
                </center>

                <div class="d-flex-between mt-3">
                    <span>Kasir: {{ Auth::user()->name ?? 'Admin' }}</span>
                    <span>No: {{ $transaksi->id }}</span>
                </div>
                <div>{{ date('d/m/Y H:i:s') }}</div>

                <div class="line">----------------------------------</div>

                @foreach($transaksi->details as $item)
                <div class="item-block">
                    <div class="item-name">{{ $item->nama_produk }}</div>
                    <div class="d-flex-between">
                        <span>{{ $item->qty }} x {{ number_format($item->harga) }}</span>
                        <span>{{ number_format($item->harga * $item->qty) }}</span>
                    </div>
                </div>
                @endforeach

                <div class="line">----------------------------------</div>

                <div class="d-flex-between">
                    <strong>TOTAL</strong>
                    <strong>Rp {{ number_format($transaksi->total) }}</strong>
                </div>

                <div class="d-flex-between">
                    <span>BAYAR</span>
                    <span>Rp {{ number_format((int) ($bayar ?? $transaksi->bayar ?? 0)) }}</span>
                </div>

                <div class="d-flex-between">
                    <span>KEMBALI</span>
                    <span>Rp {{ number_format((int) ($kembali ?? $transaksi->kembali ?? 0)) }}</span>
                </div>

                <div class="line">----------------------------------</div>

                <center class="footer-msg">
                    TERIMA KASIH<br>
                    SELAMAT JALAN<br>
                </center>
            </div>
        </div>
    </div>

</body>

</html>
