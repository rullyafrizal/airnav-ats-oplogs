<?php

use App\Enums\HttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

if(!function_exists('api_response')) {
    function api_response(int $status = HttpStatus::OK, string $message = '', $body = []): JsonResponse
    {
        if ($body) {
            return response()
                ->json([
                    'status' => $status,
                    'message' => $message,
                    'body' => $body
                ], $status);
        }

        return response()
            ->json([
                'status' => $status,
                'message' => $message,
            ], $status);
    }
}

if(!function_exists('to_carbon')) {
    function to_carbon($date)
    {
        if (!$date instanceof Carbon) {
            return Carbon::parse($date);
        }

        return $date;
    }
}
