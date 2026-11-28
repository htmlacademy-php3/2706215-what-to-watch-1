<?php

use App\Enum\UserRole;
use App\Models\Comment;
use App\Models\Film;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('guest can view film comments', function () {
    /** @var TestCase $this */
    $film = Film::factory()->create();
    $comments = Comment::factory()->count(2)->for($film)
        ->sequence(
            ['created_at' => now()->subDay()->startOfDay()],
            ['created_at' => now()->startOfDay()],
        )
        ->create();
    Comment::factory()->create();

    $response = $this->getJson(route('comments.index', ['id' => $film->id]));

    $response->assertOk();
    $response->assertJsonCount(2, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'text', 'rating', 'author_name', 'created_at'],
        ],
    ]);
    $response->assertJsonPath('data.0.id', $comments->last()->id);
    $response->assertJsonPath('data.1.id', $comments->first()->id);

    foreach ($comments as $comment) {
        $response->assertJsonFragment([
            'id' => $comment->id,
            'text' => $comment->text,
            'rating' => $comment->rating,
            'author_name' => $comment->user->name,
            'created_at' => $comment->created_at->toISOString(),
        ]);
    }
});

test('guest sees anonymous comment author as guest', function () {
    /** @var TestCase $this */
    $film = Film::factory()->create();
    Comment::factory()->for($film)->create(['user_id' => null]);

    $response = $this->getJson(route('comments.index', ['id' => $film->id]));

    $response->assertOk();
    $response->assertJsonPath('data.0.author_name', 'Гость');
});

test('guest cannot view comments of a nonexistent film', function () {
    /** @var TestCase $this */
    $film = Film::factory()->create();
    $filmId = $film->id;
    $film->delete();

    $response = $this->getJson(route('comments.index', ['id' => $filmId]));

    $response->assertNotFound();
    $response->assertJsonStructure(['message']);
});

test('guest cannot create a comment', function () {
    /** @var TestCase $this */
    $film = Film::factory()->create();

    $response = $this->postJson(route('comments.store', ['id' => $film->id]), [
        'text' => 'An engaging film with memorable characters and a beautifully crafted story.',
        'rating' => 8,
    ]);

    $response->assertUnauthorized();
});

test('authenticated user can create a comment', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $film = Film::factory()->create();
    $text = fake()->realTextBetween(50, 400);
    $this->actingAs($user);

    $response = $this->postJson(route('comments.store', ['id' => $film->id]), [
        'text' => $text,
        'rating' => 8,
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => ['id', 'text', 'rating', 'author_name', 'created_at'],
    ]);
    $response->assertJsonPath('data.text', $text);
    $response->assertJsonPath('data.rating', 8);
    $response->assertJsonPath('data.author_name', $user->name);

    $this->assertDatabaseHas('comments', [
        'id' => $response->json('data.id'),
        'film_id' => $film->id,
        'user_id' => $user->id,
        'text' => $text,
        'rating' => 8,
    ]);
});

test('authenticated user can reply to a comment', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $film = Film::factory()->create();
    $parentComment = Comment::factory()->for($film)->create();
    $text = fake()->realTextBetween(50, 400);
    $this->actingAs($user);

    $response = $this->postJson(route('comments.store', ['id' => $film->id]), [
        'text' => $text,
        'rating' => 8,
        'comment_id' => $parentComment->id,
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('comments', [
        'id' => $response->json('data.id'),
        'film_id' => $film->id,
        'user_id' => $user->id,
        'text' => $text,
        'rating' => 8,
        'comment_id' => $parentComment->id,
    ]);
});

test('guest cannot update a comment', function () {
    /** @var TestCase $this */
    $comment = Comment::factory()->create();

    $response = $this->patchJson(route('comments.update', ['comment' => $comment->id]), [
        'text' => fake()->realTextBetween(50, 400),
    ]);

    $response->assertUnauthorized();
});

test('user cannot update another user\'s comment', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $comment = Comment::factory()->create();
    $this->actingAs($user);

    $response = $this->patchJson(route('comments.update', ['comment' => $comment->id]), [
        'text' => fake()->realTextBetween(50, 400),
    ]);

    $response->assertForbidden();
});

test('user can update own comment', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $comment = Comment::factory()->for($user)->create(['rating' => 5]);
    $text = fake()->realTextBetween(50, 400);
    $this->actingAs($user);

    $response = $this->patchJson(route('comments.update', ['comment' => $comment->id]), [
        'text' => $text,
        'rating' => 8,
    ]);

    $response->assertOk();
    $response->assertJsonPath('data.id', $comment->id);
    $response->assertJsonPath('data.text', $text);
    $response->assertJsonPath('data.rating', 8);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'text' => $text,
        'rating' => 8,
    ]);
});

test('moderator can update another user\'s comment', function () {
    /** @var TestCase $this */
    $moderatorRole = Role::query()
        ->where('name', UserRole::MODERATOR->value)
        ->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $moderatorRole->id,
    ]);
    $comment = Comment::factory()->create(['rating' => 5]);
    $text = fake()->realTextBetween(50, 400);
    $this->actingAs($user);

    $response = $this->patchJson(route('comments.update', ['comment' => $comment->id]), [
        'text' => $text,
    ]);

    $response->assertOk();
    $response->assertJsonPath('data.id', $comment->id);
    $response->assertJsonPath('data.text', $text);
    $response->assertJsonPath('data.rating', 5);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'text' => $text,
        'rating' => 5,
    ]);
});

test('guest cannot delete a comment', function () {
    /** @var TestCase $this */
    $comment = Comment::factory()->create();

    $response = $this->deleteJson(route('comments.destroy', ['comment' => $comment->id]));

    $response->assertUnauthorized();
});

test('user cannot delete another user\'s comment', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $comment = Comment::factory()->create();
    $this->actingAs($user);

    $response = $this->deleteJson(route('comments.destroy', ['comment' => $comment->id]));

    $response->assertForbidden();
    $this->assertDatabaseHas('comments', ['id' => $comment->id]);
});

test('user can delete own comment without replies', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $comment = Comment::factory()->for($user)->create();
    $this->actingAs($user);

    $response = $this->deleteJson(route('comments.destroy', ['comment' => $comment->id]));

    $response->assertOk();
    $response->assertJsonPath('data', []);
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('user cannot delete own comment with replies', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $comment = Comment::factory()->for($user)->create();
    $reply = Comment::factory()->for($comment->film)->for($comment, 'parent')->create();
    $this->actingAs($user);

    $response = $this->deleteJson(route('comments.destroy', ['comment' => $comment->id]));

    $response->assertForbidden();
    $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    $this->assertDatabaseHas('comments', ['id' => $reply->id]);
});

test('moderator can delete another user\'s comment with all descendants', function () {
    /** @var TestCase $this */
    $moderatorRole = Role::query()
        ->where('name', UserRole::MODERATOR->value)
        ->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $moderatorRole->id,
    ]);
    $comment = Comment::factory()->create();
    $reply = Comment::factory()->for($comment->film)->for($comment, 'parent')->create();
    $nestedReply = Comment::factory()->for($comment->film)->for($reply, 'parent')->create();
    $unrelatedComment = Comment::factory()->for($comment->film)->create();
    $this->actingAs($user);

    $response = $this->deleteJson(route('comments.destroy', ['comment' => $comment->id]));

    $response->assertOk();
    $response->assertJsonPath('data', []);
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    $this->assertDatabaseMissing('comments', ['id' => $reply->id]);
    $this->assertDatabaseMissing('comments', ['id' => $nestedReply->id]);
    $this->assertDatabaseHas('comments', ['id' => $unrelatedComment->id]);
});
