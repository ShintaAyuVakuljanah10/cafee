<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\Transaksi;

class TransaksiDetail extends Model
{
    protected $table = 'transaksi_details';

    protected $fillable = [
        'transaksi_id',
        'nama_produk',
        'harga',
        'qty',
        'subtotal'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}