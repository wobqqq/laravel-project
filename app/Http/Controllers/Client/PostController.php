<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Post\IndexRequest;
use App\Http\Resources\Client\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes as ApiDocs;

#[ApiDocs\Group('Client')]
#[ApiDocs\Subgroup('Post')]
class PostController extends Controller
{
    /**
     * Get posts
     */
    #[ApiDocs\QueryParam('is_hot', 'boolean', example: 1)]
    #[ApiDocs\QueryParam('category', 'boolean', example: 'natus-ex-eos-rerum-incidunt-natus')]
    #[ApiDocs\QueryParam('tags', 'string[]', example: ['Voluptas.', 'Quo.'])]
    #[ApiDocs\QueryParam('page', 'integer', example: 1)]
    #[ApiDocs\QueryParam('sort_by', 'string', example: 'id')]
    #[ApiDocs\QueryParam('sort_direction', 'string', description: 'Available "asc" or "desc"', example: 'desc')]
    #[ApiDocs\ResponseFromApiResource(PostResource::class, Post::class, collection: true)]
    public function index(Post $post, IndexRequest $request): AnonymousResourceCollection
    {
        $dto = $request->getDto();
        $posts = $post->getClientFiltered($dto);

        return PostResource::collection($posts);
    }

    /**
     * Get post
     */
    #[ApiDocs\ResponseFromApiResource(PostResource::class, Post::class)]
    public function show(string $slug): JsonResource
    {
        $post = Post::whereSlug($slug)
            ->whereStatus(PostStatus::ACTIVE->value)
            ->firstOrFail();
        $post->load('category', 'tags');

        return PostResource::make($post);
    }
}
