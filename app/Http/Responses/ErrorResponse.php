<?php

namespace App\Http\Responses;

use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorResponse extends BaseResponse
{
    private const DEFAULT_STATUS = 500;

    /**
     * @param int|null $status HTTP status override; null uses the exception status or 500.
     */
    public function __construct(
        Throwable $exception,
        ?int $status = null,
    ) {
        $payload = [
            'message' => $exception->getMessage(),
        ];

        if ($exception instanceof ValidationException) {
            $payload['errors'] = $exception->errors();
        }

        if ($status === null) {
            $status = match (true) {
                $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
                $exception instanceof ValidationException => $exception->status,
                default => self::DEFAULT_STATUS,
            };
        }

        parent::__construct($payload, $status);
    }
}
