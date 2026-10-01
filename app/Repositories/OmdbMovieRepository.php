<?php

declare(strict_types=1);

namespace App\Repositories;

use JsonException;
use Override;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final class OmdbMovieRepository implements MovieRepositoryInterface
{
    private const BASE_URL = 'https://www.omdbapi.com/';

    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private string $apiKey,
    ) {}

    /**
     * {@inheritDoc}
     *
     * @throws ClientExceptionInterface If the HTTP request cannot be completed.
     * @throws JsonException If the response contains invalid JSON.
     */
    #[Override]
    public function findByImdbId(string $imdbId): array
    {
        $query = http_build_query([
            'apikey' => $this->apiKey,
            'i' => $imdbId,
        ]);

        $request = $this->requestFactory->createRequest(
            'GET',
            self::BASE_URL . '?' . $query
        );

        $response = $this->httpClient->sendRequest($request);

        return json_decode(
            $response->getBody()->getContents(),
            true,
            flags: JSON_THROW_ON_ERROR
        );
    }
}
