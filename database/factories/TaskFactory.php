<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Task\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'user_id' => User::factory(),
            'description' => $this->faker->optional(0.3)->paragraph(),
            'status' => $this->faker->randomElement(TaskStatus::toArray()),
            'due_date' => $this->faker->optional(0.3)->date('Y-m-d'),
        ];
    }
}
