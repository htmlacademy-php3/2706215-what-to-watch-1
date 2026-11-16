<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseResponse implements Responsable
{
    /**
     * Initialize common response fields and HTTP status code.
     */
    public function __construct(
        protected bool $status = true,
        protected ?string $message = null,
        protected int $code = 200,
    ) {}

    /**
     * Return response-specific fields.
     *
     * @return array<string, mixed>
     */
    abstract protected function payload(): array;

    /**
     * Build the JSON HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function toResponse($request): Response
    {
        return new JsonResponse([
            ...$this->payload(),
            'message' => $this->message,
            'status' => $this->status,
        ], $this->code);
    }

    /**
     * Remove null values while preserving keys.
     *
     * @param array<string, mixed> $fields
     * @return array<string, mixed>
     */
    public function compact(array $fields): array
    {
        return array_filter($fields, static fn($item) => $item !== null);
    }
}
