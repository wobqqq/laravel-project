<?php

declare(strict_types=1);

namespace Test\Feature\API\Client\Controllers\Post;

use App\Enums\PostStatus;
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
            ->create(['status' => PostStatus::ACTIVE->value]);

        $this->json('GET', "/api/v1/client/posts/{$post->slug}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.title', $post->title)
                ->where('data.slug', $post->slug)
                ->where('data.category.name', $post->category->name)
                ->where('data.category.slug', $post->category->slug)
                ->where('data.preview_text', $post->preview_text)
                ->where('data.content', $post->content)
                ->where('data.is_hot', $post->is_hot)
                ->where('data.published_at', $post->published_at->toDateTimeString())
                ->where('data.tags', $post->tags->pluck('name')->toArray())
            );
    }

    /**
     * @test
     */
    public function return_not_found_error(): void
    {
        $this->json('GET', '/api/v1/client/posts/NOT_FOUND')
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
            );
    }

    /**
     * @test
     */
    public function return_not_found_error_for_inactive_element(): void
    {
        $post = Post::factory()
            ->create(['status' => PostStatus::INACTIVE->value]);

        $this->json('GET', "/api/v1/client/posts/{$post->slug}")
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
            );
    }
}
