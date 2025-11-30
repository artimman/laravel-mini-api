<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ], $status);
    }

    public static function error(string $message, int $status = 400): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
        ], $status);
    }
}
