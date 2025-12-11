<?php

use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
                'message' => $e->getMessage() ?: 'Resource not found.',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        });

        $exceptions->render(function (MassAssignmentException $e, Request $request) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage() ?: 'Add fillable property to the model.',
                'data' => null,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage() ?: 'Method not allowed for the requested route.',
                'data' => null,
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        });

        $exceptions->render(function (QueryException $e, Request $request) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage() ?: 'Sql query error.',
                'data' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage() ?: 'Sql query error.',
                'data' => null,
            ], Response::HTTP_FORBIDDEN);
        });
    })->create();
