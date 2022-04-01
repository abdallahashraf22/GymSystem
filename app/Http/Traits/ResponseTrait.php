<?php

namespace App\Http\Traits;


trait ResponseTrait
{
    public function createResponse(int $statuscode, $data, bool $isSuccess = true, $errors = [])
    {
        return response()->json(
            [
                "data" => $data,
                "isSuccess" => $isSuccess,
                "errors" => $errors,
                "statusCode" => $statuscode
            ]
        )->setStatusCode($statuscode);
    }
}
