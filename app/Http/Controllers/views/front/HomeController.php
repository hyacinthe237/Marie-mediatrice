<?php

namespace App\Http\Controllers\views\front;

use App\Models\Role;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function devices () {
        return view('front.devices.index');
    }

    public function home () {
        $roles = Role::select('id', 'name')->get();
        $events = Event::orderBy('date', 'asc')->get();
        $users = User::get();
        
        return view('front.home.home', compact('roles', 'events', 'users'));
    }
}
