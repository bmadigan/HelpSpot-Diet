<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketReply>
 */
class TicketReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isStaff = fake()->boolean(60);

        return [
            'body' => fake()->paragraphs(rand(1, 3), true),
            'author_email' => $isStaff ? 'support@helpspot.com' : fake()->safeEmail(),
            'author_name' => $isStaff ? fake()->randomElement(['Support Team', 'Sarah Johnson', 'Mike Chen']) : fake()->name(),
            'is_public' => true,
            'is_staff' => $isStaff,
        ];
    }
}
