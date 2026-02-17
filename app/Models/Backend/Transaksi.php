<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\TransaksiDetail;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'total',
        'status'
    ];

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }
}