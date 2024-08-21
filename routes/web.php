<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/',[FilmController::class,'index']);
Route::get('/film/{id}',[FilmController::class,'show']);
Route::get('/my_list',[FilmController::class,'my_list'])->middleware('jwt_auth');
Route::get('film/buy/{id}',[FilmController::class,'buy'])->middleware('jwt_auth');

Route::get('/logout',[AuthController::class,'logout']);

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



