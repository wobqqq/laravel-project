<?php

declare(strict_types=1);

namespace Test\Feature\API\Client\Controllers\Category;

use App\Models\Category;
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
        $category = Category::factory()->create();

        $this->json('GET', "/api/v1/client/categories/{$category->slug}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.name', $category->name)
                ->where('data.slug', $category->slug)
            );
    }

    /**
     * @test
     */
    public function return_not_found_error(): void
    {
        $this->json('GET', '/api/v1/client/categories/NOT_FOUND')
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
            );
    }
}
