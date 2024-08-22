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
        $last_added= Film::orderBy('created_at', 'desc')->limit(3)->get();
        if(auth()->check()){
            $user =JWTAuth::user();
        }
            
        if ((!$request->has('search'))||$request->query('search')=="") {
            $films = Film::all();
            return view('home',['filmList' => $films,'user' => $user,'last_added'=>$last_added]);
        }

        $title = $request->query('search');
        $director = $request->query('search');
        $films = Film::where('title','LIKE', "%{$title}%")->orWhere('director','LIKE', "%{$director}%")->get();
        
        return view('home',['filmList' => $films,'user' => $user,'last_added'=>$last_added]);
    }

    public function show($id){
        $film = Film::findOrFail($id);
        $user=null;
        $purchase=[];
        if(auth()->check()){
            $user =JWTAuth::user();
            $purchase=Purchase::with('films')->where('user_id',$user->id)->where('film_id',$film->id)->get();
        }

        return view('detail',['film'=>$film,'user' => $user,'purchase'=>$purchase]);
    }

    public function my_list(){
        $user =JWTAuth::user();
        $user_id = $user->id;
        $film = Purchase::with('films')->where('user_id',$user_id)->get()->pluck('films');
        $last_added= Film::orderBy('created_at', 'desc')->limit(3)->get();

        return view('home',['filmList'=>$film,'user' => $user,'last_added'=>$last_added]);
    }

    public function buy($id){
        $film= Film::find($id);
        $user =JWTAuth::user();

        if($user->balance >= $film->price){
            
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'film_id' => $film->id,
            ]);
            
            $user->balance -= $film->price;
            $user->save();
        }
        return redirect()->back();
    }
}
