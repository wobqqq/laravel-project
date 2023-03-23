<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Tag\IndexRequest;
use App\Http\Resources\Client\TagResource;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes as ApiDocs;

#[ApiDocs\Group('Client')]
#[ApiDocs\Subgroup('Tag')]
class TagController extends Controller
{
    /**
     * Get tags
     */
    #[ApiDocs\QueryParam('sort_by', 'string', example: 'id')]
    #[ApiDocs\QueryParam('sort_direction', 'string', description: 'Available "asc" or "desc"', example: 'desc')]
    #[ApiDocs\ResponseFromApiResource(TagResource::class, Tag::class, collection: true)]
    public function index(Tag $tag, IndexRequest $request): AnonymousResourceCollection
    {
        $dto = $request->getDto();
        $tags = $tag->getClientFiltered($dto);

        return TagResource::collection($tags);
    }

    /**
     * Get tag
     */
    #[ApiDocs\ResponseFromApiResource(TagResource::class, Tag::class)]
    public function show(Tag $tag): JsonResource
    {
        return TagResource::make($tag);
    }
}
