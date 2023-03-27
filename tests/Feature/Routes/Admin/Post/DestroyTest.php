<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Post;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function return_successful_result(): void
    {
        $post = Post::factory()->create();
        $headers = $this->getAuthorizationHeaders();

        $this->json('DELETE', "/api/v1/admin/posts/{$post->id}", [], $headers)
            ->assertNoContent();
    }

    /**
     * @test
     */
    public function return_not_found_error(): void
    {
        $headers = $this->getAuthorizationHeaders();

        $this->json('DELETE', '/api/v1/admin/posts/NOT_FOUND', [], $headers)
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
            );
    }
}
