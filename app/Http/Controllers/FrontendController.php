<?php

namespace App\Http\Controllers;

class FrontendController extends Controller
{
    // Admin application
    public function admin(){
        // TODO: whitelist
        return view('admin');
    }

    // Public application
    public function app(){
        return view('app');
    }
}
