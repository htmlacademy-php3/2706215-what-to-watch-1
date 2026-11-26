<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * Verify that the author's name matches the related user's name.
     */
    public function test_author_name_matches_user_name(): void
    {
        $user = User::factory()->make(['name' => 'Иван']);
        $comment = Comment::factory()->make([
            'film_id' => 1,
            'user_id' => null,
        ]);

        $comment->setRelation('user', $user);

        $this->assertSame('Иван', $comment->author_name);
    }

    /**
     * Verify that an anonymous comment uses the default guest name.
     */
    public function test_author_name_is_guest_when_uaser_is_missing(): void
    {
        $comment = Comment::factory()->make([
            'film_id' => 1,
            'user_id' => null,
        ]);

        $comment->setRelation('user', null);

        $this->assertSame('Гость', $comment->author_name);
    }
}
