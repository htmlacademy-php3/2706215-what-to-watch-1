<?php

use App\Enum\UserRole;
use App\Models\Genre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can view the genre list', function () {
    $genres = Genre::factory()->count(3)->create();

    $response = $this->getJson(route('genres.index'));

    $response->assertOk();
    $response->assertJsonCount(3, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'name'],
        ],
    ]);

    foreach ($genres as $genre) {
        $response->assertJsonFragment([
            'id' => $genre->id,
            'name' => $genre->name,
        ]);
    }
});

test('guest receives an empty genre list when no genres exist', function () {
    $response = $this->getJson(route('genres.index'));

    $response->assertOk();
    $response->assertJsonPath('data', []);
});

test('authenticated user can view the genre list', function () {
    $user = User::factory()->create();
    Genre::factory()->count(3)->create();
    $this->actingAs($user);

    $response = $this->getJson(route('genres.index'));

    $response->assertOk();
    $response->assertJsonCount(3, 'data');
});

test('guest cannot update a genre', function () {
    $genre = Genre::factory()->create();

    $response = $this->patchJson(
        route('genres.update', ['genre' => $genre->id]),
        ['name' => 'Drama'],
    );

    $response->assertUnauthorized();
});

test('client cannot update a genre', function () {
    $user = User::factory()->create();
    $genre = Genre::factory()->create();
    $this->actingAs($user);

    $response = $this->patchJson(
        route('genres.update', ['genre' => $genre->id]),
        ['name' => 'Drama'],
    );

    $response->assertForbidden();
});

test('moderator can update a genre', function () {
    $moderatorRole = Role::query()
        ->where('name', UserRole::MODERATOR->value)
        ->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $moderatorRole->id,
    ]);
    $genre = Genre::factory()->create([
        'name' => 'Comedy',
    ]);
    $this->actingAs($user);

    $response = $this->patchJson(
        route('genres.update', ['genre' => $genre->id]),
        ['name' => 'Drama'],
    );

    $response->assertOk();

    $this->assertDatabaseHas('genres', [
        'id' => $genre->id,
        'name' => 'Drama',
    ]);

    $response->assertJsonPath('data.id', $genre->id);
    $response->assertJsonPath('data.name', 'Drama');

});
