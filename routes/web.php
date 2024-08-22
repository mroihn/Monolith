<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilmController;
use Illuminate\Support\Facades\Route;


Route::middleware(['jwt_auth'])->group(function () {
    Route::get('/my_list',[FilmController::class,'my_list']);
    Route::post('/buy/{id}',[FilmController::class,'buy']);
    Route::get('/logout',[AuthController::class,'logout']);
});

Route::get('/',[FilmController::class,'index']);

Route::get('/film/{id}',[FilmController::class,'show']);

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post(
    '/login',
    [AuthController::class,'login']
)->name('post_login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post(
    '/process_register',
    [AuthController::class,'register']
)->name('post_register');



