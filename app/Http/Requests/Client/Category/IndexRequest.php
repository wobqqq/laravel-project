<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\Category;

use App\DTOs\Client\Category\CategoryFilterDto;
use App\Http\Requests\Traits\RetrieveInputItemFromRequest;
use App\Models\Category;
use App\Transformers\Client\CategoryDtoTransformer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Category|null $category
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

    public function getDto(): CategoryFilterDto
    {
        return CategoryDtoTransformer::fromFilter($this);
    }
}
