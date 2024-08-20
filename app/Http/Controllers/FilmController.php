<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Purchase;
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
        $user=null;
        if(auth()->check()){
            $user =JWTAuth::user();
        }
        return view('detail',['film'=>$film,'user' => $user]);
    }

    public function my_list($id){
        $user =JWTAuth::user();
        $user_id = $user->id;
        $film = Purchase::with('films')->where('user_id',$user_id)->first();
        // $film = Purchase::with('users')->get();
        return response()->json($film->films->title);
        return view('home',['filmList'=>$film,'user' => $user]);
    }
}
