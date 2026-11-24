<?php

namespace App\Http\Requests;

use App\Enum\FilmStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexFilmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!in_array($this->query('status'), [
            FilmStatus::PENDING->value,
            FilmStatus::MODERATE->value,
        ], true)) {
            return true;
        }

        return $this->user('sanctum')?->can('moderate') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'required', Rule::enum(FilmStatus::class)],
        ];
    }
}
