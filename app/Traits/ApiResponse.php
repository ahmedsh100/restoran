<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($message, $data = [])
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    protected function errorResponse($message, $code = 400, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
