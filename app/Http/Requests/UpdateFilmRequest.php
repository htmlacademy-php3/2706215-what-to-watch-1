<?php

namespace App\Http\Requests;

use App\Enum\FilmStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFilmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'poster_image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'preview_image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'background_image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'background_color' => ['sometimes', 'hex_color'],
            'video_link' => ['sometimes', 'nullable', 'string', 'max:255'],
            'preview_video_link' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'director' => ['sometimes', 'nullable', 'string', 'max:255'],
            'starring' => ['sometimes', 'array'],
            'starring.*' => ['required', 'string', 'max:255'],
            'genre' => ['sometimes', 'array'],
            'genre.*' => ['required', 'string', 'max:255', 'distinct'],
            'released' => ['sometimes', 'integer', 'digits:4'],
            'run_time' => ['sometimes', 'integer'],
            'imdb_id' => [
                'required',
                'string',
                'max:255',
                'regex:/^tt[0-9]{7,}$/D',
                Rule::unique('films', 'imdb_id')->ignore($this->route('film')),
            ],
            'status' => ['required', Rule::enum(FilmStatus::class)],
        ];
    }
}
