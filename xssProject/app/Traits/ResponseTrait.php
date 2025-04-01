<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function response($status, $message, $data, $error): JsonResponse
    {
        return response()->json(
            [
                'status'=>$status,
                'message'=>$message,
                'data'=>$data,
                'error'=>$error
            ]
        );
    }
}
