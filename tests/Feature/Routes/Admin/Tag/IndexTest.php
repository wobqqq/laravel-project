<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Tag;

use App\Models\Tag;
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

        $this->json('GET', '/api/v1/admin/tags', [], $headers)
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
        $tags = Tag::factory()
            ->count(20)
            ->create()
            ->sortByDesc('id')
            ->values()
            ->forPage(1, 15);
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', '/api/v1/admin/tags', [], $headers)
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($tags) {
                /** @var Tag $tag */
                foreach ($tags as $i => $tag) {
                    $json->where("data.{$i}.id", $tag->id)
                        ->where("data.{$i}.name", $tag->name);
                }

                $json->has('links')
                    ->has('meta');
            });
    }
}
