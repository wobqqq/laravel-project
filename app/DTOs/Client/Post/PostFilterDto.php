<?php

declare(strict_types=1);

namespace App\DTOs\Client\Post;

class PostFilterDto
{
    public function __construct(
        public readonly int $page,
        public readonly string $sort_by,
        public readonly string $sort_direction,
        public readonly bool $is_hot,
        public readonly ?string $category,
        /** @var string[] */
        public readonly array $tags,
    ) {
    }
}
