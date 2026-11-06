<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FilmController extends Controller
{
    /**
     * List films.
     */
    public function index(): Response
    {
        return response()->noContent();
    }

    /**
     * Add a film.
     */
    public function store(Request $request): Response
    {
        return response()->noContent();
    }

    /**
     * Show a film.
     */
    public function show(string $id): Response
    {
        return response()->noContent();
    }

    /**
     * Update a film.
     */
    public function update(Request $request, string $id): Response
    {
        return response()->noContent();
    }

    /**
     * List similar films.
     */
    public function similar(string $id): Response
    {
        return response()->noContent();
    }
}
