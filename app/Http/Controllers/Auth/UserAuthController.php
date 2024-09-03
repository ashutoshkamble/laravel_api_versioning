<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\MasterApiController;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends MasterApiController
{
    /**
     * Register the user to the api
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        // If validation fails it will throw ValidationException
        // This exception is handled at E:\xampp\htdocs\laravel_api_versioning\bootstrap\app.php
        $registerUserData = $request->validated();

        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
        ]);

        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'Successfully created user!',
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ], Response::HTTP_CREATED);
    }

    /**
     * Make user logged in
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $loginUserData = $request->validated();

        $user = User::where('email', $loginUserData['email'])->first();

        if (! $user || ! Hash::check($loginUserData['password'], $user->password)) {
            return $this->errorResponse('Invalid Credentials', Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in!',
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ], Response::HTTP_ACCEPTED);
    }
}
