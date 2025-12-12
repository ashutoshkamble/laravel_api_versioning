<?php

use App\Http\Controllers\Api\V2\CommentController;
use App\Http\Controllers\Api\V2\PostController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class);

Route::get('posts/{post}/comments', [CommentController::class, 'index']);
Route::post('posts/{post}/comments', [CommentController::class, 'store']);

Route::apiResource('comments', CommentController::class)->only(['update', 'destroy']);
