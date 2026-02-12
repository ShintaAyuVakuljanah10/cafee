<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\Makanan;

class SubMakanan extends Model
{
    protected $table = 'sub_makanans';
    protected $primaryKey = 'id_sub_makanan';

    protected $fillable = [
        'id_makanan',
        'nama',
        'tambahan_harga'
    ];

    public function makanan()
    {
        return $this->belongsTo(Makanan::class, 'id_makanan', 'id_makanan');
    }
}
