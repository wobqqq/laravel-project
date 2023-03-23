<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Category;

use App\DTOs\Admin\Category\CategoryFilterDto;
use App\Http\Requests\Traits\RetrieveInputItemFromRequest;
use App\Models\Category;
use App\Transformers\Admin\CategoryDtoTransformer;
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
            'page' => 'nullable|integer',
            'sort_by' => 'nullable|string',
            'sort_direction' => 'nullable|string',
        ];
    }

    public function getDto(): CategoryFilterDto
    {
        return CategoryDtoTransformer::fromFilter($this);
    }
}
