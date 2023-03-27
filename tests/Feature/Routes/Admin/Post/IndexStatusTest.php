<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Post;

use App\Enums\PostStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IndexStatusTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function return_successful_result(): void
    {
        $headers = $this->getAuthorizationHeaders();

        $this->json('GET', '/api/v1/admin/posts/status', [], $headers)
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    PostStatus::ACTIVE->name => PostStatus::ACTIVE->value,
                    PostStatus::INACTIVE->name => PostStatus::INACTIVE->value,
                ],
            ]);
    }
}
