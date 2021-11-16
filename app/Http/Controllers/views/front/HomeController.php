<?php

namespace App\Http\Controllers\views\front;

// use App\Models\Role;
// use App\Models\Event;
// use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home () {
        return view('front.home.home');
    }
}
