<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Post;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function return_successful_result(): void
    {
        $post = Post::factory()
            ->has(Tag::factory()->count(3))
            ->create();
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', "/api/v1/admin/posts/{$post->id}", [], $headers)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.id', $post->id)
                ->where('data.title', $post->title)
                ->where('data.slug', $post->slug)
                ->where('data.status', $post->status->value)
                ->where('data.category_id', $post->category_id)
                ->where('data.preview_text', $post->preview_text)
                ->where('data.content', $post->content)
                ->where('data.is_hot', $post->is_hot)
                ->where('data.published_at', $post->published_at->toDateTimeString())
                ->where('data.tag_ids', $post->tags->pluck('id')->toArray())
            );
    }

    /**
     * @test
     */
    public function return_not_found_error(): void
    {
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', '/api/v1/admin/posts/NOT_FOUND', [], $headers)
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
            );
    }
}
