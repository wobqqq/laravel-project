<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Tag;

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
        $tag = Tag::factory()->create();
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', "/api/v1/admin/tags/{$tag->id}", [], $headers)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.id', $tag->id)
                ->where('data.name', $tag->name)
            );
    }

    /**
     * @test
     */
    public function return_not_found_error(): void
    {
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', '/api/v1/admin/tags/NOT_FOUND', [], $headers)
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
            );
    }
}
