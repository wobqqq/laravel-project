<?php

declare(strict_types=1);

namespace Test\Feature\API\Client\Controllers\Post;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function return_successful_empty_result(): void
    {
        $this->json('GET', '/api/v1/client/posts')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data', [])
                ->has('links')
                ->has('meta')
            );
    }

    /**
     * @test
     */
    public function return_successful_not_empty_result(): void
    {
        $posts = Post::factory()
            ->count(20)
            ->has(Tag::factory()->count(3))
            ->create()
            ->where('status', PostStatus::ACTIVE)
            ->sortByDesc('published_at')
            ->values()
            ->forPage(1, 15);

        $this->json('GET', '/api/v1/client/posts')
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($posts) {
                /** @var Post $post */
                foreach ($posts as $i => $post) {
                    $json->where("data.{$i}.title", $post->title)
                        ->where("data.{$i}.slug", $post->slug)
                        ->where("data.{$i}.category.name", $post->category->name)
                        ->where("data.{$i}.category.slug", $post->category->slug)
                        ->where("data.{$i}.preview_text", $post->preview_text)
                        ->where("data.{$i}.content", $post->content)
                        ->where("data.{$i}.is_hot", $post->is_hot)
                        ->where("data.{$i}.published_at", $post->published_at->toDateTimeString())
                        ->where("data.{$i}.tags", $post->tags->pluck('name')->toArray());
                }

                $json->has('links')
                    ->has('meta');
            });
    }

    /**
     * @test
     */
    public function return_filtered_list_by_is_hot_param(): void
    {
        $posts = Post::factory()
            ->count(20)
            ->create()
            ->where('status', PostStatus::ACTIVE)
            ->where('is_hot', true)
            ->forPage(1, 15);

        $query = http_build_query(['is_hot' => true]);

        $this->json('GET', "/api/v1/client/posts?{$query}")
            ->assertOk()
            ->assertJsonCount($posts->count(), 'data');
    }

    /**
     * @test
     */
    public function return_filtered_list_by_category_param(): void
    {
        $posts = Post::factory()
            ->count(20)
            ->create();

        /** @var Post $post */
        $post = $posts->first();
        $category = $post->category;

        $posts = $posts->where('status', PostStatus::ACTIVE)
            ->where('category_id', $category->id)
            ->forPage(1, 15);

        $query = http_build_query(['category' => $category->slug]);

        $this->json('GET', "/api/v1/client/posts?{$query}")
            ->assertOk()
            ->assertJsonCount($posts->count(), 'data');
    }

    /**
     * @test
     */
    public function return_filtered_list_by_tags_param(): void
    {
        $posts = Post::factory()
            ->has(Tag::factory()->count(3))
            ->count(20)
            ->create();

        /** @var Post $post */
        $post = $posts->where('status', PostStatus::ACTIVE)->first();
        /** @var Tag $tag */
        $tag = $post->tags->first();

        $posts = $posts->where('status', PostStatus::ACTIVE)
            ->where(function (Post $post) use ($tag) {
                return $post->tags->where('name', $tag->name)->count() > 0;
            })

            ->forPage(1, 15);

        $query = http_build_query(['tags' => [$tag->name]]);

        $this->json('GET', "/api/v1/client/posts?{$query}")
            ->assertOk()
            ->assertJsonCount($posts->count(), 'data');
    }
}
