<?php

declare(strict_types=1);

namespace App\DTOs\Admin\Post;

class PostFilterDto
{
    public function __construct(
        public readonly int $page,
        public readonly string $sort_by,
        public readonly string $sort_direction,
    ) {
    }
}
