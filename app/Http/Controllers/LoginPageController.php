<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginPageController extends Controller{
    public function create()
    {
        return view('login');
    }
}
