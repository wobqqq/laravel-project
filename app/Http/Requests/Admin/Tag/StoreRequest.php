<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Tag;

use App\DTOs\Admin\Tag\TagDto;
use App\Transformers\Admin\TagDtoTransformer;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|string>
     */
    public function rules(): array
    {
        return ['name' => 'required|string|max:255|unique:tags'];
    }

    public function getDto(): TagDto
    {
        return TagDtoTransformer::fromRequest($this);
    }
}
