<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $customers = \App\Models\Customer::factory()->count(15)->create();

        // Seed an initial batch of tickets linked to real customers with matching tiers.
        $tickets = \App\Models\Ticket::factory()
            ->count(10)
            ->state(function () use ($customers) {
                $customer = $customers->random();

                return [
                    'requester_email' => $customer->email,
                    'requester_name' => fake()->name(),
                    'tier' => $customer->plan,
                ];
            })
            ->create();

        $tickets->each(function ($ticket) {
            \App\Models\TicketReply::factory()
                ->count(rand(0, 3))
                ->for($ticket)
                ->create();
        });

        // A few SLA at risk tickets (older last reply)
        \App\Models\Ticket::factory()
            ->count(3)
            ->state(function () use ($customers) {
                $customer = $customers->random();

                return [
                    'requester_email' => $customer->email,
                    'tier' => $customer->plan,
                    'status' => 'open',
                    'last_public_reply_at' => now()->subDays(rand(2, 5)),
                ];
            })
            ->create();

        // A couple VIP/Enterprise tickets
        \App\Models\Ticket::factory()
            ->count(2)
            ->state(function () use ($customers) {
                $customer = $customers->where('plan', 'Enterprise')->random();

                return [
                    'requester_email' => $customer->email,
                    'tier' => 'Enterprise',
                ];
            })
            ->create();

        // Add 100 additional realistic help desk tickets (mix of new/old for reporting)
        \App\Models\Ticket::factory()
            ->count(100)
            ->state(function () use ($customers) {
                $customer = $customers->random();

                // Some older, some newer for reporting views
                $createdAt = fake()->dateTimeBetween('-180 days', 'now');
                $lastReply = fake()->boolean(85) ? fake()->dateTimeBetween($createdAt, 'now') : null;

                return [
                    'requester_email' => $customer->email,
                    'tier' => $customer->plan,
                    'created_at' => $createdAt,
                    'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
                    'last_public_reply_at' => $lastReply,
                    'status' => fake()->randomElement(['open', 'pending', 'closed']),
                    'priority' => fake()->randomElement(['low', 'normal', 'high', 'urgent']),
                ];
            })
            ->create();
    }
}
