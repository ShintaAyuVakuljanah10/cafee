<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\backend\Aplikasi;
use Illuminate\Http\Request;

class PageController extends Controller 
{
    public function index()
    {
        $app = Aplikasi::first();

        return view('layouts.frontend', compact('app'));
    }
}
