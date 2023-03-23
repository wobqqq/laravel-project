<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
final class PostFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var Category $category */
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();

        return [
            'title' => fake()->text(20),
            'slug' => fake()->slug(),
            'status' => fake()->randomElement([PostStatus::ACTIVE, PostStatus::INACTIVE]),
            'category_id' => $category->id,
            'preview_text' => fake()->text(500),
            'content' => fake()->text(3000),
            'is_hot' => fake()->boolean(20),
            'published_at' => fake()->dateTimeBetween('-2 years'),
        ];
    }
}
