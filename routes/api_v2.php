<?php

use App\Http\Controllers\Api\V2\PostController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class);
