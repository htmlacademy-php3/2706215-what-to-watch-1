<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginatedResponse extends BaseResponse
{
    /**
     * @param LengthAwarePaginator<string, mixed> $paginator
     */
    public function __construct(
        protected LengthAwarePaginator $paginator,
        bool $status = true,
        ?string $message = 'Успешная загрузка.',
        int $code = 200,
    ) {
        parent::__construct($status, $message, $code);
    }

    protected function payload(): array
    {
        return [
            'data' => $this->paginator->items(),
            'current_page' => $this->paginator->currentPage(),
            'first_page_url' => $this->paginator->url(1),
            'next_page_url' => $this->paginator->nextPageUrl(),
            'prev_page_url' => $this->paginator->previousPageUrl(),
            'per_page' => $this->paginator->perPage(),
            'total' => $this->paginator->total(),
        ];
    }
}
