<?php

namespace Database\Factories;

use App\Enum\FilmStatus;
use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'imdb_id' => fake()->unique()->numerify('tt#######'),
            'poster_image' => fake()->imageUrl(),
            'preview_image' => fake()->imageUrl(),
            'background_image' => fake()->imageUrl(),
            'background_color' => fake()->hexColor(),
            'video_link' => fake()->url(),
            'preview_video_link' => fake()->url(),
            'description' => fake()->text(1000),
            'director' => fake()->name(),
            'starring' => [fake()->name(), fake()->name(), fake()->name()],
            'run_time' => fake()->numberBetween(60, 240),
            'released' => fake()->numberBetween(1900, now()->year),
            'status' => fake()->randomElement(FilmStatus::values()),
            'is_promo' => fake()->boolean(),
        ];
    }
}
