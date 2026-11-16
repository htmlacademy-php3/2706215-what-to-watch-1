<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * List genres.
     */
    public function index(): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Update a genre.
     */
    public function update(Request $request, string $genre): Responsable
    {
        return new SuccessResponse([]);
    }
}
