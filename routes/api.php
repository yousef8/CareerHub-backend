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
use App\Http\Middleware\OnlyEmployer;

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::post('user/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return response()->json($request->user());
    });
    Route::get('user/me', function (Request $request) {
        return response()->json($request->user());
    });
});

Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', OnlyAdmin::class]);

Route::apiResource('skills', SkillController::class)->middleware('auth:sanctum');

Route::apiResource('industries', IndustryController::class)->middleware('auth:sanctum');

Route::apiResource('applications', ApplicationController::class)->middleware('auth:sanctum');

// unprotected routes
Route::get('jobs', [JobPostController::class, 'index']);
Route::get('jobs/search', [JobPostController::class, 'search']);
Route::get('jobs/{id}', [JobPostController::class, 'show']);

// protected routes
Route::get('jobs/unApproved', [JobPostController::class, 'unApproved'])->middleware(['auth:sanctum', OnlyAdmin::class]);
Route::put('jobs/approve/{id}', [JobPostController::class, 'approve'])->middleware(['auth:sanctum', OnlyAdmin::class]);
Route::post('jobs', [JobPostController::class, 'store'])->middleware(['auth:sanctum', OnlyEmployer::class]);
Route::delete('jobs/{id}', [JobPostController::class, 'destroy'])->middleware(['auth:sanctum', OnlyEmployer::class]);
Route::put('jobs/{id}', [JobPostController::class, 'update'])->middleware(['auth:sanctum', OnlyEmployer::class]);

