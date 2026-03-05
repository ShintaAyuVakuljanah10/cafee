@extends('layouts.backend')

@section('title', 'Dashboard')

@section('content')

<h3 class="font-weight-bold mb-4">
    Welcome {{ auth()->user()->name ?? 'Admin' }}
</h3>

<div class="row">

    {{-- Omset Hari Ini --}}
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h5>Omset Hari Ini</h5>
                <h2 class="text-success">
                    Rp {{ number_format($omsetHariIni,0,',','.') }}
                </h2>
            </div>
        </div>
    </div>

    {{-- Total Pesanan --}}
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h5>Total Pesanan Hari Ini</h5>
                <h2 class="text-primary">
                    {{ $pesananHariIni }} Pesanan
                </h2>
            </div>
        </div>
    </div>

</div>

{{-- Chart --}}
<div class="card mt-4 shadow">
    <div class="card-body">
        <h5>Omset 7 Hari Terakhir</h5>
        <canvas id="chartOmset"></canvas>
    </div>
</div>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        const ctx = document.getElementById('chartOmset');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Omset',
                    data: {!! json_encode($data) !!},
                    borderWidth: 2,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0,200,0,0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins:{
                    legend:{
                        display:true
                    }
                }
            }
        });

    </script>
@endpush
