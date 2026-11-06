<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * List favorite films.
     */
    public function index(): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Add a film to favorites.
     */
    public function store(Request $request, string $id): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Remove a film from favorites.
     */
    public function destroy(string $id): Responsable
    {
        return new SuccessResponse([]);
    }
}
