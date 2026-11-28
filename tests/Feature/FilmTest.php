<?php

use App\Enum\FilmStatus;
use App\Enum\UserRole;
use App\Models\Comment;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('guest can view the film list', function () {
    /** @var TestCase $this */
    $films = Film::factory()->count(3)
        ->sequence(['released' => 2001], ['released' => 2003], ['released' => 2002])
        ->create(['status' => FilmStatus::READY->value]);
    Film::factory()->create(['status' => FilmStatus::PENDING->value]);

    $response = $this->getJson(route('films.index'));

    $response->assertOk();
    $response->assertJsonCount(3, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'preview_image',
                'preview_video_link',
            ],
        ],
        'current_page',
        'first_page_url',
        'last_page_url',
        'next_page_url',
        'prev_page_url',
        'per_page',
        'total',
    ]);
    $response->assertJsonPath('per_page', 8);
    $response->assertJsonPath('total', 3);
    $response->assertJsonPath('data.0.id', $films[1]->id);
    $response->assertJsonPath('data.1.id', $films[2]->id);
    $response->assertJsonPath('data.2.id', $films[0]->id);

    foreach ($films as $film) {
        $response->assertJsonFragment([
            'id' => $film->id,
            'name' => $film->name,
        ]);
    }
});

test('guest can view the second page of films', function () {
    /** @var TestCase $this */
    $oldestFilm = Film::factory()->create([
        'status' => FilmStatus::READY->value,
        'released' => 2000,
    ]);
    Film::factory()->count(8)->create([
        'status' => FilmStatus::READY->value,
        'released' => 2020,
    ]);

    $response = $this->getJson(route('films.index', ['page' => 2]));

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.id', $oldestFilm->id);
    $response->assertJsonPath('current_page', 2);
    $response->assertJsonPath('per_page', 8);
    $response->assertJsonPath('total', 9);
});

test('guest can filter films by genre', function () {
    /** @var TestCase $this */
    $genre = Genre::factory()->create();
    $film = Film::factory()->create(['status' => FilmStatus::READY->value]);
    $film->genres()->attach($genre->id);
    Film::factory()->create(['status' => FilmStatus::READY->value]);

    $response = $this->getJson(route('films.index', ['genre' => $genre->id]));

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.id', $film->id);
});

test('guest can sort films by release year ascending', function () {
    /** @var TestCase $this */
    $newFilm = Film::factory()->create([
        'status' => FilmStatus::READY->value,
        'released' => 2020,
    ]);
    $oldFilm = Film::factory()->create([
        'status' => FilmStatus::READY->value,
        'released' => 2000,
    ]);

    $response = $this->getJson(route('films.index', [
        'order_by' => 'released',
        'order_to' => 'asc',
    ]));

    $response->assertOk();
    $response->assertJsonPath('data.0.id', $oldFilm->id);
    $response->assertJsonPath('data.1.id', $newFilm->id);
});

test('guest can sort films by average rating', function () {
    /** @var TestCase $this */
    $film = Film::factory()->create(['status' => FilmStatus::READY->value]);
    Comment::factory()->for($film)->create(['rating' => 4]);
    Comment::factory()->for($film)->create(['rating' => 8]);
    $higherRatedFilm = Film::factory()->create(['status' => FilmStatus::READY->value]);
    Comment::factory()->for($higherRatedFilm)->create(['rating' => 9]);
    $unratedFilm = Film::factory()->create(['status' => FilmStatus::READY->value]);

    $response = $this->getJson(route('films.index', ['order_by' => 'rating']));

    $response->assertOk();
    $response->assertJsonPath('data.0.id', $higherRatedFilm->id);
    $response->assertJsonPath('data.1.id', $film->id);
    $response->assertJsonPath('data.2.id', $unratedFilm->id);
});

test('moderator can filter films by status', function () {
    /** @var TestCase $this */
    $moderatorRole = Role::query()
        ->where('name', UserRole::MODERATOR->value)
        ->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $moderatorRole->id,
    ]);
    $film = Film::factory()->create(['status' => FilmStatus::MODERATE->value]);
    Film::factory()->create(['status' => FilmStatus::READY->value]);
    $this->actingAs($user);

    $response = $this->getJson(route('films.index', ['status' => FilmStatus::MODERATE->value]));

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.id', $film->id);
});

test('client cannot view films awaiting moderation', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson(route('films.index', ['status' => FilmStatus::MODERATE->value]));

    $response->assertForbidden();
});

test('guest can view a film', function () {
    /** @var TestCase $this */
    $film = Film::factory()->create([
        'status' => FilmStatus::READY->value,
    ]);
    $genres = Genre::factory()->count(2)->create();
    $film->genres()->attach($genres->modelKeys());
    Comment::factory()->for($film)->create(['rating' => 6]);
    Comment::factory()->for($film)->create(['rating' => 8]);

    $response = $this->getJson(route('films.show', ['id' => $film->id]));

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'imdb_id',
            'poster_image',
            'preview_image',
            'background_image',
            'background_color',
            'video_link',
            'preview_video_link',
            'description',
            'director',
            'starring',
            'run_time',
            'released',
            'status',
            'genres' => [
                '*' => ['id', 'name'],
            ],
            'rating',
            'scores_count',
        ],
    ]);
    $response->assertJsonPath('data.id', $film->id);
    $response->assertJsonPath('data.name', $film->name);
    $response->assertJsonPath('data.rating', 7);
    $response->assertJsonPath('data.scores_count', 2);
    $response->assertJsonCount(2, 'data.genres');

    foreach ($genres as $genre) {
        $response->assertJsonFragment([
            'id' => $genre->id,
            'name' => $genre->name,
        ]);
    }
});

