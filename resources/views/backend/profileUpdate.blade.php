@extends('layouts.backend')

@section('title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold">Update Profil</h5>
            </div>
            <div class="card-body">
                <form id="formProfile" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="text-center mb-4">
                        <img src="{{ $user->foto ? asset('storage/'.$user->foto) : asset('assets/images/no-image.png') }}" 
                             class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                    </div>

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                        <small class="text-muted text-italic">*Minimal 6 karakter</small>
                    </div>

                    <div class="form-group">
                        <label>Ganti Foto</label>
                        <input type="file" name="foto" class="form-control">
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#formProfile').submit(function(e) {
        e.preventDefault(); 
        let formData = new FormData(this);
        
        // Tembak langsung ke route profile update
        let url = "{{ route('backend.user.profile.update') }}";

        $.ajax({
            url: url,
            type: 'POST', // Gunakan POST
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if(res.success) {
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Berhasil', 
                        text: res.message,
                        timer: 1500, 
                        showConfirmButton: false 
                    }).then(() => {
                        location.reload(); // Penting agar foto di navbar ikut update
                    });
                }
            },
            error: function (xhr) {
                // Jika validasi gagal (error 422)
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let pesan = Object.values(errors).flat().join("<br>");
                    Swal.fire('Gagal', pesan, 'error');
                } else {
                    Swal.fire('Error', 'Terjadi kesalahan pada server (Error 500)', 'error');
                }
            }
        });
    });
</script>
@endpush