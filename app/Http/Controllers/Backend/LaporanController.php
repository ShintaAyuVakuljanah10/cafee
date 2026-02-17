<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Transaksi;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    private function getData($request)
    {
        $query = Transaksi::where('status', 'lunas');

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('created_at', [
                $request->tanggal_awal . ' 00:00:00',
                $request->tanggal_akhir . ' 23:59:59'
            ]);
        }

        if ($request->bulan) {
            $bulan = Carbon::parse($request->bulan);
            $query->whereMonth('created_at', $bulan->month)
                  ->whereYear('created_at', $bulan->year);
        }

        return $query->orderBy('created_at','desc')->get();
    }

    public function index(Request $request)
    {
        $data = $this->getData($request);

        $total_omset = $data->sum('total');

        $rata_harian = 0;
        if ($data->count() > 0) {
            $jumlah_hari = $data->groupBy(function($item){
                return $item->created_at->format('Y-m-d');
            })->count();

            $rata_harian = $total_omset / $jumlah_hari;
        }

        return view('backend.laporan', [
            'data' => $data,
            'total_omset' => $total_omset,
            'rata_harian' => $rata_harian,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'bulan' => $request->bulan
        ]);
    }

    public function print(Request $request)
    {
        $data = $this->getData($request);
        $total_omset = $data->sum('total');

        return view('backend.laporanPrint', compact('data','total_omset'));
    }

    public function export(Request $request)
    {
        return Excel::download(new LaporanExport($request), 'laporan_penjualan.xlsx');
    }
}