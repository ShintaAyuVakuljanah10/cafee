<?php

namespace App\Models\BackEnd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'icon',
        'route',
        'is_submenu',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];
    

    public function submenus()
    {
        return $this->hasMany(SubMenu::class, 'parent_id')->where('active', 1)
        ->orderBy('sort_order');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role');
    }

    public static function routeSelect($search = null)
    {
        return collect(Route::getRoutes())
            ->map(fn ($route) => $route->getName())
            ->filter(function ($name) use ($search) {
                if (!$name) return false;

                if ($search) {
                    return str_contains(strtolower($name), strtolower($search));
                }

                return true;
            })
            ->values()
            ->map(fn ($name) => [
                'id'   => $name,
                'text' => $name
            ]);
    }
}
