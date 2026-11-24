<?php

namespace App\Http\Controllers;

use App\Enum\FilmStatus;
use App\Http\Requests\IndexFilmRequest;
use App\Http\Responses\SuccessResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FilmController extends Controller
{
    /**
     * List films.
     */
    public function index(IndexFilmRequest $request): Responsable
    {
        $status = $request->validated('status', FilmStatus::READY->value);

        if ($status !== FilmStatus::READY->value) {
            $user = $request->user('sanctum');

            abort_unless($user, 401);

            // abort_unless($user->can('moderate'), 403);
            Gate::forUser($user)->authorize('moderate');
        }

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
