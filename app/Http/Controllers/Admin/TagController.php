<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\IndexRequest;
use App\Http\Requests\Admin\Tag\StoreRequest;
use App\Http\Requests\Admin\Tag\UpdateRequest;
use App\Http\Resources\Admin\TagResource;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes as ApiDocs;
use Symfony\Component\HttpFoundation\Response as HttpStatusCode;

#[ApiDocs\Group('Admin')]
#[ApiDocs\Subgroup('Tag')]
#[ApiDocs\Authenticated]
class TagController extends Controller
{
    /**
     * Get tags
     */
    #[ApiDocs\QueryParam('page', 'integer', example: 1)]
    #[ApiDocs\QueryParam('sort_by', 'string', example: 'id')]
    #[ApiDocs\QueryParam('sort_direction', 'string', description: 'Available "asc" or "desc"', example: 'desc')]
    #[ApiDocs\ResponseFromApiResource(TagResource::class, Tag::class, collection: true)]
    public function index(Tag $tag, IndexRequest $request): AnonymousResourceCollection
    {
        $dto = $request->getDto();
        $tags = $tag->getAdminFiltered($dto);

        return TagResource::collection($tags);
    }

    /**
     * Create tag
     */
    #[ApiDocs\BodyParam('name', 'string', '', example: 'Magni')]
    #[ApiDocs\ResponseFromApiResource(TagResource::class, Tag::class, HttpStatusCode::HTTP_CREATED)]
    public function store(StoreRequest $request, TagService $tagService): JsonResource
    {
        $dto = $request->getDto();
        $tag = $tagService->store($dto);

        return TagResource::make($tag);
    }

    /**
     * Get tag
     */
    #[ApiDocs\ResponseFromApiResource(TagResource::class, Tag::class)]
    public function show(Tag $tag): JsonResource
    {
        return TagResource::make($tag);
    }

    /**
     * Update tag
     */
    #[ApiDocs\BodyParam('name', 'string', '', example: 'Magni')]
    #[ApiDocs\ResponseFromApiResource(TagResource::class, Tag::class)]
    public function update(UpdateRequest $request, Tag $tag, TagService $tagService): JsonResource
    {
        $dto = $request->getDto();
        $tag = $tagService->update($dto, $tag);

        return TagResource::make($tag);
    }

    /**
     * Delete tag
     */
    #[ApiDocs\Response(status: HttpStatusCode::HTTP_NO_CONTENT)]
    public function destroy(Tag $tag): Response
    {
        $tag->delete();

        return response()->noContent();
    }
}
