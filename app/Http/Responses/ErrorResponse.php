<?php

namespace App\Http\Responses;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorResponse extends BaseResponse
{
    private const DEFAULT_CODE = 500;

    /**
     * @param string|null $message Null uses the exception message.
     * @param int|null $code Null uses the HTTP exception status or 500.
     */
    public function __construct(
        Throwable $exception,
        bool $status = false,
        ?string $message = null,
        ?int $code = null,
    ) {
        $code ??= $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : self::DEFAULT_CODE;

        parent::__construct(
            $status,
            $message ?? $exception->getMessage(),
            $code
        );
    }

    protected function payload(): array
    {
        return [];
    }
}
