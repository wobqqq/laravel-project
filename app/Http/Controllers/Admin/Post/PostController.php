<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\IndexRequest;
use App\Http\Requests\Admin\Post\StoreRequest;
use App\Http\Requests\Admin\Post\UpdateRequest;
use App\Http\Resources\Admin\Post\PostResource;
use App\Models\Post;
use App\Queries\Admin\PostQuery;
use App\Services\PostService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes as ApiDocs;
use Symfony\Component\HttpFoundation\Response as HttpStatusCode;

#[ApiDocs\Group('Admin')]
#[ApiDocs\Subgroup('Post')]
#[ApiDocs\Authenticated]
class PostController extends Controller
{
    /**
     * Get posts
     */
    #[ApiDocs\QueryParam('page', 'integer', example: 1)]
    #[ApiDocs\QueryParam('sort_by', 'string', example: 'id')]
    #[ApiDocs\QueryParam('sort_direction', 'string', description: 'Available "asc" or "desc"', example: 'desc')]
    #[ApiDocs\ResponseFromApiResource(PostResource::class, Post::class, collection: true)]
    public function index(PostQuery $query, IndexRequest $request): AnonymousResourceCollection
    {
        $dto = $request->getDto();
        $posts = $query->getFiltered($dto);

        return PostResource::collection($posts);
    }

    /**
     * Create post
     */
    #[ApiDocs\BodyParam('title', 'string', '', example: 'Suscipit id nulla')]
    #[ApiDocs\BodyParam('status', 'integer', '', example: 1)]
    #[ApiDocs\BodyParam('category_id', 'integer', '', example: 1)]
    #[ApiDocs\BodyParam('preview_text', 'string', '', example: 'Quo voluptatibus numquam impedit consequatur nemo perspiciatis asperiores.')]
    #[ApiDocs\BodyParam('content', 'string', '', example: 'Et est saepe sapiente id qui. Magnam et sequi error eos enim non praesentium.')]
    #[ApiDocs\BodyParam('is_hot', 'boolean', '', example: true)]
    #[ApiDocs\BodyParam('tag_ids', 'integer[]', '', false, '1, 2, 3')]
    #[ApiDocs\BodyParam('published_at', 'string', '', example: '2021-06-22 10:29:39')]
    #[ApiDocs\ResponseFromApiResource(PostResource::class, Post::class, HttpStatusCode::HTTP_CREATED)]
    public function store(StoreRequest $request, PostService $postService): JsonResource
    {
        $dto = $request->getDto();
        $post = $postService->store($dto);

        return $this->postResponse($post);
    }

    /**
     * Get post
     */
    #[ApiDocs\ResponseFromApiResource(PostResource::class, Post::class)]
    public function show(Post $post): JsonResource
    {
        return $this->postResponse($post);
    }

    /**
     * Update post
     */
    #[ApiDocs\BodyParam('title', 'string', '', example: 'Suscipit id nulla')]
    #[ApiDocs\BodyParam('slug', 'string', '', example: 'qui-cum-laboriosam-eum-nostrum-deleniti')]
    #[ApiDocs\BodyParam('status', 'integer', '', example: 1)]
    #[ApiDocs\BodyParam('category_id', 'integer', '', example: 1)]
    #[ApiDocs\BodyParam('preview_text', 'string', '', example: 'Quo voluptatibus numquam impedit consequatur nemo perspiciatis asperiores.')]
    #[ApiDocs\BodyParam('content', 'string', '', example: 'Et est saepe sapiente id qui. Magnam et sequi error eos enim non praesentium.')]
    #[ApiDocs\BodyParam('is_hot', 'boolean', '', example: true)]
    #[ApiDocs\BodyParam('tag_ids', 'integer[]', '', false, '1, 2, 3')]
    #[ApiDocs\BodyParam('published_at', 'string', '', example: '2021-06-22 10:29:39')]
    #[ApiDocs\ResponseFromApiResource(PostResource::class, Post::class)]
    public function update(UpdateRequest $request, Post $post, PostService $postService): JsonResource
    {
        $dto = $request->getDto();
        $post = $postService->update($dto, $post);

        return $this->postResponse($post);
    }

    /**
     * Delete post
     */
    #[ApiDocs\Response(status: HttpStatusCode::HTTP_NO_CONTENT)]
    public function destroy(Post $post): Response
    {
        $post->delete();

        return response()->noContent();
    }

    private function postResponse(Post $post): JsonResource
    {
        $post->load('tags');

        return PostResource::make($post);
    }
}
