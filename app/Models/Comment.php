<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $film_id
 * @property int|null $user_id
 * @property string $text
 * @property int|null $rating
 * @property int|null $comment_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $author_name
 * @property-read Film $film
 * @property-read User|null $user
 * @property-read Comment|null $parent
 * @property-read Comment[]|Collection $replies
 */
#[Fillable(['film_id', 'user_id', 'text', 'rating', 'comment_id'])]
class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Film, $this>
     */
    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Comment, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'comment_id');
    }

    /**
     * @return HasMany<Comment, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'comment_id');
    }

    /**
     * Get the author's name when reading the author_name property.
     *
     * @return Attribute<string, never>
     */
    protected function authorName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->user?->name ?? 'Гость'
        );
    }
}
