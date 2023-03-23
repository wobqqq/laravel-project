<?php

declare(strict_types=1);

namespace App\Transformers\Client;

use App\DTOs\Client\Category\CategoryFilterDto;
use App\Http\Requests\Client\Category\IndexRequest;

class CategoryDtoTransformer
{
    public static function fromFilter(IndexRequest $request): CategoryFilterDto
    {
        return new CategoryFilterDto(
            $request->sortBy(),
            $request->sortDirection(),
        );
    }
}
