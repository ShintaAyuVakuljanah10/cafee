@extends('layouts.backend')

@section('title', 'Sub Makanan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Sub Makanan Management</h4>

        <button class="btn btn-success" data-toggle="modal" data-target="#modalSub">
            <i class="mdi mdi-plus"></i>
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle" id="subTable">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Makanan</th>
                        <th>Nama Sub</th>
                        <th>Tambahan Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection


<div class="modal fade" id="modalSub">
    <div class="modal-dialog">
        <form id="formSub">
            @csrf
            <input type="hidden" id="id_sub">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sub Makanan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Pilih Makanan</label>
                        <select id="id_makanan" class="form-control">
                            @foreach(\App\Models\Backend\Makanan::all() as $m)
                            <option value="{{ $m->id_makanan }}">
                                {{ $m->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nama Sub</label>
                        <input type="text" id="nama_sub" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Tambahan Harga</label>
                        <input type="number" id="tambahan_harga" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
    $(document).ready(function () {

        let table = $('#subTable').DataTable();
        loadData();

        function loadData() {
            $.get("{{ route('backend.sub-makanan.data') }}", function (data) {
                table.clear();
                data.forEach((item, i) => {
                    table.row.add([
                        i + 1,
                        item.makanan.nama,
                        item.nama,
                        'Rp ' + item.tambahan_harga,
                        `
                    <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary edit" data-id="${item.id_sub_makanan}"><i class="mdi mdi-pencil"></i></button>
                            <button class="btn btn-outline-danger delete" data-id="${item.id_sub_makanan}"><i class="mdi mdi-delete"></i></button>
                        </div>
                    `
                    ]);
                });
                table.draw();
            });
        }

        $('#formSub').submit(function (e) {
            e.preventDefault();

            let id = $('#id_sub').val();
            let url = id 
                ? `/backend/sub-makanan/${id}` 
                : "{{ route('backend.sub-makanan.store') }}";

            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: {
                    id_makanan: $('#id_makanan').val(),
                    nama: $('#nama_sub').val(),
                    tambahan_harga: $('#tambahan_harga').val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function () {
                    $('#modalSub').modal('hide');
                    $('#formSub')[0].reset();
                    $('#id_sub').val('');
                    $('.modal-title').text('Tambah Sub Makanan');
                    loadData();

                    Swal.fire('Berhasil!', 'Data berhasil disimpan', 'success');
                }
            });
        });

        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');

            $.get("{{ route('backend.sub-makanan.data') }}", function (data) {
                let item = data.find(x => x.id_sub_makanan == id);

                if (item) {
                    $('#id_sub').val(item.id_sub_makanan);
                    $('#id_makanan').val(item.id_makanan);
                    $('#nama_sub').val(item.nama);
                    $('#tambahan_harga').val(item.tambahan_harga);

                    $('.modal-title').text('Edit Sub Makanan');
                    $('#modalSub').modal('show');
                }
            });
        });

        $(document).on('click', '.delete', function () {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Yakin hapus?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/backend/sub-makanan/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function () {
                            loadData();
                            Swal.fire('Terhapus!', 'Data berhasil dihapus.',
                                'success');
                        }
                    });
                }
            });
        });

    });

</script>
@endpush
