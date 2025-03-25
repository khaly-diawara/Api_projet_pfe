<?php

use App\Http\Controllers\Api_ouvrier_controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[Api_ouvrier_controller::class,'register']);
Route::post('/login',[Api_ouvrier_controller::class,'login']);
Route::get('/all',[Api_ouvrier_controller::class,'all']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/ouvrier',[Api_ouvrier_controller::class,'index']);
    Route::post('/ouvrier/logout',[Api_ouvrier_controller::class,'logout']);
    Route::post('/ouvrier/update',[Api_ouvrier_controller::class,'update']);
});
Route::post('/verify-login', 'AuthController@verifyLogin');
