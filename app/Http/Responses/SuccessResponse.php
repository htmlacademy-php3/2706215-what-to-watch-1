<?php

namespace App\Http\Responses;

class SuccessResponse extends BaseResponse
{
    public function __construct(array $data, int $status = 200)
    {
        parent::__construct(['data' => $data], $status);
    }
}
