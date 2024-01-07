<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('v1.pages.home', [
            'title' => 'home'
        ]);
    }
}
