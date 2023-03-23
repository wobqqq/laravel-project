<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\Post;

use App\DTOs\Client\Post\PostFilterDto;
use App\Http\Requests\Traits\RetrieveInputItemFromRequest;
use App\Models\Post;
use App\Transformers\Client\PostDtoTransformer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Post|null $post
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
            'page' => 'nullable|integer',
            'sort_by' => 'nullable|string',
            'sort_direction' => 'nullable|string',
            'is_hot' => 'nullable|boolean',
            'category' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'required|string',
        ];
    }

    public function getDto(): PostFilterDto
    {
        return PostDtoTransformer::fromFilter($this);
    }
}
