<?php

declare(strict_types=1);

namespace App\Http\Resources\Client;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /** @var Post */
    public $resource;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'category' => [
                'name' => $this->resource->category->name,
                'slug' => $this->resource->category->slug,
            ],
            'preview_text' => $this->resource->preview_text,
            'content' => $this->resource->content,
            'is_hot' => $this->resource->is_hot,
            'published_at' => $this->resource->published_at->toDateTimeString(),
            'tags' => $this->resource->tags->pluck('name'),
        ];
    }
}
