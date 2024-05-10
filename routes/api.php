<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JobPostSkillController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\JobPostController;

Route::apiResource('users', UserController::class);

Route::apiResource('skills', SkillController::class);

Route::apiResource('industries', IndustryController::class);

Route::apiResource('applications', ApplicationController::class);

Route::get('jobs/search', [JobPostController::class, 'search']);
Route::apiResource('jobs', JobPostController::class);

