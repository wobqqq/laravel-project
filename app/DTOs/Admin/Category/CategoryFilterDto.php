<?php

declare(strict_types=1);

namespace App\DTOs\Admin\Category;

class CategoryFilterDto
{
    public function __construct(
        public readonly int $page,
        public readonly string $sort_by,
        public readonly string $sort_direction,
    ) {
    }
}
