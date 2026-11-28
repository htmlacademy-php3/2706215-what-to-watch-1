<?php

namespace App\Http\Controllers;

use App\Enum\FilmStatus;
use App\Http\Requests\IndexFilmRequest;
use App\Http\Requests\StoreFilmRequest;
use App\Http\Requests\UpdateFilmRequest;
use App\Http\Resources\FilmListCollection;
use App\Http\Resources\FilmResource;
use App\Http\Responses\PaginatedResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FilmController extends Controller
{
    /**
     * List films.
     */
    public function index(IndexFilmRequest $request): Responsable
    {
        $status = $request->validated('status', FilmStatus::READY->value);
        $genreId = $request->validated('genre');
        $orderBy = $request->validated('order_by', 'released');
        $orderDirection = $request->validated('order_to', 'desc');
        $films = Film::query()
            ->where('status', $status);

        if ($genreId !== null) {
            $films->whereHas('genres', fn (Builder $query): Builder => $query->whereKey($genreId));
        }

        if ($orderBy === 'rating') {
            $films->withAvg('comments as average_rating', 'rating')
                ->orderBy('average_rating', $orderDirection);
        } else {
            $films->orderBy('released', $orderDirection);
        }

        $films = $films->paginate(8);

        return new PaginatedResponse(new FilmListCollection($films)->resource);
    }

    /**
     * Add a film.
     */
    public function store(StoreFilmRequest $request): Responsable
    {
        $film = Film::create([
            'imdb_id' => $request->validated('imdb_id'),
            'status' => FilmStatus::PENDING,
        ]);

        return new SuccessResponse([
            'id' => $film->id,
            'imdb_id' => $film->imdb_id,
            'status' => $film->status->value,
        ], code: 201);
    }

    /**
     * Show a film.
     */
    public function show(string $id): Responsable
    {
        $film = Film::with(['genres', 'comments'])->findOrFail($id);

        return new SuccessResponse(new FilmResource($film)->resolve());
    }

    /**
     * Update a film.
     */
    public function update(UpdateFilmRequest $request, Film $film): Responsable
    {
        DB::transaction(function () use ($request, $film): void {
            $film->update($request->safe()->except('genre'));

            $genreNames = $request->validated('genre');

            if ($genreNames !== null) {
                $genreIds = [];

                foreach ($genreNames as $genreName) {
                    $genreIds[] = Genre::firstOrCreate(['name' => $genreName])->id;
                }

                $film->genres()->sync($genreIds);
            }
        });

        $film->load(['genres', 'comments']);

        return new SuccessResponse(new FilmResource($film)->resolve());
    }

    /**
     * List similar films.
     */
    public function similar(string $id): Responsable
    {
        $film = Film::findOrFail($id);
        $genreIds = $film->genres()->allRelatedIds();

        $similarFilms = Film::query()
            ->where('status', FilmStatus::READY->value)
            ->whereKeyNot($film->id)
            ->whereHas('genres', fn (Builder $query): Builder => $query->whereIn('genres.id', $genreIds))
            ->limit(4)
            ->get();

        return new SuccessResponse(new FilmListCollection($similarFilms)->resolve());
    }
}
