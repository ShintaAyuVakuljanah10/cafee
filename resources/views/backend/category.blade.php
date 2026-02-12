@extends('layouts.backend')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="container">
    <button id="btnAddCategory" class="btn btn-primary mb-3">+ Tambah Kategori</button>

    <div class="card">
        <div class="card-body">
            <h3 class="font-weight-bold mb-3">Data Kategori</h3>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center" id="categoryTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCategory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCategory">
            @csrf
            <input type="hidden" id="category_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="Contoh: Makanan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let categoryTable;

    $(document).ready(function() {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        categoryTable = $('#categoryTable').DataTable();
        loadCategory();

        function loadCategory() {
            $.get("/backend/category/data", function(data) {
                categoryTable.clear();
                $.each(data, function(i, val) {
                    categoryTable.row.add([
                        i + 1,
                        val.name,
                        val.slug,
                        `<div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-edit" data-id="${val.id}"><i class="mdi mdi-pencil"></i></button>
                            <button class="btn btn-outline-danger btn-delete" data-id="${val.id}"><i class="mdi mdi-delete"></i></button>
                        </div>`
                    ]);
                });
                categoryTable.draw();
            });
        }

        $('#btnAddCategory').click(function() {
            $('#formCategory')[0].reset();
            $('#category_id').val('');
            $('#modalTitle').text('Tambah Kategori');
            $('#modalCategory').modal('show');
        });

        $('#formCategory').submit(function(e) {
            e.preventDefault();
            
            let id = $('#category_id').val();
            // Gunakan metode PUT jika melakukan update, Laravel membutuhkan ini jika route menggunakan Route::put
            let url = id ? `/backend/category/update/${id}` : `/backend/category/store`;
            let formData = $(this).serialize();

            $.ajax({
                url: url,
                type: 'POST', // Secara teknis tetap POST, tapi kita kirim data serialize
                data: formData,
                beforeSend: function() {
                    // Disable tombol agar tidak double click
                    $('button[type="submit"]').attr('disabled', true).text('Menyimpan...');
                },
                success: function(response) {
                    $('#modalCategory').modal('hide');
                    loadCategory();
                    Swal.fire('Berhasil', 'Data berhasil disimpan', 'success');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data', 'error');
                },
                complete: function() {
                    // Aktifkan kembali tombol
                    $('button[type="submit"]').attr('disabled', false).text('Simpan');
                }
            });
        });

        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.get(`/backend/category/${id}/edit`, function(data) {
                $('#category_id').val(data.id);
                $('#name').val(data.name);
                
                // Tambahkan ini jika route update Anda adalah Route::put
                if ($('#method_field').length === 0) {
                    $('#formCategory').prepend('<input type="hidden" name="_method" id="method_field" value="PUT">');
                }
                
                $('#modalTitle').text('Edit Kategori');
                $('#modalCategory').modal('show');
            });
        });

        // Reset method saat tambah data baru
        $('#btnAddCategory').click(function() {
            $('#formCategory')[0].reset();
            $('#category_id').val('');
            $('#method_field').remove(); // Hapus method PUT
            $('#modalTitle').text('Tambah Kategori');
            $('#modalCategory').modal('show');
        });

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Hapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/backend/category/delete/${id}`,
                        type: 'DELETE',
                        success: function() {
                            loadCategory();
                            Swal.fire('Terhapus', '', 'success');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush