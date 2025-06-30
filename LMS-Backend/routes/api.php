<?php

use App\Http\Controllers\Api\AdminController;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// TEST POSTMAN & BACKEND API
Route::get('ping', function () {
    return response()->json(['message' => 'API is working!']);
});

//Open Routes
Route::post('register', [AdminController::class , 'register']);
Route::post('login', [AdminController::class , 'login']);