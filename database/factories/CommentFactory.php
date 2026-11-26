<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Film;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'film_id' => Film::factory(),
            'user_id' => User::factory(),
            'text' => fake()->realTextBetween(50, 400),
            'rating' => fake()->numberBetween(1, 10),
            'comment_id' => null,
        ];
    }
}
