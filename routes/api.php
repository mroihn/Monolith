<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\FilmApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    
    Route::get('/self',[AuthApiController::class,'self']);
    Route::get('/logout',[AuthApiController::class,'logout']);
    Route::get('/users',[UserApiController::class,'getUserByUsername']);
    Route::get('/users/{id}',[UserApiController::class,'getUserById']);
    Route::post('/users/{id}/balance',[UserApiController::class,'updateBalance']);
    Route::delete('/users/{id}',[UserApiController::class,'delete']);
    Route::post('/films',[FilmApiController::class,'create']);
    Route::get('/films',[FilmApiController::class,'getFilms']);
    Route::get('/films/{id}',[FilmApiController::class,'getFilmById']);
    Route::put('/films/{id}',[FilmApiController::class,'update']);
    Route::delete('/films/{id}',[FilmApiController::class,'delete']);
    
});

Route::post('/login',[AuthApiController::class,'login']);
