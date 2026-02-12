<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\BackEnd\Menu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.backend', function ($view) {

        
        $menus = Menu::with('submenus')
            ->where('active', 1)
            ->whereHas('roles', function ($q) {
                $q->where('roles.id', Auth::user()->role_id);
            })
            ->orderBy('sort_order')
            ->get();

        $view->with('menus', $menus);
    });
    }
}
