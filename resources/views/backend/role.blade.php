@extends('layouts.backend')

@section('title', 'Role Management')

@section('content')
<div class="container">

    <button id="btnAddRole" class="btn btn-primary mb-3">
        + Tambah Role
    </button>

    <div class="card">
        <div class="card-body">
            <h3 class="font-weight-bold mb-3">Data Role</h3>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center" id="rolesTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Role</th>
                            <th>Menu</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRole">
    <div class="modal-dialog">
        <form id="formRole">
            @csrf
            <input type="hidden" id="role_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTitle">Tambah Role</h5>
                </div>

                <div class="modal-body">
                    <input type="text" id="name" name="name" class="form-control mb-3" placeholder="Nama Role">

                    <label class="fw-bold mb-2 d-block">Menu Akses</label>

                    <div class="card border">
                        <div class="card-body p-3">
                            <div class="row">
                                @foreach ($menus as $menu)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input
                                                type="checkbox"
                                                class="form-check-input menu-checkbox"
                                                name="menus[]"
                                                value="{{ $menu->id }}"
                                                id="menu{{ $menu->id }}"
                                            >
                                            <label class="form-check-label" for="menu{{ $menu->id }}">
                                                {{ $menu->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let rolesTable;
    $(document).ready(function () {

        rolesTable = $('#rolesTable').DataTable({
            ordering: true,
            searching: true,
            columnDefs: [
                { targets: [0,3], className: 'text-center' },
                { targets: [3], orderable: false }
            ]
        });

        loadRoles();

        function loadRoles() {
            $.get("{{ route('roles.data') }}", function (data) {

                rolesTable.clear();

                if (data.length === 0) {
                    rolesTable.row.add(['','Data kosong','','']).draw();
                    return;
                }

                $.each(data, function (i, role) {

                    let menus = '';
                    role.menus.forEach(menu => {
                        menus += `<span class="badge bg-info me-1">${menu.name}</span>`;
                    });

                    rolesTable.row.add([
                        i + 1,
                        role.name,
                        menus,
                        `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-edit" data-id="${role.id}">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-delete" data-id="${role.id}">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                        `
                    ]);
                });

                rolesTable.draw();
            });
        }

        $('#btnAddRole').click(function () {
            $('#formRole')[0].reset();
            $('#role_id').val('');
            $('.menu-checkbox').prop('checked', false);
            $('#modalTitle').text('Tambah Role');
            $('#modalRole').modal('show');
        });

        $('#formRole').submit(function (e) {
            e.preventDefault();

            let id = $('#role_id').val();
            let url = id ? `/roles/update/${id}` : `/roles/store`;

            $.post(url, $(this).serialize(), function () {
                $('#modalRole').modal('hide');
                loadRoles();
            });
        });

        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');

            $.get(`/roles/${id}/edit`, function (res) {
                $('#role_id').val(res.id);
                $('#name').val(res.name);

                $('.menu-checkbox').prop('checked', false);
                res.menus.forEach(menu => {
                    $('.menu-checkbox[value="' + menu.id + '"]').prop('checked', true);
                });

                $('#modalTitle').text('Edit Role');
                $('#modalRole').modal('show');
            });
        });

        $(document).on('click', '.btn-delete', function () {
            let id = $(this).data('id');

            if (!confirm('Hapus role?')) return;

            $.ajax({
                url: `/roles/delete/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function () {
                    loadRoles();
                }
            });
        });

    });
</script>
@endpush
