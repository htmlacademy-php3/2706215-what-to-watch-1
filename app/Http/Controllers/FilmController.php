<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    /**
     * List films.
     */
    public function index(): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Add a film.
     */
    public function store(Request $request): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Show a film.
     */
    public function show(string $id): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Update a film.
     */
    public function update(Request $request, string $id): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * List similar films.
     */
    public function similar(string $id): Responsable
    {
        return new SuccessResponse([]);
    }
}
