<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Tag;

use App\DTOs\Admin\Tag\TagDto;
use App\Models\Tag;
use App\Transformers\Admin\TagDtoTransformer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Tag|null $tag
 */
class UpdateRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags')->ignore($this->tag?->id),
            ],
        ];
    }

    public function getDto(): TagDto
    {
        return TagDtoTransformer::fromRequest($this);
    }
}
