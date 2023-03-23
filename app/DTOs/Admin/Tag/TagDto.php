<?php

declare(strict_types=1);

namespace App\DTOs\Admin\Tag;

class TagDto
{
    public function __construct(
        public readonly string $name
    ) {
    }
}
