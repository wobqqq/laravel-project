<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Category;

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
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', "/api/v1/admin/categories/{$category->id}", [], $headers)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.id', $category->id)
                ->where('data.name', $category->name)
                ->where('data.slug', $category->slug)
            );
    }

    /**
     * @test
     */
    public function return_not_found_error(): void
    {
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', '/api/v1/admin/categories/NOT_FOUND', [], $headers)
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
            );
    }
}
