@extends('layouts.backend')

@section('title', 'Makanan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Makanan Management</h4>

        <button class="btn btn-success" data-toggle="modal" data-target="#modalMakanan">
            <i class="mdi mdi-plus"></i>
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle" id="makananTable">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
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


<div class="modal fade" id="modalMakanan">
    <div class="modal-dialog">
        <form id="formMakanan">
            @csrf
            <input type="hidden" id="id_makanan">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Makanan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" id="nama" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Category</label>
                        <select id="id_category" class="form-control">
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" id="harga" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select id="status" class="form-control">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Pilih Gambar</label>
                        <input type="hidden" id="gambar">
                        
                        <div class="row">
                            @foreach($files as $file)
                                <div class="col-4 mb-2">
                                    <img src="{{ asset('storage/'.$file->gambar) }}"
                                         class="img-thumbnail pilih-gambar"
                                         data-path="{{ $file->gambar }}"
                                         style="cursor:pointer;">
                                </div>
                            @endforeach
                        </div>
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
$(document).ready(function(){

    let table = $('#makananTable').DataTable();

    loadData();

    function loadData(){
        $.get("{{ route('backend.makanan.data') }}", function(data){
            table.clear();
            data.forEach((item, i)=>{
                table.row.add([
                    i+1,
                    `<img src="/storage/${item.gambar}" width="60" class="img-thumbnail">`,
                    item.nama,
                    item.category ? item.category.name : '-',
                    'Rp ' + item.harga,
                    item.status == 'aktif' 
                        ? '<span class="badge badge-success">Aktif</span>'
                        : '<span class="badge badge-secondary">Nonaktif</span>',
                    `
                    <button class="btn btn-sm btn-primary edit" data-id="${item.id_makanan}">
                        <i class="mdi mdi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete" data-id="${item.id_makanan}">
                        <i class="mdi mdi-delete"></i>
                    </button>
                    `
                ]);
            });
            table.draw();
        });
    }

    $('#formMakanan').submit(function(e){
        e.preventDefault();

        let id = $('#id_makanan').val();
        let url = id 
            ? `/backend/makanan/${id}` 
            : `/backend/makanan/store`;

        let data = {
            nama: $('#nama').val(),
            id_category: $('#id_category').val(),
            harga: $('#harga').val(),
            status: $('#status').val(),
            gambar: $('#gambar').val(),
            _token: "{{ csrf_token() }}"
        };

        if(id) data._method = 'PUT';

        $.post(url, data, function(){
            $('#modalMakanan').modal('hide');
            loadData();
            Swal.fire('Berhasil!', 'Data tersimpan', 'success');
        });
    });

    $(document).on('click', '.pilih-gambar', function(){
        $('.pilih-gambar').removeClass('border border-primary');
        $(this).addClass('border border-primary');

        let path = $(this).data('path');
        $('#gambar').val(path);
    });

    $(document).on('click', '.edit', function(){
        let id = $(this).data('id');

        $.get(`/backend/makanan/data`, function(data){
            let item = data.find(x => x.id_makanan == id);

            $('#id_makanan').val(item.id_makanan);
            $('#nama').val(item.nama);
            $('#id_category').val(item.id_category);
            $('#harga').val(item.harga);
            $('#status').val(item.status);
            $('#gambar').val(item.gambar);

            $('.pilih-gambar').removeClass('border border-primary');
            $(`.pilih-gambar[data-path="${item.gambar}"]`)
                .addClass('border border-primary');

            $('.modal-title').text('Edit Makanan');
            $('#modalMakanan').modal('show');
        });
    });

    $(document).on('click', '.delete', function(){
        let id = $(this).data('id');

        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: `/backend/makanan/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(){
                        loadData();
                        Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                    }
                });
            }
        });
    });
});
</script>
@endpush