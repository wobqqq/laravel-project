<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Category\IndexRequest;
use App\Http\Resources\Client\CategoryResource;
use App\Models\Category;
use App\Queries\Client\CategoryQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes as ApiDocs;

#[ApiDocs\Group('Client')]
#[ApiDocs\Subgroup('Category')]
class CategoryController extends Controller
{
    /**
     * Get categories
     */
    #[ApiDocs\QueryParam('sort_by', 'string', example: 'id')]
    #[ApiDocs\QueryParam('sort_direction', 'string', description: 'Available "asc" or "desc"', example: 'desc')]
    #[ApiDocs\ResponseFromApiResource(CategoryResource::class, Category::class, collection: true)]
    public function index(CategoryQuery $query, IndexRequest $request): AnonymousResourceCollection
    {
        $dto = $request->getDto();
        $categories = $query->getFiltered($dto);

        return CategoryResource::collection($categories);
    }

    /**
     * Get category
     */
    #[ApiDocs\ResponseFromApiResource(CategoryResource::class, Category::class)]
    public function show(Category $category): JsonResource
    {
        return CategoryResource::make($category);
    }
}
