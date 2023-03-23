<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Category;

use App\DTOs\Admin\Category\CategoryDto;
use App\Http\Requests\Traits\RetrieveInputItemFromRequest;
use App\Models\Category;
use App\Transformers\Admin\CategoryDtoTransformer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Category|null $category
 */
class UpdateRequest extends FormRequest
{
    use RetrieveInputItemFromRequest;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($this->category?->id),
            ],
        ];
    }

    public function getDto(): CategoryDto
    {
        return CategoryDtoTransformer::fromRequest($this);
    }
}
