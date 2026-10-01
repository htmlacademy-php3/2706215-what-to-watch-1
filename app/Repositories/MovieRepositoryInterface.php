<?php

declare(strict_types=1);

namespace App\Repositories;

interface MovieRepositoryInterface
{
    /**
     * Finds a movie by its IMDb ID.
     *
     * @return array<string, mixed> Movie data.
     */
    public function findByImdbId(string $imdbId): array;
}
