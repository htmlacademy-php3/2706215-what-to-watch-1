<?php

declare(strict_types=1);

use App\Repositories\OmdbMovieRepository;
use App\Services\MovieService;
use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// PSR-18 HTTP Client
$httpClient = new Client();

// PSR-17 HTTP Factorie
$requestFactory = new HttpFactory();

$omdbApiKey = $_ENV['OMDB_API_KEY'] ?? '';

$repository = new OmdbMovieRepository(
    $httpClient,
    $requestFactory,
    $omdbApiKey
);

$service = new MovieService($repository);

$movie = $service->findByImdbId('tt33764258');
var_dump($movie);
