@extends('layouts.backend') 
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Daftar Meja & QR Code</div>
        <div class="card-body">
            <form action="{{ route('backend.meja.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="input-group w-50">
                    <input type="text" name="nomor_meja" class="form-control" placeholder="Contoh: 01" required>
                    <button type="submit" class="btn btn-primary">Tambah Meja</button>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>No Meja</th>
                        <th>URL Unik (UUID)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mejas as $m)
                    <tr>
                        <td>Meja {{ $m->nomor_meja }}</td>
                        <td><small>{{ $m->uuid }}</small></td>
                        <td>
                            <a href="{{ route('backend.meja.print', $m->id) }}" class="btn btn-sm btn-info" target="_blank">
                                <i class="fe fe-printer"></i> Print QR
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection