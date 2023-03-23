<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => config('api.api_email'),
            'email_verified_at' => now(),
            'password' => bcrypt(config('api.api_password')),
            'remember_token' => Str::random(10),
        ];
    }
}
