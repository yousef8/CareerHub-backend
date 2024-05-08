<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkillController;

Route::apiResource('users', UserController::class);
Route::resource('/v1/skills', SkillController::class, [
    'except' => ['create', 'edit'],
]);

