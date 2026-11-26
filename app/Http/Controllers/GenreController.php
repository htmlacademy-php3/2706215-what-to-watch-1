<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreCollection;
use App\Http\Resources\GenreResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Genre;
use Illuminate\Contracts\Support\Responsable;

class GenreController extends Controller
{
    /**
     * List genres.
     */
    public function index(): Responsable
    {
        $genres = Genre::all();

        return new SuccessResponse(new GenreCollection($genres)->resolve());
    }

    /**
     * Update a genre.
     */
    public function update(UpdateGenreRequest $request, Genre $genre): Responsable
    {
        $genre->update($request->validated());

        return new SuccessResponse(new GenreResource($genre)->resolve());
    }
}
