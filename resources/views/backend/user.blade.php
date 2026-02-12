@extends('layouts.backend')

@section('title', 'Data User')

@section('content')

<div class="container">
    <button id="btnAddUser" class="btn btn-primary mb-3">
        + Tambah User
    </button>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h3 class="font-weight-bold mb-3">Data User</h3>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center" id="usersTable">
                        <thead classs="text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Foto</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="user-table">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalUser" tabindex="-1">
    <div class="modal-dialog">
        <form id="formUser" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="user_id"> <!-- untuk edit -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukan Nama" required>
                        <small class="text-danger error-name"></small>
                    </div>

                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukan Username" required>
                        <small class="text-danger error-username"></small>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukan Password Min 6 Karakter">
                        <small class="text-danger error-password"></small>
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role_id" id="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger error-role"></small>
                    </div>

                    <div class="mb-3">
                        <label>Foto</label>
                        <input type="file" name="foto" id="foto" class="form-control">
                        <small class="text-danger error-foto"></small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>



@endsection

@push('scripts')
<script>
let usersTable;

$(document).ready(function () {

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    usersTable = $('#usersTable').DataTable({
        pageLength: 10,
        ordering: true,
        lengthChange: true,
        searching: true,
        autoWidth: false,
        language: {
            search: "Cari",
            lengthMenu: "Tampilkan _MENU_",
            info: "_START_ - _END_ dari _TOTAL_ data",
            paginate: {
                previous: "‹",
                next: "›"
            }
        },
        columnDefs: [
            { targets: [0,5], className: 'text-center' },
            { targets: [5], orderable: false }
        ]
    });

    loadUsers();

    function loadUsers() {
        $.get("{{ route('backend.user.data') }}", function (data) {

            console.log('RESPON AJAX:', data);

            usersTable.clear();

            if (data.length === 0) {
                usersTable.row.add([
                    '',
                    'Data kosong',
                    '',
                    '',
                    '',
                    ''
                ]).draw();
                return;
            }

            $.each(data, function (i, user) {

                let foto = user.foto
                    ? `<img src="/storage/${user.foto}" width="50">`
                    : `<span class="text-muted">No Image</span>`;

                usersTable.row.add([
                    i + 1,
                    user.name,
                    user.username,
                    user.role ? user.role.name : '-',
                    foto,
                    `
                    <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-primary btn-edit" data-id="${user.id}">
                        <i class="mdi mdi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-delete" data-id="${user.id}">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
                    `
                ]);
            });

            usersTable.draw();
        });
    }

    $('#btnAddUser').click(function () {
        $('#formUser')[0].reset();
        $('.text-danger').text('');
        $('#modalTitle').text('Tambah User');
        $('#btnSave').text('Simpan');
        $('#user_id').val('');
        $('#modalUser').modal('show');
    });

    $('#formUser').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $('.text-danger').text('');
        let userId = $('#user_id').val();
        let url = userId ? `/user/${userId}` : "{{ route('backend.user.tambah') }}";
        let type = 'POST';

        if(userId){
            formData.append('_method','PUT'); 
        }

        $.ajax({
            url: url,
            type: type,
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                $('#modalUser').modal('hide');
                $('#formUser')[0].reset();
                loadUsers();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: userId 
                        ? 'User berhasil diperbarui' 
                        : 'User berhasil ditambahkan',
                    timer: 2000,
                    showConfirmButton: false
                });
            },

            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        $('.error-' + key).text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Terjadi kesalahan pada server'
                    });
                }
            }

        });
    });

    $(document).on('click', '.btn-edit', function () {
        let userId = $(this).data('id');
        // Hilangkan huruf 's' pada /users/ agar menjadi /user/
        $.get(`/user/${userId}/edit`, function (data) { 
            $('#modalTitle').text('Edit User');
            $('#btnSave').text('Update');
            $('#user_id').val(data.id);
            $('#name').val(data.name);
            $('#username').val(data.username);
            $('#role').val(data.role_id);
            $('#password').val('');
            $('.text-danger').text('');
            $('#modalUser').modal('show');
        });
    });

    $(document).on('click', '.btn-delete', function () {
        let userId = $(this).data('id');
        Swal.fire({
    title: 'Yakin?',
    text: 'User ini akan dihapus permanen!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/users/${userId}`,
                    type: 'DELETE',
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus',
                            text: 'User berhasil dihapus',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        loadUsers();
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'User gagal dihapus'
                        });
                    }
                });
            }
        });

    });

});

</script>
@endpush

