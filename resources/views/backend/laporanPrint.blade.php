<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan</title>
    <style>
        body { font-family: Arial; }
        table { width:100%; border-collapse: collapse; }
        table, th, td { border:1px solid black; }
        th, td { padding:8px; }
    </style>
</head>
<body onload="window.print()">

<h3>Laporan Penjualan</h3>

<table>
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
        @foreach($data as $i => $row)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $row->kode }}</td>
            <td>{{ $row->created_at }}</td>
            <td>Rp {{ number_format($row->total,0,',','.') }}</td>
            <td>{{ $row->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h4>Total Omset: Rp {{ number_format($total_omset,0,',','.') }}</h4>

</body>
</html>