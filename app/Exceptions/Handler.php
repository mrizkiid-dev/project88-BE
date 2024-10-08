<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'errors' => [
                        'message' => 'Item Not Found'
                    ]
                ], 404);
            }
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'errors' => [
                        'message' => 'UnAuthenticated'
                    ]
                ], 401);
            }
        });
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['errors' => []], 400);
            }
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'errors' => [
                        'message' => 'url not found'
                    ]
                ], 404);
            }
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'errors' => [
                        'message' => 'Method not allowed'
                    ]
                ], 400);
            }
        });
    }

}
