<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Category;

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
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', '/api/v1/admin/categories', [], $headers)
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
        $headers = $this->getAuthorizationHeaders();

        $categories = Category::factory()
            ->count(20)
            ->create()
            ->sortByDesc('id')
            ->values()
            ->forPage(1, 15);

        $this->json('GET', '/api/v1/admin/categories', [], $headers)
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($categories) {
                /** @var Category $category */
                foreach ($categories as $i => $category) {
                    $json->where("data.{$i}.id", $category->id)
                        ->where("data.{$i}.name", $category->name)
                        ->where("data.{$i}.slug", $category->slug);
                }

                $json->has('links')
                    ->has('meta');
            });
    }
}
