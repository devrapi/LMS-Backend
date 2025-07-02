<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\TeamController;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use phpDocumentor\Reflection\Types\Resource_;

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

//categories
Route::apiResource('/categories' , CategoriesController::class);

//division
Route::apiResource('/division' , DivisionController::class);

//teams
Route::apiResource('/teams' , TeamController::class);

