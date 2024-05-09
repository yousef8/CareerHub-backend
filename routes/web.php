<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\JobPostController;

Route::get('/token', function () {
    return csrf_token();
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkAuth', function () {
    if (auth()->check()) {
        return response()->json(['message' => 'Authenticated']);
    }
    return response()->json(['message' => 'Not authenticated']);
    
});

// Define routes for applications
Route::apiResource('application', ApplicationController::class);


