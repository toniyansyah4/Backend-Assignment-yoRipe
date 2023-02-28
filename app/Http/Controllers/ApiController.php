<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function successResponse(
        $data = null,
        $message = null,
        $extra = [],
        $status = true
    ) {
        $response = [
            'success' => $status,
            'message' => $message,
            'data' => $data,
        ];

        // merge extraresponse
        if (!empty($extra)) {
            $response = array_merge($response, $extra);
        }
        return response()->json($response, 200);
    }

    public function errorResponse($error = null, $message = null, $code = 403)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'error' => $error,
        ];

        return response()->json($response, $code);
    }

    public function definitionResponse(
        $data = null,
        $message = null,
        $result = false
    ) {
        $response = [
            'success' => $result,
            'message' => $message,
            'data' => $data,
        ];

        // merge extraresponse
        if (!empty($extra)) {
            $response = array_merge($response, $extra);
        }
        if (!$result) {
            return response()->json($response, 403);
        }
        return response()->json($response, 200);
    }
}
