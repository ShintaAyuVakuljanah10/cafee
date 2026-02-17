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

                    let statusBadge = item.status === 'lunas' ?
                        `<span class="badge bg-success">LUNAS</span>` :
                        `<span class="badge bg-warning">PENDING</span>`;

                    let tombolBayar = item.status === 'pending' ?
                        `<button class="btn btn-outline-primary bayar" data-id="${item.id}">
                            <i class="mdi mdi-cash"></i>
                        </button>` :
                        '';

                        table.row.add([
                        i + 1,
                        item.kode_transaksi,
                        moment(item.created_at).format('DD-MM-YYYY HH:mm'),
                        'Rp ' + new Intl.NumberFormat().format(item.total),

                        item.barcode,

                        statusBadge,

                        `
                        <div class="btn-group btn-group-sm">
                            ${tombolBayar}
                            <a href="/backend/transaksi/${item.id}/cetak"
                            target="_blank"
                            class="btn btn-outline-success">
                            <i class="mdi mdi-printer"></i>
                            </a>
                            <button class="btn btn-outline-danger delete"
                                    data-id="${item.id}">
                                    <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                        `
                    ]);
                });

                table.draw();
            });
        }

        $(document).on('click', '.delete', function () {

            let id = $(this).data('id');

            Swal.fire({
                title: 'Yakin hapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: `/backend/transaksi/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function () {
                            loadData();
                            Swal.fire('Terhapus!', '', 'success');
                        }
                    });

                }

            });
        });

        $(document).on('click', '.bayar', function () {

            let id = $(this).data('id');

            Swal.fire({
                title: 'Konfirmasi Pembayaran?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Bayar'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post(`/backend/transaksi/${id}/bayar`, {
                        _token: "{{ csrf_token() }}"
                    }, function () {

                        loadData();
                        Swal.fire('Berhasil!', 'Transaksi dilunasi.', 'success');

                    });

                }

            });

        });

    });

</script>
@endpush
