<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller{
    public function createLoginForm()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        // Handle login logic, e.g., validation and authentication
        //Some authentication
        return redirect()->route('menuPage');
    }
    public function logout()
    {
        // Handle logout logic here
    }
}
