<?php

namespace App\Models;

use Database\Factories\GenreFactory;
// use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Film[]|Collection $films
 */
#[Fillable(['name'])]
// API fields are defined by resources instead of hidden.
// #[Hidden(['created_at', 'updated_at'])]
class Genre extends Model
{
    /** @use HasFactory<GenreFactory> */
    use HasFactory;

    /**
     * @return BelongsToMany<Film, $this>
     */
    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'film_genre');
    }
}
