<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_role')->orderBy('sort_order', 'asc');
    }

    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
