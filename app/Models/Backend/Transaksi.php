<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\TransaksiDetail;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'nama_customer',
        'no_meja',
        'total',
        'status',
        'bayar',
        'kembali'
    ];

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }
    public function meja()
    {
        return $this->belongsTo(\App\Models\Backend\Meja::class, 'id_meja');
    }
    
}