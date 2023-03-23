<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Post;

use App\DTOs\Admin\Post\PostDto;
use App\Enums\PostStatus;
use App\Http\Requests\Traits\RetrieveInputItemFromRequest;
use App\Models\Post;
use App\Transformers\Admin\PostDtoTransformer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Post|null $post
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
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts')->ignore($this->post?->id),
            ],
            'status' => [
                'required',
                Rule::enum(PostStatus::class),
            ],
            'category_id' => 'required|integer|exists:categories,id',
            'preview_text' => 'required|string|max:1000',
            'content' => 'required|string|max:65535',
            'is_hot' => 'required|boolean|max:65535',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'required|integer|exists:tags,id',
            'published_at' => 'required|date',
        ];
    }

    public function getDto(): PostDto
    {
        return PostDtoTransformer::fromRequest($this);
    }
}
