@extends('layouts.backend')

@section('title', 'File Manager')

@section('content')
<div class="container">

    <button class="btn btn-primary mb-3" id="btnAdd">
        + Upload Gambar
    </button>

    <div class="row" id="file-grid">
        <div class="col-12 text-center">Loading...</div>
    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalFile">
    <div class="modal-dialog">
        <form id="formFile" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Upload Gambar</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control" required>
                        <small class="text-danger error-judul"></small>
                    </div>

                    <div class="mb-3">
                        <label>Gambar</label>
                        <input type="file" name="gambar" class="form-control" required>
                        <small class="text-danger error-gambar"></small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        loadFiles();

        function loadFiles() {
            $.get("{{ route('backend.fileManager.data') }}", function (data) {
                let html = '';
                if (data.length === 0) {
                    html = '<div class="col-12 text-center">Belum ada gambar</div>';
                } else {
                    $.each(data, function (i, item) {
                        html += `
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="/storage/${item.gambar}" class="card-img-top" style="height:250px;object-fit:cover">
                            <div class="card-body text-center">
                                <small>${item.judul}</small><br>
                                <button class="btn btn-danger btn-sm mt-2 delete" data-id="${item.id}">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>`;
                    });
                }
                $('#file-grid').html(html);
            });
        }

        $('#btnAdd').click(function () {
            $('#formFile')[0].reset();
            $('.text-danger').text('');
            $('#modalFile').modal('show');
        });

        $('#formFile').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('backend.fileManager.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    $('#modalFile').modal('hide');
                    loadFiles();

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Upload berhasil',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },

                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.error-judul').text(errors.judul ?? '');
                        $('.error-gambar').text(errors.gambar ?? '');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan pada server'
                        });
                    }
                }

            });
        });


        $(document).on('click', '.delete', function () {

            let id = $(this).data('id');

            Swal.fire({
                title: 'Yakin?',
                text: 'Gambar ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('backend.fileManager.destroy', ':id') }}"
                            .replace(':id', id),
                        type: 'DELETE',
                        success: function () {
                            loadFiles();
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                            Swal.fire('Error', 'Gagal menghapus', 'error');
                        }
                    });

                }

            });
        });

    });
</script>
@endpush
