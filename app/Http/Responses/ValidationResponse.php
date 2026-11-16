<?php

namespace App\Http\Responses;

class ValidationResponse extends BaseResponse
{
    public function __construct(
        protected array $errors,
        bool $status = false,
        ?string $message = 'Переданные данные не корректны.',
        int $code = 422,
    ) {
        parent::__construct($status, $message, $code);
    }

    protected function payload(): array
    {
        return $this->compact([
            'errors' => $this->errors,
        ]);
    }
}
