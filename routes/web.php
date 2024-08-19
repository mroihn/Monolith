<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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


Route::get('/token', function () {
    return csrf_token(); 
});

// Route::get('/register', [RegisterController::class, 'showRegisterForm'])
//   ->name('register');
// Route::post('/register', [RegisterController::class, 'register'])
//   ->name('register.post');


