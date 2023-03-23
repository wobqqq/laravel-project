<?php

declare(strict_types=1);

namespace App\Transformers\Admin;

use App\DTOs\Admin\Category\CategoryDto;
use App\DTOs\Admin\Category\CategoryFilterDto;
use App\Http\Requests\Admin\Category\IndexRequest;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;

class CategoryDtoTransformer
{
    public static function fromRequest(StoreRequest|UpdateRequest $request): CategoryDto
    {
        return new CategoryDto(
            $request->str('name')->toString(),
            $request->stringOrNull('slug'),
        );
    }

    public static function fromFilter(IndexRequest $request): CategoryFilterDto
    {
        return new CategoryFilterDto(
            $request->page(),
            $request->sortBy(),
            $request->sortDirection(),
        );
    }
}
