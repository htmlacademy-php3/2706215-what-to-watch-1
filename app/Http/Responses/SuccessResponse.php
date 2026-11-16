<?php

namespace App\Http\Responses;

class SuccessResponse extends BaseResponse
{
    public function __construct(
        protected array $data,
        bool $status = true,
        ?string $message = 'Успешная загрузка',
        int $code = 200,
    ) {
        parent::__construct($status, $message, $code);
    }

    protected function payload(): array
    {
        return $this->compact([
            'data' => $this->data,
        ]);
    }
}
