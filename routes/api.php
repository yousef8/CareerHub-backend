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
use App\Http\Middleware\OnlyCandidate;

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

// Route::apiResource('applications', ApplicationController::class)->middleware('auth:sanctum');


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

Route::get('industries', [IndustryController::class, 'index']);
Route::get('industries/{id}', [IndustryController::class, 'show']);
Route::group(['middleware' => ['auth:sanctum', OnlyAdmin::class]], function () {
    Route::apiResource('industries', IndustryController::class)->except(['index', 'show']);
});

Route::get('applications', [ApplicationController::class, 'index'])->middleware(['auth:sanctum']);

Route::group(['middleware' => ['auth:sanctum',OnlyEmployer::class]], function () {
Route::post('applications/{id}/approved', [ApplicationController::class, 'approve']);
Route::post('applications/{id}/rejected', [ApplicationController::class, 'reject']);
});
Route::post('applications', [ApplicationController::class, 'store'])->middleware(['auth:sanctum']);

Route::get('applications/{id}', [ApplicationController::class, 'show'])->middleware(['auth:sanctum']);

Route::delete('applications/{id}', [ApplicationController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::put('applications/{id}', [ApplicationController::class, 'update'])->middleware(['auth:sanctum']);

