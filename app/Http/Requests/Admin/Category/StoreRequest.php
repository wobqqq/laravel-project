<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Category;

use App\DTOs\Admin\Category\CategoryDto;
use App\Http\Requests\Traits\RetrieveInputItemFromRequest;
use App\Transformers\Admin\CategoryDtoTransformer;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use RetrieveInputItemFromRequest;

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|string>
     */
    public function rules(): array
    {
        return ['name' => 'required|string|max:255'];
    }

    public function getDto(): CategoryDto
    {
        return CategoryDtoTransformer::fromRequest($this);
    }
}
