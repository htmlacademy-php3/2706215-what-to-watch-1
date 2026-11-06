<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseResponse implements Responsable
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        private array $payload,
        private int $status
    ) {}

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function toResponse($request): Response
    {
        return new JsonResponse($this->payload, $this->status);
    }
}
