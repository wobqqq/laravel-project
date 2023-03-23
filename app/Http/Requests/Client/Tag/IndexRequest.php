<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\Tag;

use App\DTOs\Client\Tag\TagFilterDto;
use App\Http\Requests\Traits\RetrieveInputItemFromRequest;
use App\Models\Tag;
use App\Transformers\Client\TagDtoTransformer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Tag|null $tag
 */
class IndexRequest extends FormRequest
{
    use RetrieveInputItemFromRequest;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'sort_by' => 'nullable|string',
            'sort_direction' => 'nullable|string',
        ];
    }

    public function getDto(): TagFilterDto
    {
        return TagDtoTransformer::fromFilter($this);
    }
}
