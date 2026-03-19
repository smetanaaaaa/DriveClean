<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;




class HomeController extends Controller
{
    public function index() {
        return view('main.index');
    }

    public function how_it_works() {
        return view('main.how_it_works');
    }

    public function social() {
        return view('main.social');
    }

}
