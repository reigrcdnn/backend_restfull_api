<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

//posts
Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);