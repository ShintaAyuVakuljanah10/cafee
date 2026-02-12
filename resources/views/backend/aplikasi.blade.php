@extends('layouts.backend')

@section('title', 'Pengaturan Aplikasi')

@section('content')

<form id="formSetting"
      action="{{ route('backend.aplikasi.update') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    <div class="card-body">

        <h4 class="mb-4">Pengaturan Aplikasi</h4>

        {{-- ROW 1 --}}
        <div class="row mb-4">

            <div class="col-md-4">
                <label class="form-label">Nama Aplikasi</label>
                <input type="text" name="nama_aplikasi" class="form-control"
                    value="{{ $aplikasi->nama_aplikasi ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Gambar</label>

                <div class="text-center">
                    <img id="previewGambar"
                        src="{{ $aplikasi->logo ? asset('storage/'.$aplikasi->logo) : '' }}"
                        style="max-height:120px; {{ empty($aplikasi->logo) ? 'display:none;' : '' }}">

                    <input type="hidden" name="logo" id="gambar"
                        value="{{ old('logo', $aplikasi->logo ?? '') }}">

                    <div class="mt-2">
                        <button type="button"
                            class="btn btn-secondary btn-sm"
                            onclick="openFileManager()">
                            Pilih dari File Manager
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- Deskripsi --}}
        <div class="mb-4">
            <label class="form-label">Deskripsi Judul</label>
            <textarea name="deskripsi" class="form-control" rows="2">{{ $aplikasi->deskripsi ?? '' }}</textarea>
        </div>

        {{-- ROW EMAIL + HP --}}
        <div class="row mb-4">

            <div class="col-md-6">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control"
                    value="{{ $aplikasi->email ?? '' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Nomor HP</label>
                <input type="text" name="telepon" class="form-control"
                    value="{{ $aplikasi->telepon ?? '' }}">
            </div>

        </div>

        {{-- Alamat --}}
        <div class="mb-4">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2">{{ $aplikasi->alamat ?? '' }}</textarea>
        </div>
        
        <hr>

        <h5 class="mb-3">Jam Operasional</h5>


        {{-- Weekday --}}
        <div class="row">

            {{-- Weekday --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Jam Operasional Weekday</label>
                <input type="text" name="weekday" class="form-control"
                    placeholder="Contoh: Senin - Sabtu 09:00 - 21:00"
                    value="{{ $aplikasi->weekday ?? '' }}">
            </div>

            {{-- Weekend --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Jam Operasional Weekend</label>
                <input type="text" name="weekend" class="form-control"
                    placeholder="Contoh: Minggu 10:00 - 20:00"
                    value="{{ $aplikasi->weekend ?? '' }}">
            </div>

        </div>


        <div class="text-end">
            <button class="btn btn-primary px-4">
                Simpan
            </button>
        </div>

    </div>

    <div class="modal fade" id="fileManagerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pilih Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row" id="fileManagerList"></div>
            </div>

        </div>
    </div>
</div>

</form>
@endsection
@push('scripts')
<script>

function openFileManager(){

    let modal = new bootstrap.Modal(document.getElementById('fileManagerModal'));
    modal.show();

    $.get("{{ route('backend.fileManager.data') }}", function(data){

        let html = '';

        data.forEach(file => {

            html += `
                <div class="col-md-3 mb-3 text-center">
                    <img src="/storage/${file.gambar}"
                        class="img-thumbnail"
                        style="cursor:pointer"
                        onclick="pilihGambar('${file.gambar}')">
                </div>
            `;
        });

        $('#fileManagerList').html(html);
    });
}

function pilihGambar(gambar){

    $('#gambar').val(gambar);

    $('#previewGambar')
        .attr('src','/storage/'+gambar)
        .show();

    bootstrap.Modal.getInstance(
        document.getElementById('fileManagerModal')
    ).hide();
}


$('#formSetting').submit(function(e){

    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    $.ajax({
        url: $(form).attr('action'),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        beforeSend: function(){
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        },

        success: function(response){
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message ?? 'Data berhasil disimpan',
                timer: 2000,
                showConfirmButton: false
            });
        },

        error: function(xhr){

            let errors = xhr.responseJSON?.errors;
            let message = 'Terjadi kesalahan';

            if(errors){
                message = Object.values(errors).map(e => e[0]).join('\n');
            }

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: message
            });
        }

    });

});

</script>
@endpush


