<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Backend\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Omset hari ini
        $omsetHariIni = Transaksi::whereDate('created_at', Carbon::today())
                        ->sum('total');

        // Total pesanan hari ini
        $pesananHariIni = Transaksi::whereDate('created_at', Carbon::today())
                        ->count();

        // Omset 7 hari terakhir
        $chart = Transaksi::select(
                    DB::raw('DATE(created_at) as tanggal'),
                    DB::raw('SUM(total) as omset')
                )
                ->whereDate('created_at', '>=', Carbon::now()->subDays(6))
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();

        $labels = $chart->pluck('tanggal');
        $data = $chart->pluck('omset');

        return view('backend.home', compact(
            'omsetHariIni',
            'pesananHariIni',
            'labels',
            'data',
            'user'
        ));
    }
    // public function index()
    // {
    //     $user = Auth::user();
    //     return view('backend.home', compact('user'));
    // }

}
