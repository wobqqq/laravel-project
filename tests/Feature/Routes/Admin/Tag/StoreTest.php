<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Tag;

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
        $tag = Tag::factory()->makeOne();
        $headers = $this->getAuthorizationHeaders();

        $this->json('POST', '/api/v1/admin/tags', $tag->toArray(), $headers)
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json
                ->whereNot('data.id', null)
                ->where('data.name', $tag->name)
            );
    }

    /**
     * @test
     */
    public function return_invalid_error(): void
    {
        $headers = $this->getAuthorizationHeaders();

        $this->json('POST', '/api/v1/admin/tags', [], $headers)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
