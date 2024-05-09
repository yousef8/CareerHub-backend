<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

Route::get('/', function () {
    return view('welcome');
});

// Define routes for applications
Route::apiResource('application', ApplicationController::class);