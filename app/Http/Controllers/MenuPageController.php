<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuPageController extends Controller
{
    public function create(){
        return view('menu');
    }
}
