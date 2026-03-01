@extends('layouts.backend')

@section('title', 'Data Transaksi')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Transaksi Management</h4>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle" id="trxTable">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Barcode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>
@endsection


@push('scripts')
<script>
    $(document).ready(function () {

        let table = $('#trxTable').DataTable();
        loadData();

        function loadData() {
            $.get("{{ route('backend.transaksi.data') }}", function (data) {
                table.clear();

                data.forEach((item, i) => {
                    // Logika Status: Tetap tampilkan PENDING jika belum lunas
                    // Jika Anda ingin status ini berubah secara otomatis setelah proses di kasir, 
                    // pastikan backend Anda mengupdate kolom status menjadi 'lunas'.
                    let statusBadge = item.status === 'lunas' ?
                        `<span class="badge bg-success">LUNAS</span>` :
                        `<span class="badge bg-warning">PENDING</span>`;

                    // Tombol Bayar dihapus dari sini sesuai permintaan
                    
                    table.row.add([
                        i + 1,
                        item.kode_transaksi,
                        moment(item.created_at).format('DD-MM-YYYY HH:mm'),
                        'Rp ' + new Intl.NumberFormat().format(item.total),
                        item.barcode,
                        statusBadge,
                        `
                        <div class="btn-group btn-group-sm">
                            <a href="/backend/transaksi/${item.id}/cetak"
                                target="_blank"
                                class="btn btn-outline-success">
                                <i class="mdi mdi-printer"></i>
                            </a>
                        </div>
                        `
                    ]);
                });

                table.draw();
            });
        }   


    });

</script>
@endpush
@push('style')
        <style>
            .ticket {
                width: 280px; /* Sedikit lebih ramping agar aman di thermal 58mm/80mm */
                font-family: 'Courier New', Courier, monospace;
                font-size: 13px;
                line-height: 1.3;
                color: #000;
                padding: 5px;
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
                margin-bottom: 8px; /* Jarak antar produk */
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

            @media print {
                body * { visibility: hidden; }
                #struk-pembayaran, #struk-pembayaran * { visibility: visible; }
                #struk-pembayaran {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    display: block !important;
                }
                @page { size: auto; margin: 0mm; }
            }
        </style>
    @endpush
