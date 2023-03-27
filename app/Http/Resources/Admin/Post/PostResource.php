<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin\Post;

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
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'status' => $this->resource->status,
            'category_id' => $this->resource->category_id,
            'preview_text' => $this->resource->preview_text,
            'content' => $this->resource->content,
            'is_hot' => $this->resource->is_hot,
            'published_at' => $this->resource->published_at->toDateTimeString(),
            'tag_ids' => $this->resource->tags->pluck('id'),
        ];
    }
}
