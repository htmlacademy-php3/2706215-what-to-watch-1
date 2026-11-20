<?php

namespace App\Models;

use App\Enum\FilmStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string|null $name
 * @property string $imdb_id
 * @property string|null $poster_image
 * @property string|null $preview_image
 * @property string|null $background_image
 * @property string|null $background_color
 * @property string|null $video_link
 * @property string|null $preview_video_link
 * @property string|null $description
 * @property string|null $director
 * @property array|null $starring
 * @property int|null $run_time
 * @property int|null $released
 * @property FilmStatus $status
 * @property bool $is_promo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Genre[]|Collection $genres
 */
#[Fillable(['name', 'imdb_id', 'poster_image', 'preview_image', 'background_image', 'background_color', 'video_link', 'preview_video_link', 'description', 'director', 'starring', 'run_time', 'released', 'status', 'is_promo'])]
class Film extends Model
{
    /** @use HasFactory<\Database\Factories\FilmFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starring' => 'array',
            'status' => FilmStatus::class,
            'is_promo' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Genre, $this>
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'film_genre');
    }
}
