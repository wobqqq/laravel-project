<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Tag;

use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
        $tag = Tag::factory()->create();
        $data = ['name' => $tag->name = fake()->text(20)];
        $headers = $this->getAuthorizationHeaders();

        $this->json('PUT', "/api/v1/admin/tags/{$tag->id}", $data, $headers)
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

        $this->json('PUT', '/api/v1/admin/tags/NOT_FOUND', [], $headers)
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
        $tag = Tag::factory()->create();
        $headers = $this->getAuthorizationHeaders();

        $this->json('PUT', "/api/v1/admin/tags/{$tag->id}", [], $headers)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
