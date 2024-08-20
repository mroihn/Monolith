<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\User;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index(Request $request){
         return response()->json(auth()->check());
        $user = null;
        if(auth()->check()){
            $user = auth->user();
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
