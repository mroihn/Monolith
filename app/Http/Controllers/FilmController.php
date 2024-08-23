<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class FilmController extends Controller
{
    public function index(Request $request){
        $user=null;
        $last_added= Film::orderBy('created_at', 'desc')->limit(3)->get();
        if(auth()->check()){
            $user =JWTAuth::user();
        }

        $search = $request->search;

        $films = Film::where('title','LIKE', "%{$search}%")
                       ->orWhere('director','LIKE', "%{$search}%")
                       ->orWhere('release_year','LIKE', "%{$search}%")
                       ->orWhere('genres','LIKE', "%{$search}%")
                       ->paginate(15);
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
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $items = Purchase::with('films')->where('user_id',$user_id)->get()->pluck('films')->flatten();
        $currentItems = $items->slice($perPage * ($currentPage - 1), $perPage);
        $films = new LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage);
        $last_added= Film::orderBy('created_at', 'desc')->limit(3)->get();
        return view('home',['filmList'=>$films,'user' => $user,'last_added'=>$last_added]);
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
