<?php

use App\Http\Controllers\Auth\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// versioning details here

Route::middleware(['auth:sanctum'])->prefix('v1')->group(base_path('routes/api_v1.php'));
Route::middleware(['auth:sanctum'])->prefix('v2')->group(base_path('routes/api_v2.php'));

// User Authentication Routes
Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout'])
    ->middleware('auth:sanctum');

// Protected route to get authenticated user details
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
