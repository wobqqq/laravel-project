<?php

declare(strict_types=1);

namespace App\Transformers\Admin;

use App\DTOs\Admin\Post\PostDto;
use App\DTOs\Admin\Post\PostFilterDto;
use App\Enums\PostStatus;
use App\Http\Requests\Admin\Post\IndexRequest;
use App\Http\Requests\Admin\Post\StoreRequest;
use App\Http\Requests\Admin\Post\UpdateRequest;
use Carbon\Carbon;

class PostDtoTransformer
{
    public static function fromRequest(StoreRequest|UpdateRequest $request): PostDto
    {
        return new PostDto(
            $request->str('title')->toString(),
            $request->stringOrNull('slug'),
            $request->integer('status', PostStatus::INACTIVE),
            $request->integer('category_id'),
            $request->str('preview_text')->toString(),
            $request->str('content')->toString(),
            $request->boolean('is_hot'),
            $request->date('published_at') ?? Carbon::now(),
            $request->array('tag_ids', []),
        );
    }

    public static function fromFilter(IndexRequest $request): PostFilterDto
    {
        return new PostFilterDto(
            $request->page(),
            $request->sortBy(),
            $request->sortDirection(),
        );
    }
}
