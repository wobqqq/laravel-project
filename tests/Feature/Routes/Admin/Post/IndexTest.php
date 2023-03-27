<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Post;

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
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', '/api/v1/admin/posts', [], $headers)
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
            ->sortByDesc('id')
            ->values()
            ->forPage(1, 15);
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', '/api/v1/admin/posts', [], $headers)
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($posts) {
                /** @var Post $post */
                foreach ($posts as $i => $post) {
                    $json->where("data.{$i}.id", $post->id)
                        ->where("data.{$i}.title", $post->title)
                        ->where("data.{$i}.slug", $post->slug)
                        ->where("data.{$i}.status", $post->status->value)
                        ->where("data.{$i}.category_id", $post->category_id)
                        ->where("data.{$i}.preview_text", $post->preview_text)
                        ->where("data.{$i}.content", $post->content)
                        ->where("data.{$i}.is_hot", $post->is_hot)
                        ->where("data.{$i}.published_at", $post->published_at->toDateTimeString())
                        ->where("data.{$i}.tag_ids", $post->tags->pluck('id')->toArray());
                }

                $json->has('links')
                    ->has('meta');
            });
    }
}
