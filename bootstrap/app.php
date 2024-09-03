<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->errors(),
                'data' => null,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Route not found',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        });
    })->create();
