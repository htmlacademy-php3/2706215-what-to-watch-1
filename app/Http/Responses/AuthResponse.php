<?php

namespace App\Http\Responses;

class AuthResponse extends BaseResponse
{
    public function __construct(
        bool $status = false,
        ?string $message = 'Запрос требует аутентификации.',
        int $code = 401,
    ) {
        parent::__construct($status, $message, $code);
    }

    protected function payload(): array
    {
        return [];
    }
}
