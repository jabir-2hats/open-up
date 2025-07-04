<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(5, true),
            'user_id' => User::factory(),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'status' => fake()->randomElement(['Active', 'Inactive']),
            'image_url' => null,
        ];
    }
}
