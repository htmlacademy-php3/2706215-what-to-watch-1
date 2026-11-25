<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Film;
use Tests\TestCase;

class FilmTest extends TestCase
{
    /**
     * Verify that the film's rating is the average of its comment ratings.
     */
    public function test_rating_is_average_of_comment_ratings(): void
    {
        $film = Film::factory()->make();

        $firstComment = Comment::factory()->make([
            'film_id' => 1,
            'user_id' => null,
            'rating' => 6,
        ]);
        $secondComment = Comment::factory()->make([
            'film_id' => 1,
            'user_id' => null,
            'rating' => 9,
        ]);

        $film->setRelation('comments', $film->newCollection([
            $firstComment,
            $secondComment,
        ]));

        $this->assertSame(7.5, $film->rating);
    }

    /**
     * Verify that a film without comments has a zero rating.
     */
    public function test_rating_is_zero_when_film_has_no_comments(): void
    {
        $film = Film::factory()->make();

        $film->setRelation('comments', $film->newCollection());

        $this->assertSame(0.0, $film->rating);
    }
}
