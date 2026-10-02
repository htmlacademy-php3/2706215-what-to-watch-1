<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\MovieRepositoryInterface;

final class MovieService
{
    public function __construct(
        private MovieRepositoryInterface $repository
    ) {}

    /**
     * Retrieves movie information by its IMDb ID.
     *
     * @return array<string, mixed>
     */
    public function findByImdbId(string $imdbId): array
    {
        return $this->repository->findByImdbId($imdbId);
    }
}