test('guest cannot view a nonexistent film', function () {
    /** @var TestCase $this */
    $film = Film::factory()->create([
        'status' => FilmStatus::READY->value,
    ]);
    $filmId = $film->id;
    $film->delete();

    $response = $this->getJson(route('films.show', ['id' => $filmId]));

    $response->assertNotFound();
    $response->assertJsonStructure(['message']);
});

test('authenticated user can view a favorite film', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $film = Film::factory()->create([
        'status' => FilmStatus::READY->value,
    ]);
    $user->favorites()->attach($film->id);
    $this->actingAs($user);

    $response = $this->getJson(route('films.show', ['id' => $film->id]));

    $response->assertOk();
    $response->assertJsonPath('data.id', $film->id);
    $response->assertJsonPath('data.is_favorite', true);
});

test('authenticated user can view a film outside favorites', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $film = Film::factory()->create([
        'status' => FilmStatus::READY->value,
    ]);
    $this->actingAs($user);

    $response = $this->getJson(route('films.show', ['id' => $film->id]));

    $response->assertOk();
    $response->assertJsonPath('data.id', $film->id);
    $response->assertJsonPath('data.is_favorite', false);
});

test('guest can view similar films', function () {
    /** @var TestCase $this */
    $genre = Genre::factory()->create();
    $film = Film::factory()->create([
        'status' => FilmStatus::READY->value,
    ]);
    $film->genres()->attach($genre->id);

    $similarFilms = Film::factory()->count(2)->create([
        'status' => FilmStatus::READY->value,
    ]);

    foreach ($similarFilms as $similarFilm) {
        $similarFilm->genres()->attach($genre->id);
    }

    Film::factory()->create([
        'status' => FilmStatus::READY->value,
    ]);

    $response = $this->getJson(route('films.similar', ['id' => $film->id]));

    $response->assertOk();
    $response->assertJsonCount(2, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'preview_image',
                'preview_video_link',
            ],
        ],
    ]);

    $this->assertEqualsCanonicalizing(
        $similarFilms->modelKeys(),
        array_column($response->json('data'), 'id'),
    );
});

test('guest cannot create a film', function () {
    /** @var TestCase $this */
    $response = $this->postJson(route('films.store'), [
        'imdb_id' => 'tt0111161',
    ]);

    $response->assertUnauthorized();
});

test('client cannot create a film', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson(route('films.store'), [
        'imdb_id' => 'tt0111161',
    ]);

    $response->assertForbidden();
});

test('moderator can create a film', function () {
    /** @var TestCase $this */
    $moderatorRole = Role::query()
        ->where('name', UserRole::MODERATOR->value)
        ->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $moderatorRole->id,
    ]);
    $this->actingAs($user);

    $response = $this->postJson(route('films.store'), [
        'imdb_id' => 'tt0111161',
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => ['id', 'imdb_id', 'status'],
    ]);
    $response->assertJsonPath('data.imdb_id', 'tt0111161');
    $response->assertJsonPath('data.status', FilmStatus::PENDING->value);

    $this->assertDatabaseHas('films', [
        'imdb_id' => 'tt0111161',
        'status' => FilmStatus::PENDING->value,
    ]);
});

test('guest cannot update a film', function () {
    /** @var TestCase $this */
    $film = Film::factory()->create();

    $response = $this->patchJson(route('films.update', ['film' => $film->id]), [
        'name' => 'Updated film name',
    ]);

    $response->assertUnauthorized();
});

test('client cannot update a film', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $film = Film::factory()->create();
    $this->actingAs($user);

    $response = $this->patchJson(route('films.update', ['film' => $film->id]), [
        'name' => 'Updated film name',
    ]);

    $response->assertForbidden();
});

test('moderator can update a film', function () {
    /** @var TestCase $this */
    $moderatorRole = Role::query()
        ->where('name', UserRole::MODERATOR->value)
        ->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $moderatorRole->id,
    ]);
    $film = Film::factory()->create([
        'name' => 'Original film name',
        'status' => FilmStatus::MODERATE->value,
    ]);
    $oldGenre = Genre::factory()->create(['name' => 'Comedy']);
    $newGenre = Genre::factory()->create(['name' => 'Drama']);
    $film->genres()->attach($oldGenre->id);
    $released = now()->year + 1;
    $this->actingAs($user);

    $response = $this->patchJson(route('films.update', ['film' => $film->id]), [
        'name' => 'Updated film name',
        'imdb_id' => $film->imdb_id,
        'status' => FilmStatus::READY->value,
        'background_color' => '#A1b2C3ff',
        'released' => $released,
        'genre' => [$newGenre->name],
    ]);

    $response->assertOk();
    $response->assertJsonPath('data.id', $film->id);
    $response->assertJsonPath('data.name', 'Updated film name');
    $response->assertJsonPath('data.status', FilmStatus::READY->value);
    $response->assertJsonPath('data.background_color', '#A1b2C3ff');
    $response->assertJsonPath('data.released', $released);
    $response->assertJsonCount(1, 'data.genres');
    $response->assertJsonPath('data.genres.0.id', $newGenre->id);
    $response->assertJsonPath('data.genres.0.name', $newGenre->name);

    $this->assertDatabaseHas('films', [
        'id' => $film->id,
        'name' => 'Updated film name',
        'imdb_id' => $film->imdb_id,
        'status' => FilmStatus::READY->value,
        'background_color' => '#A1b2C3ff',
        'released' => $released,
    ]);

    $this->assertDatabaseHas('film_genre', [
        'film_id' => $film->id,
        'genre_id' => $newGenre->id,
    ]);
    $this->assertDatabaseMissing('film_genre', [
        'film_id' => $film->id,
        'genre_id' => $oldGenre->id,
    ]);
});
