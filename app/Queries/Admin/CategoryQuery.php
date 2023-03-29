<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\DTOs\Admin\Category\CategoryFilterDto;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryQuery
{
    public function __construct(
        private readonly Category $category,
    ) {
    }

    /**
     * @return LengthAwarePaginator<Category>
     */
    public function getFiltered(CategoryFilterDto $dto): LengthAwarePaginator
    {
        return $this->category->orderBy($dto->sort_by, $dto->sort_direction)
            ->paginate(page: $dto->page);
    }
}
