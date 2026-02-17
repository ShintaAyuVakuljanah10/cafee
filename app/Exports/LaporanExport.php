<?php

namespace App\Exports;

use App\Models\Backend\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanExport implements FromCollection
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Transaksi::where('status','lunas');

        if ($this->request->tanggal_awal && $this->request->tanggal_akhir) {
            $query->whereBetween('created_at', [
                $this->request->tanggal_awal.' 00:00:00',
                $this->request->tanggal_akhir.' 23:59:59'
            ]);
        }

        if ($this->request->bulan) {
            $bulan = Carbon::parse($this->request->bulan);
            $query->whereMonth('created_at',$bulan->month)
                  ->whereYear('created_at',$bulan->year);
        }

        return $query->select('kode_transaksi','total','status','created_at')->get();
    }
}