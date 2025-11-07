<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'requester_email' => fake()->safeEmail(),
            'requester_name' => fake()->name(),
            'status' => fake()->randomElement(['open', 'pending', 'closed']),
            'priority' => fake()->randomElement(['low', 'normal', 'high', 'urgent']),
            'last_public_reply_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'assigned_to' => fake()->name(),
        ];
    }

    public function slaAtRisk(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
            'last_public_reply_at' => now()->subDays(rand(2, 5)),
        ]);
    }
}
