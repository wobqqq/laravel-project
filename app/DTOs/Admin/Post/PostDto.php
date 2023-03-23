<?php

declare(strict_types=1);

namespace App\DTOs\Admin\Post;

use Carbon\Carbon;

class PostDto
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $slug,
        public readonly int $status,
        public readonly int $category_id,
        public readonly string $preview_text,
        public readonly string $content,
        public readonly bool $is_hot,
        public readonly Carbon $published_at,
        /** @var int[]|array $tag_ids */
        public readonly array $tag_ids
    ) {
    }
}
