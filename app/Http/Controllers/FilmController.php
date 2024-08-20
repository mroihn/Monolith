<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\User;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class FilmController extends Controller
{
    public function index(Request $request){
        $user=null;
        if(auth()->check()){
            $user =JWTAuth::user();
        }
            
        if ((!$request->has('search'))||$request->query('search')=="") {
            $films = Film::all();
            return view('home',['filmList' => $films,'user' => $user]);
        }

        $title = $request->query('search');
        $director = $request->query('search');
        $films = Film::where('title','LIKE', "%{$title}%")->orWhere('director','LIKE', "%{$director}%")->get();
        return view('home',['filmList' => $films,'user' => $user]);
    }

    public function show($id){
        $film = Film::findOrFail($id);
        return view('detail',['film'=>$film]);
    }
}
