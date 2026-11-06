<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    /**
     * List genres.
     */
    public function index(): Response
    {
        return response()->noContent();
    }

    /**
     * Update a genre.
     */
    public function update(Request $request, string $genre): Response
    {
        return response()->noContent();
    }
}
