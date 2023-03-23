<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Admin\Category\CategoryDto;
use App\Models\Category;

class CategoryService
{
    public function store(CategoryDto $categoryDto): Category
    {
        return Category::create(['name' => $categoryDto->name]);
    }

    public function update(CategoryDto $categoryDto, Category $category): Category
    {
        $category->update([
            'name' => $categoryDto->name,
            'slug' => $categoryDto->slug,
        ]);

        return $category;
    }
}
