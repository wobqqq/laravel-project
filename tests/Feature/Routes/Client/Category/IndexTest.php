<?php

declare(strict_types=1);

namespace Test\Feature\API\Client\Controllers\Category;

use App\Models\Category;
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
        $this->json('GET', '/api/v1/client/categories')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('data', []));
    }

    /**
     * @test
     */
    public function return_successful_not_empty_result(): void
    {
        $categories = Category::factory()
            ->count(20)
            ->create()
            ->sortBy('name')
            ->values();

        $this->json('GET', '/api/v1/client/categories')
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($categories) {
                /** @var Category $category */
                foreach ($categories as $i => $category) {
                    $json->where("data.{$i}.name", $category->name)
                        ->where("data.{$i}.slug", $category->slug);
                }
            });
    }
}
