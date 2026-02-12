<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\SubMakanan;
use App\Models\Backend\Category;

class Makanan extends Model
{
    protected $table = 'makanans';
    protected $primaryKey = 'id_makanan';

    protected $fillable = [
        'id_category',
        'nama',
        'harga',
        'deskripsi',
        'gambar',
        'status'
    ];

    public function subMakanans()
    {
        return $this->hasMany(SubMakanan::class, 'id_makanan', 'id_makanan');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }
}