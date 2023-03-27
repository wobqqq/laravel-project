<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Post;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UpdateTest extends TestCase
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
        $data = [
            'title' => $post->title = fake()->text(20),
            'slug' => $post->slug = fake()->slug(),
            'status' => $post->status = PostStatus::INACTIVE,
            'category_id' => $post->category_id = (Category::factory()->create())->id,
            'preview_text' => $post->preview_text = fake()->text(1000),
            'content' => $post->content = fake()->text(4000),
            'is_hot' => $post->is_hot = fake()->boolean(20),
            'tag_ids' => [],
            'published_at' => $post->published_at = Carbon::parse(fake()->dateTime),
        ];
        $headers = $this->getAuthorizationHeaders();

        $this->json('PUT', "/api/v1/admin/posts/{$post->id}", $data, $headers)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.id', $post->id)
                ->where('data.title', $post->title)
                ->where('data.status', $post->status->value)
                ->where('data.slug', $post->slug)
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

        $this->json('PUT', '/api/v1/admin/posts/NOT_FOUND', [], $headers)
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
            );
    }

    /**
     * @test
     */
    public function return_invalid_error(): void
    {
        $post = Post::factory()->create();
        $headers = $this->getAuthorizationHeaders();

        $this->json('PUT', "/api/v1/admin/posts/{$post->id}", [], $headers)
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
