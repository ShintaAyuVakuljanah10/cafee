<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meja extends Model
{
    protected $fillable = ['nomor_meja', 'uuid', 'status'];

    // Otomatis buat UUID saat tambah meja baru
    protected static function booted()
    {
        static::creating(function ($meja) {
            $meja->uuid = (string) Str::uuid();
        });
    }
}
