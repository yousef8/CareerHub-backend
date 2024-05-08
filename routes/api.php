<?php

use App\Http\Controllers\JobPostSkillController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('skills', SkillController::class);

// Define the route for retrieving all skills associated with a specific job post
Route::get('/v1/jobs/{jobId}/skills', [JobPostSkillController::class, 'index']);

// Define the route for attaching a skill to a specific job post
Route::post('/v1/jobs/{jobId}/skills', [JobPostSkillController::class, 'store']);

// Define the route for removing a specific skill from a specific job post
Route::delete('/v1/jobs/{jobId}/skills/{skillId}', [JobPostSkillController::class, 'destroy']);
