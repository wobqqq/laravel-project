<?php

declare(strict_types=1);

namespace App\Transformers\Client;

use App\DTOs\Client\Tag\TagFilterDto;
use App\Http\Requests\Client\Tag\IndexRequest;

class TagDtoTransformer
{
    public static function fromFilter(IndexRequest $request): TagFilterDto
    {
        return new TagFilterDto(
            $request->sortBy(),
            $request->sortDirection(),
        );
    }
}
