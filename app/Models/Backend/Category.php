<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\Makanan;

class Category extends Model
{
    protected $table = 'categories'; 

    protected $fillable = ['name', 'slug'];

    public function makanans()
    {
        return $this->hasMany(Makanan::class, 'id_category', 'id');
    }
}
