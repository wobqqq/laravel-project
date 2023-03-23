<?php

declare(strict_types=1);

namespace App\Transformers\Admin;

use App\DTOs\Admin\Tag\TagDto;
use App\DTOs\Admin\Tag\TagFilterDto;
use App\Http\Requests\Admin\Tag\IndexRequest;
use App\Http\Requests\Admin\Tag\StoreRequest;
use App\Http\Requests\Admin\Tag\UpdateRequest;

class TagDtoTransformer
{
    public static function fromRequest(StoreRequest|UpdateRequest $request): TagDto
    {
        return new TagDto(
            $request->str('name')->toString()
        );
    }

    public static function fromFilter(IndexRequest $request): TagFilterDto
    {
        return new TagFilterDto(
            $request->page(),
            $request->sortBy(),
            $request->sortDirection(),
        );
    }
}
