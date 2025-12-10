<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Enums\UserRole;
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
            'content' => fake()->paragraphs(3, true),
            'status' => fake()->randomElement(array_column(PostStatus::cases(), 'value')),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
            // Only assign created_by to users with ADMIN or EDITOR roles
            'created_by' => fake()->randomElement(User::whereIn('role', [UserRole::ADMIN, UserRole::EDITOR])->pluck('id')->toArray()),
        ];
    }
}
