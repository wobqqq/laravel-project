<?php

declare(strict_types=1);

namespace App\Queries\Client;

use App\DTOs\Client\Category\CategoryFilterDto;
use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryQuery
{
    public function __construct(
        private readonly Category $category,
    ) {
    }

    /**
     * @return Collection<int, Category>
     */
    public function getFiltered(CategoryFilterDto $dto): Collection
    {
        return $this->category->orderBy($dto->sort_by, $dto->sort_direction)->get();
    }
}
