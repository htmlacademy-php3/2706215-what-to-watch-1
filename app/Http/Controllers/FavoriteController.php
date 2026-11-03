<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FavoriteController extends Controller
{
    /**
     * List favorite films.
     */
    public function index(): Response
    {
        return response()->noContent();
    }

    /**
     * Add a film to favorites.
     */
    public function store(Request $request, string $id): Response
    {
        return response()->noContent();
    }

    /**
     * Remove a film from favorites.
     */
    public function destroy(string $id): Response
    {
        return response()->noContent();
    }
}
