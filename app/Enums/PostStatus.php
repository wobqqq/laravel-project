<?php

declare(strict_types=1);

namespace App\Enums;

enum PostStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
}
