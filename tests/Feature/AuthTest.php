<?php

declare(strict_types=1);

namespace Test\Feature\API\Admin\Controllers\Category;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function should_return_generated_token(): void
    {
        User::factory()->create();
        $data = [
            'email' => config('api.api_email'),
            'password' => config('api.api_password'),
        ];

        $this->json('POST', '/api/v1/auth/login', $data)
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    /**
     * @test
     */
    public function should_return_token_generation_unauthorized_error(): void
    {
        $data = [
            'email' => 'fake@example.net',
            'password' => 'wrong',
        ];

        $this->json('POST', '/api/v1/auth/login', $data)
            ->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function should_return_refreshed_token(): void
    {
        $headers = $this->getAuthorizationHeaders();

        $this->json('POST', '/api/v1/auth/refresh', [], $headers)
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    /**
     * @test
     */
    public function should_return_token_refresh_server_error_error(): void
    {
        $headers = ['Authorization' => 'fake'];

        $this->json('POST', '/api/v1/auth/refresh', [], $headers)
            ->assertServerError()
            ->assertJsonStructure(['message']);
    }
}
