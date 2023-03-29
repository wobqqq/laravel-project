<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\IndexRequest;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use App\Queries\Admin\CategoryQuery;
use App\Services\CategoryService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes as ApiDocs;
use Symfony\Component\HttpFoundation\Response as HttpStatusCode;

#[ApiDocs\Group('Admin')]
#[ApiDocs\Subgroup('Category')]
#[ApiDocs\Authenticated]
class CategoryController extends Controller
{
    /**
     * Get categories
     */
    #[ApiDocs\QueryParam('page', 'integer', example: 1)]
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
     * Create category
     */
    #[ApiDocs\BodyParam('name', 'string', '', example: 'Et ipsum quo ipsam')]
    #[ApiDocs\BodyParam('slug', 'string', '', example: 'harum-velit-ex-et')]
    #[ApiDocs\ResponseFromApiResource(CategoryResource::class, Category::class, HttpStatusCode::HTTP_CREATED)]
    public function store(StoreRequest $request, CategoryService $categoryService): JsonResource
    {
        $dto = $request->getDto();
        $category = $categoryService->store($dto);

        return CategoryResource::make($category);
    }

    /**
     * Get category
     */
    #[ApiDocs\ResponseFromApiResource(CategoryResource::class, Category::class)]
    public function show(Category $category): JsonResource
    {
        return CategoryResource::make($category);
    }

    /**
     * Update category
     */
    #[ApiDocs\BodyParam('name', 'string', '', example: 'Et ipsum quo ipsam')]
    #[ApiDocs\BodyParam('slug', 'string', '', example: 'harum-velit-ex-et')]
    #[ApiDocs\ResponseFromApiResource(CategoryResource::class, Category::class)]
    public function update(
        UpdateRequest $request,
        Category $category,
        CategoryService $categoryService,
    ): JsonResource {
        $dto = $request->getDto();
        $category = $categoryService->update($dto, $category);

        return CategoryResource::make($category);
    }

    /**
     * Delete category
     */
    #[ApiDocs\Response(status: HttpStatusCode::HTTP_NO_CONTENT)]
    public function destroy(Category $category): Response
    {
        $category->delete();

        return response()->noContent();
    }
}
