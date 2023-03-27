<?php

declare(strict_types=1);

namespace App\Transformers\Client;

use App\DTOs\Client\Post\PostFilterDto;
use App\Http\Requests\Client\Post\IndexRequest;

class PostDtoTransformer
{
    public static function fromFilter(IndexRequest $request): PostFilterDto
    {
        /** @var string[] $tags */
        $tags = $request->arrayOrNull('tags');

        return new PostFilterDto(
            $request->page(),
            $request->sortBy('published_at'),
            $request->sortDirection(),
            $request->boolean('is_hot'),
            $request->stringOrNull('category'),
            $tags,
        );
    }
}
