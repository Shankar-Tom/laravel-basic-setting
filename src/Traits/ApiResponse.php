<?php

namespace Shankar\LaravelBasicSetting\Traits;

trait ApiResponse
{
    protected function processResponse($data, $message)
    {
        if (filled($data)) {
            return $this->successResponse(data: $data);
        }
        return $this->errorResponse(message: $message);
    }

    protected function emptyResponse($status = 202, $message = "Data not available")
    {
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ], $status);
    }

    protected function successResponse($status = 200, $message = 'Successful', $data = null)
    {
        $temparray['status'] = $status;
        if ($data) {
            $temparray['data'] = $data;
        }
        $temparray['message'] = $message;
        return response()->json($temparray);
    }

    protected function validationErrorResponse($status = 400, $message = "Validation error", $errors = null)
    {
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'error'   => is_object($errors) && method_exists($errors, 'first') ? $errors->first() : $errors,
        ], 400);
    }

    protected function internalServerErrorResponse($status = 500, $message = "Please Try Again", $exception = null)
    {
        return response()->json([
            'status'        => $status,
            'message'       => $message,
            'error_details' => $exception,
        ], 500);
    }

    protected function errorResponse($status = 403, $message = null)
    {
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ], 400);
    }
}
