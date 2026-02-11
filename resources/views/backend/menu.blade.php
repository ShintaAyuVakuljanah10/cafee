@extends('layouts.backend')

@section('title', 'Menu')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Menu Management</h4>

        <button class="btn btn-success" data-toggle="modal" data-target="#modalMenu">
            <i class="mdi mdi-plus"></i>
        </button>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle" id="menuTable">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Menu</th>
                        <th>Icon</th>
                        <th>Route</th>
                        <th>Active</th>
                        <th>Order</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- <tr>
                        <td colspan="7" class="text-center">Loading...</td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


<div class="modal fade" id="modalMenu" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-center">
        <form id="formMenu">
            @csrf
            <input type="hidden" id="menu_id">

            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Menu</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Nama Menu</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold">Icon</label>
                            <select id="icon" name="icon" class="form-control">

                                <option value="">-- Select Icon --</option>

                                <!-- DASHBOARD -->
                                <option value="mdi mdi-view-dashboard">📊 Dashboard</option>
                                <option value="mdi mdi-home">🏠 Home</option>

                                <!-- CONTENT -->
                                <option value="mdi mdi-file-document">📄 Pages</option>
                                <option value="mdi mdi-post">📝 Post</option>
                                <option value="mdi mdi-folder">📁 Category</option>
                                <option value="mdi mdi-tag">🏷️ Tag</option>

                                <!-- USER -->
                                <option value="mdi mdi-account">👤 User</option>
                                <option value="mdi mdi-account-group">👥 Users</option>
                                <option value="mdi mdi-shield-account">🛡️ Role</option>

                                <!-- SETTINGS -->
                                <option value="mdi mdi-cog">⚙️ Settings</option>
                                <option value="mdi mdi-cogs">⚙️ Advanced Settings</option>
                                <option value="mdi mdi-tools">🛠️ Tools</option>

                                <!-- MEDIA -->
                                <option value="mdi mdi-image">🖼️ Media</option>
                                <option value="mdi mdi-image-multiple">🖼️ Gallery</option>
                                <option value="mdi mdi-file-upload">⬆️ Upload</option>

                                <!-- NAVIGATION -->
                                <option value="mdi mdi-menu">📋 Menu</option>
                                <option value="mdi mdi-menu-open">📂 Sub Menu</option>

                                <!-- COMMUNICATION -->
                                <option value="mdi mdi-email">✉️ Email</option>
                                <option value="mdi mdi-chat">💬 Chat</option>
                                <option value="mdi mdi-bell">🔔 Notification</option>

                                <!-- DATE & TIME -->
                                <option value="mdi mdi-calendar">📅 Calendar</option>
                                <option value="mdi mdi-clock-outline">⏰ Time</option>

                                <!-- SECURITY -->
                                <option value="mdi mdi-lock">🔒 Security</option>
                                <option value="mdi mdi-lock-open">🔓 Unlock</option>

                                <!-- SYSTEM -->
                                <option value="mdi mdi-database">🗄️ Database</option>
                                <option value="mdi mdi-server">🖥️ Server</option>
                                <option value="mdi mdi-logout">🚪 Logout</option>

                            </select>
                        </div>


                        <div class="col-md-6">
                            <label class="fw-semibold">Route</label>
                            <select id="route" name="route" class="form-control" style="width:100%"></select>
                        </div>                        

                        <div class="col-md-6 mt-2">
                            <label class="fw-semibold d-block mb-2">&nbsp;</label>

                            <div class="d-flex gap-4 align-items-center">
                                <label class="d-flex align-items-center gap-2 mb-0">
                                    <input type="checkbox" id="active" checked>
                                    <span class="fw-semibold">Active</span>
                                </label>

                                <label class="d-flex align-items-center gap-2 mb-0">
                                    <input type="checkbox" id="is_submenu">
                                    <span class="fw-semibold">Sub Menu</span>
                                </label>
                            </div>
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
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        loadMenu();
        let table;

        $(document).ready(function () {

            table = $('#menuTable').DataTable({
                pageLength: 10,
                ordering: true,
                lengthChange: true,
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
                columnDefs: [{
                        targets: [0, 2, 4, 5, 6],
                        className: 'text-center'
                    },
                    {
                        targets: [6],
                        orderable: false
                    }
                ]
            });


            loadMenu();
        });

        function loadMenu() {
            $.get("{{ route('backend.menu.data') }}", function (data) {

                table.clear();

                data.forEach((menu, i) => {
                    table.row.add([
                        i + 1,
                        menu.name,
                        `<i class="${menu.icon}"></i>`,
                        menu.route ? menu.route : '-',
                        menu.active ?
                        '<span class="badge badge-success">Yes</span>' :
                        '<span class="badge badge-secondary">No</span>',
                        menu.sort_order,
                        `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-secondary up" data-id="${menu.id}" title="Naik">
                                <i class="mdi mdi-arrow-up"></i>
                            </button>
                            <button class="btn btn-outline-secondary down" data-id="${menu.id}" title="Turun">
                                <i class="mdi mdi-arrow-down"></i>
                            </button>
                            <button class="btn btn-outline-primary btn-edit" data-id="${menu.id}" title="Edit">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-delete" data-id="${menu.id}" title="Hapus">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                `
                    ]);
                });

                table.draw();
            });
        }
        $(document).ready(function () {
            loadMenu();
        });

        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');

            $.get(`/backend/menu/${id}`, function (data) {
                $('#menu_id').val(data.id);
                $('#name').val(data.name);
                $('#icon').val(data.icon);
                $('#route').val(data.route);
                $('#active').prop('checked', data.active == 1);

                $('.modal-title').text('Edit Menu');
                $('#modalMenu').modal('show');
            });
        });

        $(document).on('click', '.btn-delete', function () {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Yakin?',
                text: 'Menu yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/backend/menu/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus',
                                text: 'Menu berhasil dihapus',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadMenu();
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Menu gagal dihapus'
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.up', function () {
            let id = $(this).data('id');

            $.post(`/backend/menu/${id}/up`, function () {
                loadMenu();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Urutan menu diperbarui',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        });

        $(document).on('click', '.down', function () {
            let id = $(this).data('id');

            $.post(`/backend/menu/${id}/down`, function () {
                loadMenu();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Urutan menu diperbarui',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        });

        $(document).ready(function () {

            function toggleRouteInput() {
                if ($('#is_submenu').is(':checked')) {
                    $('#route').val('').prop('disabled', true);
                } else {
                    $('#route').prop('disabled', false);
                }
            }

            $('#modalMenu').on('shown.bs.modal', function () {
                toggleRouteInput();
            });

            $('#is_submenu').on('change', function () {
                toggleRouteInput();
            });

        });

        $('#formMenu').submit(function (e) {
            e.preventDefault();

            let id = $('#menu_id').val();
            let url = id ? `/backend/menu/${id}` : `/backend/menu`;

            let data = {
                name: $('#name').val(),
                icon: $('#icon').val(),
                route: $('#route').val(),
                active: $('#active').is(':checked') ? 1 : 0,
                is_submenu: $('#is_submenu').is(':checked') ? 1 : 0,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            if (id) data._method = 'PUT';

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function () {
                    $('#modalMenu').modal('hide');
                    $('#formMenu')[0].reset();
                    $('#active').prop('checked', true);
                    loadMenu();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: id ?
                            'Menu berhasil diperbarui' :
                            'Menu berhasil ditambahkan',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    setTimeout(function () {
                        location.reload();
                    }, 100);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let message = '';

                        Object.keys(errors).forEach(function (key) {
                            message += errors[key][0] + '\n';
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi gagal',
                            text: message
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

        $('#route').select2({
            placeholder: 'Pilih Route',
            allowClear: true,
            ajax: {
                url: "{{ route('backend.menu.routeSelect') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                }
            }
        });
    });

</script>
@endpush
