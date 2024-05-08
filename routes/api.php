<?php

use App\Http\Controllers\JobPostSkillController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndustryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);

Route::apiResource('skills', SkillController::class);

Route::apiResource('jobs', JobPostSkillController::class);

Route::apiResource('industries', IndustryController::class);
