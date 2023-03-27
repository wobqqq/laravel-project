<?php

declare(strict_types=1);

namespace Test\Feature\API\Client\Controllers\Tag;

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

        $this->json('GET', "/api/v1/client/tags/{$tag->id}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('data.name', $tag->name));
    }

    /**
     * @test
     */
    public function return_not_found_error(): void
    {
        $this->json('GET', '/api/v1/client/tags/NOT_FOUND')
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
            );
    }
}
