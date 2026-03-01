    @extends('layouts.backend')

    @section('title','Pembayaran')

    @section('content')

    <div class="row">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header">
                    <h5>Detail Pesanan</h5>
                </div>

                <div class="card-body">

                    @foreach($transaksi->details as $item)

                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            {{ $item->nama_produk }} x {{ $item->qty }}
                        </div>

                        <div>
                            Rp {{ number_format($item->harga * $item->qty) }}
                        </div>
                    </div>

                    @endforeach

                    <hr>

                    <h4 class="text-right text-primary">
                        Total : Rp {{ number_format($transaksi->total) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header">
                    <h5>Pembayaran</h5>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Total</label>
                        <input type="text" id="total" class="form-control" value="{{ $transaksi->total }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Uang Bayar</label>
                        <input type="number" id="bayar" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Kembalian</label>
                        <input type="text" id="kembalian" class="form-control" readonly>
                    </div>

                    <button class="btn btn-success btn-block">
                        Bayar
                    </button>

                </div>

            </div>

        </div>

    </div>
    {{-- Container ini hanya muncul saat print, atau bisa kita buat preview di layar --}}
    <div id="struk-pembayaran" class="d-none">
        <div class="preview-container"> {{-- Pembungkus agar bisa ditengah --}}
            <div class="ticket shadow-sm">
                <center>
                    <div class="logo-text"><strong>{{ $app->nama_aplikasi }}</strong></div>
                    <div>{{ $app->alamat }}</div>
                    <div>{{ $app->telepon }}</div>
                </center>
                
                <div class="d-flex-between mt-2">
                    <span>Oper: {{ Auth::user()->name ?? 'Admin' }}</span>
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
                    <strong>{{ number_format($transaksi->total) }}</strong>
                </div>

                <div class="d-flex-between">
                    <span>BAYAR</span>
                    <span id="print-bayar">Rp 0</span>
                </div>
                
                <div class="d-flex-between">
                    <span>KEMBALI</span>
                    <span id="print-kembali">Rp 0</span>
                </div>

                <div class="line">----------------------------------</div>
                
                <center class="footer-msg">
                    TERIMA KASIH<br>
                    SELAMAT JALAN<br>
                </center>
            </div>
        </div>
    </div>
    @endsection
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Logika hitung kembalian
            $('#bayar').on('keyup', function() {
                let bayar = parseInt($(this).val()) || 0;
                let total = parseInt($('#total').val());
                let kembali = bayar - total;

                $('#kembalian').val("Rp " + kembali.toLocaleString());
                
                // Update data ke elemen struk
                $('#print-bayar').text("Rp " + bayar.toLocaleString());
                $('#print-kembali').text("Rp " + kembali.toLocaleString());
            });

            $('.btn-success').on('click', function() {
                let bayar = parseInt($('#bayar').val()) || 0;
                let total = parseInt($('#total').val());
                let transaksiId = "{{ $transaksi->id }}"; // Ambil ID transaksi dari Laravel

                if (bayar < total) {
                    Swal.fire('Gagal', 'Uang bayar tidak cukup!', 'error');
                    return;
                }

                // 1. Kirim permintaan ke server untuk update status ke LUNAS
                $.post(`/backend/transaksi/${transaksiId}/bayar`, {
                    _token: "{{ csrf_token() }}",
                    bayar: bayar // Mengirimkan info uang bayar jika diperlukan di backend
                }, function(res) {
                    if (res.success) {
                        // 2. Jika berhasil update di DB, tampilkan pesan sukses
                        Swal.fire({
                            title: 'Pembayaran Berhasil!',
                            text: 'Status pesanan LUNAS. Mencetak struk...',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // 3. Jalankan perintah cetak
                            setTimeout(function() {
                                window.print();
                            }, 500);
                        });
                    } else {
                        Swal.fire('Gagal', 'Gagal memperbarui status transaksi', 'error');
                    }
                }).fail(function() {
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                });
            });

            // Otomatis pindah ke kasir setelah print selesai atau dibatalkan
            window.onafterprint = function() {
                window.location.href = "{{ route('backend.transaksi.kasir') }}"; // Pastikan nama route benar
            };
        </script>
    @endpush
    @push('style')
<style>
    /* Style untuk Thermal Struk */
    .ticket {
        width: 280px;
        background: white;
        font-family: 'Courier New', Courier, monospace;
        font-size: 13px;
        line-height: 1.3;
        color: #000;
        padding: 15px;
        margin: 20px auto; /* Membuatnya di tengah secara horizontal */
        border: 1px dashed #ccc; /* Penanda di layar */
    }
    
    .line { margin: 5px 0; text-align: center; }
    .d-flex-between { display: flex; justify-content: space-between; }
    .item-block { margin-bottom: 8px; }
    .item-name { text-transform: uppercase; }
    .logo-text { font-size: 16px; margin-bottom: 2px; }
    .footer-msg { margin-top: 15px; font-weight: bold; }

    @media print {
        /* Sembunyikan semua elemen UI Laravel */
        body * { visibility: hidden; }
        
        /* Munculkan hanya area struk */
        #struk-pembayaran, #struk-pembayaran * { visibility: visible; }
        
        #struk-pembayaran {
            display: block !important;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .preview-container {
            display: flex;
            justify-content: center;
        }

        .ticket {
            border: none;
            margin: 0;
            padding: 0;
            box-shadow: none;
        }

        @page { size: auto; margin: 0mm; }
    }
</style>
@endpush
    