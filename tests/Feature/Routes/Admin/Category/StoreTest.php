<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Category;

use App\Models\Category;
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
        $category = Category::factory()->makeOne();
        $headers = $this->getAuthorizationHeaders();

        $this->json('POST', '/api/v1/admin/categories', $category->toArray(), $headers)
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json
                ->whereNot('data.id', null)
                ->where('data.name', $category->name)
                ->whereNot('data.slug', null)
            );
    }

    /**
     * @test
     */
    public function return_invalid_error(): void
    {
        $headers = $this->getAuthorizationHeaders();

        $this->json('POST', '/api/v1/admin/categories', [], $headers)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
