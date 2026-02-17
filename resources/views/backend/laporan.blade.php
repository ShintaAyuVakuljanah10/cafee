@extends('layouts.backend')

@section('content')

<div class="container">

    <h4 class="mb-4">Laporan Penjualan</h4>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('backend.laporan.index') }}" class="row g-3 mb-4">

        <div class="col-md-3">
            <label>Tanggal Awal</label>
            <input type="date" name="tanggal_awal" value="{{ $tanggal_awal }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label>Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" value="{{ $tanggal_akhir }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label>Filter Bulan</label>
            <input type="month" name="bulan" value="{{ $bulan }}" class="form-control">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

    </form>

    {{-- RINGKASAN --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h6>Total Omset</h6>
                <h4>Rp {{ number_format($total_omset,0,',','.') }}</h4>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h6>Rata-rata Penjualan / Hari</h6>
                <h4>Rp {{ number_format($rata_harian,0,',','.') }}</h4>
            </div>
        </div>
    </div>

    {{-- TABEL DATATABLE --}}
    <div class="card">
        <div class="card-body">

            <table class="table table-bordered" id="tableLaporan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $row->kode }}</td>
                            <td>{{ $row->created_at->format('d-m-Y H:i') }}</td>
                            <td>Rp {{ number_format($row->total,0,',','.') }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ strtoupper($row->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">

                <a href="{{ route('backend.laporan.print', request()->query()) }}"
                   target="_blank"
                   class="btn btn-secondary">
                    Print Preview
                </a>
            
                <a href="{{ route('backend.laporan.export', request()->query()) }}"
                   class="btn btn-success">
                    Export Excel
                </a>
            
            </div>

        </div>
    </div>

</div>

@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        $('#tableLaporan').DataTable({
            "pageLength": 10,
            "order": [[2, "desc"]],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Berikutnya"
                }
            }
        });
    });
</script>
@endpush