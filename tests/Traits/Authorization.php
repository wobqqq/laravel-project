<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;

trait Authorization
{
    /**
     * @return array<string,string>
     */
    public function getAuthorizationHeaders(?User $user = null): array
    {
        /** @var JWTSubject $user */
        $user = $user ?? User::factory()->create();
        $token = JWTAuth::fromUser($user);

        return ['Authorization' => 'Bearer ' . $token];
    }
}
