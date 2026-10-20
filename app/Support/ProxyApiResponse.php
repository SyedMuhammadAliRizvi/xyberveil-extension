<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ProxyApiResponse
{
    public static function detail(string $message, int $status = 400): JsonResponse
    {
        return response()->json(['detail' => $message], $status);
    }
}
