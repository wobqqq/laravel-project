<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Post;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function return_successful_result(): void
    {
        $post = Post::factory()
            ->has(Tag::factory()->count(3))
            ->makeOne();
        $headers = $this->getAuthorizationHeaders();

        $this->json('POST', '/api/v1/admin/posts', $post->toArray(), $headers)
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json
                ->whereNot('data.id', null)
                ->where('data.title', $post->title)
                ->whereNot('data.slug', null)
                ->where('data.status', $post->status->value)
                ->where('data.category_id', $post->category_id)
                ->where('data.preview_text', $post->preview_text)
                ->where('data.content', $post->content)
                ->where('data.is_hot', $post->is_hot)
                ->whereNot('data.published_at', null)
                ->where('data.tag_ids', $post->tags->pluck('id')->toArray())
            );
    }

    /**
     * @test
     */
    public function return_invalid_error(): void
    {
        $headers = $this->getAuthorizationHeaders();

        $this->json('POST', '/api/v1/admin/posts', [], $headers)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'title',
                'status',
                'category_id',
                'preview_text',
                'content',
                'is_hot',
                'published_at',
            ]);
    }
}
