<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;

class Aplikasi extends Model
{
    protected $table = 'aplikasi';

    protected $fillable = [
        'nama_aplikasi',
        'logo',
        'deskripsi',
        'alamat',
        'telepon',
        'email',
        'weekday',
        'weekend',
        'jam_weekday',
        'jam_weekend'
    ];
}
