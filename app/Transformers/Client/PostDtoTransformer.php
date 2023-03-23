<?php

declare(strict_types=1);

namespace App\Transformers\Client;

use App\DTOs\Client\Post\PostFilterDto;
use App\Http\Requests\Client\Post\IndexRequest;

class PostDtoTransformer
{
    public static function fromFilter(IndexRequest $request): PostFilterDto
    {
        return new PostFilterDto(
            $request->page(),
            $request->sortBy(),
            $request->sortDirection(),
            $request->boolean('is_hot'),
            $request->stringOrNull('category'),
            $request->array('tags'),
        );
    }
}
