<?php

declare(strict_types=1);

namespace App\DTOs\Client\Tag;

class TagFilterDto
{
    public function __construct(
        public readonly string $sort_by,
        public readonly string $sort_direction,
    ) {
    }
}
