<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResponseErrorHelper {
    public static function throwErrorResponse(\Throwable $e): JsonResponse
    {
        if ($e instanceof HttpException) {
            return response()->json([
                'errors' => [
                    'message' => $e->getMessage()
                ]
            ],$e->getStatusCode());
        }

        return response()->json([
            'errors' => [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]
        ],500);
    }
}