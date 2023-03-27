<?php

declare(strict_types=1);

namespace Test\Feature\API\Client\Controllers\Tag;

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
        $this->json('GET', '/api/v1/client/tags')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('data', []));
    }

    /**
     * @test
     */
    public function return_successful_not_empty_result(): void
    {
        $tags = Tag::factory()
            ->count(20)
            ->create()
            ->sortBy('name')
            ->values();

        $this->json('GET', '/api/v1/client/tags')
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($tags) {
                /** @var Tag $tag */
                foreach ($tags as $i => $tag) {
                    $json->where("data.{$i}.name", $tag->name);
                }
            });
    }
}
