<?php

declare(strict_types=1);

namespace App\DTOs\Admin\Category;

class CategoryDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $slug
    ) {
    }
}
