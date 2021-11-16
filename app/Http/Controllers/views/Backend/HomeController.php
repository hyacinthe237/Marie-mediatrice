<?php

namespace App\Http\Controllers\Views\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{


    /**
     * Dasboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function dashboard ()
    {
        return view('backend.home.dashboard');
    }

    /**
     * List users
     * @return View
     */
    public function users ()
    {
        return view('backend.home.users');
    }

    /**
     * File Manager
     * @return View
     */
    public function files ()
    {
        return view('backend.files.files');
    }
}
