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
use App\Http\Middleware\OnlyEmployer;
use App\Http\Middleware\OnlyCandidate;

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return response()->json($request->user());
    });
    Route::get('user/me', function (Request $request) {
        return response()->json($request->user());
    });
    Route::post('user/logout', [LogoutController::class, 'logout']);
});

Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', 'onlyAdmin']);

Route::middleware(['auth:sanctum', 'onlyAdmin'])->group(function () { // Important to have this before other job-posts route
    Route::get('job-posts/pending', [JobPostController::class, 'pendingPosts']);
    Route::get('job-posts/rejected', [JobPostController::class, 'rejectedPosts']);
    Route::put('job-posts/{id}/approve', [JobPostController::class, 'approve']);
    Route::put('job-posts/{id}/reject', [JobPostController::class, 'reject']);
});

Route::get('job-posts', [JobPostController::class, 'index']);
Route::get('job-posts/{id}', [JobPostController::class, 'show']);
Route::get('job-posts/search', [JobPostController::class, 'search']);


Route::middleware(['auth:sanctum', 'onlyEmployer'])->group(function () {
    Route::post('job-posts', [JobPostController::class, 'store']);
    Route::put('job-posts/{id}', [JobPostController::class, 'update'])->middleware(['onlyJobPostOwner']);
    Route::delete('job-posts/{id}', [JobPostController::class, 'destroy'])->middleware(['onlyJobPostOwner']);
    Route::get('job-posts/{id}/applications', [JobPostController::class, 'jobPostApplications'])->middleware(['onlyJobPostOwner']);
    // Route::get('employer/applications', [ApplicationController::class, 'employerApplications']);
    // Route::put('applications/{id}/approve', [ApplicationController::class, 'approve']);
    // Route::put('applications/{id}/reject', [ApplicationController::class, 'reject']);
});


Route::apiResource('skills', SkillController::class)->middleware('auth:sanctum');

// Route::apiResource('applications', ApplicationController::class)->middleware('auth:sanctum');

Route::get('industries', [IndustryController::class, 'index']);
Route::get('industries/{id}', [IndustryController::class, 'show']);
Route::middleware(['auth:sanctum', 'onlyAdmin'])->group(function () {
    Route::apiResource('industries', IndustryController::class)->except(['index', 'show']);
});

Route::get('applications', [ApplicationController::class, 'index'])->middleware(['auth:sanctum', OnlyCandidate::class]);

Route::group(['middleware' => ['auth:sanctum', OnlyEmployer::class]], function () {
    Route::post('applications/{id}/approved', [ApplicationController::class, 'approve']);
    Route::post('applications/{id}/rejected', [ApplicationController::class, 'reject']);
});
Route::post('applications', [ApplicationController::class, 'store'])->middleware(['auth:sanctum'])->middleware([OnlyCandidate::class]);

Route::get('applications/{id}', [ApplicationController::class, 'show'])->middleware(['auth:sanctum'])->middleware([OnlyCandidate::class]);

Route::delete('applications/{id}', [ApplicationController::class, 'destroy'])->middleware(['auth:sanctum'])->middleware([OnlyCandidate::class]);
