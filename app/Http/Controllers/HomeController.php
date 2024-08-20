<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $user = User::all()->first();
        $film = Film::all();
        return view('home',['filmList' => $film,'user' => $user]);
    }
}
