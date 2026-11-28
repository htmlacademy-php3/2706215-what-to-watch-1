<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user('sanctum');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'imdb_id' => $this->imdb_id,
            'poster_image' => $this->poster_image,
            'preview_image' => $this->preview_image,
            'background_image' => $this->background_image,
            'background_color' => $this->background_color,
            'video_link' => $this->video_link,
            'preview_video_link' => $this->preview_video_link,
            'description' => $this->description,
            'director' => $this->director,
            'starring' => $this->starring,
            'run_time' => $this->run_time,
            'released' => $this->released,
            'status' => $this->status,
            'genres' => GenreResource::collection($this->genres),
            'rating' => $this->rating,
            'scores_count' => $this->comments->whereNotNull('rating')->count(),
            'is_favorite' => $this->when(
                $user !== null,
                fn (): bool => $user->favorites()->whereKey($this->id)->exists(),
            ),
        ];
    }
}
