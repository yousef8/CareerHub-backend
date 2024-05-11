<?php

use App\Http\Controllers\JobPostSkillController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndustryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Middleware\OnlyAdmin;

Route::post('login', [LoginController::class, 'login']);

Route::post('register', [RegisterController::class, 'register']);

Route::post('user/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', OnlyAdmin::class]);

Route::apiResource('skills', SkillController::class)->middleware('auth:sanctum');

Route::apiResource('industries', IndustryController::class)->middleware('auth:sanctum');

Route::apiResource('applications', ApplicationController::class)->middleware('auth:sanctum');

Route::get('jobs', [JobPostController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('jobs', JobPostController::class)->except(['index']);
});
