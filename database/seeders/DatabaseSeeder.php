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
            $replyCount = fake()->randomElement([0, 1, 1, 2, 2, 3, 4]);
            $cursor = $ticket->created_at ?? now()->subDays(rand(1, 30));

            for ($i = 0; $i < $replyCount; $i++) {
                $cursor = (clone $cursor)->addMinutes(rand(30, 60 * 24));

                $isStaff = fake()->boolean(60);

                $reply = \App\Models\TicketReply::factory()
                    ->for($ticket)
                    ->state([
                        'author_email' => $isStaff ? 'support@helpspot.com' : $ticket->requester_email,
                        'is_staff' => $isStaff,
                    ])
                    ->create();

                $reply->created_at = $cursor;
                $reply->updated_at = $cursor;
                $reply->saveQuietly();
            }

            $lastReply = $ticket->replies()->latest('created_at')->first();
            $lastCustomerReply = $ticket->replies()->where('is_staff', false)->latest('created_at')->first();

            $ticket->forceFill([
                'last_public_reply_at' => $lastReply?->created_at ?? $ticket->last_public_reply_at,
                'last_customer_reply_at' => $lastCustomerReply?->created_at,
                'updated_at' => $lastReply?->created_at ?? $ticket->updated_at,
            ])->saveQuietly();
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
            ->create()
            ->each(function ($ticket) {
                $replyCount = rand(1, 4);
                $cursor = $ticket->created_at ?? now()->subDays(5);

                for ($i = 0; $i < $replyCount; $i++) {
                    $cursor = (clone $cursor)->addHours(rand(2, 24));

                    $isStaff = fake()->boolean(60);
                    $reply = \App\Models\TicketReply::factory()
                        ->for($ticket)
                        ->state([
                            'author_email' => $isStaff ? 'support@helpspot.com' : $ticket->requester_email,
                            'is_staff' => $isStaff,
                        ])
                        ->create();

                    $reply->created_at = $cursor;
                    $reply->updated_at = $cursor;
                    $reply->saveQuietly();
                }

                $lastReply = $ticket->replies()->latest('created_at')->first();
                $lastCustomerReply = $ticket->replies()->where('is_staff', false)->latest('created_at')->first();
                $ticket->forceFill([
                    'last_public_reply_at' => $lastReply?->created_at,
                    'last_customer_reply_at' => $lastCustomerReply?->created_at,
                    'updated_at' => $lastReply?->created_at,
                ])->saveQuietly();
            });

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
            ->create()
            ->each(function ($ticket) {
                $replyCount = rand(2, 6);
                $cursor = $ticket->created_at ?? now()->subDays(15);

                for ($i = 0; $i < $replyCount; $i++) {
                    $cursor = (clone $cursor)->addHours(rand(4, 36));

                    $isStaff = fake()->boolean(60);
                    $reply = \App\Models\TicketReply::factory()
                        ->for($ticket)
                        ->state([
                            'author_email' => $isStaff ? 'support@helpspot.com' : $ticket->requester_email,
                            'is_staff' => $isStaff,
                        ])
                        ->create();

                    $reply->created_at = $cursor;
                    $reply->updated_at = $cursor;
                    $reply->saveQuietly();
                }

                $lastReply = $ticket->replies()->latest('created_at')->first();
                $lastCustomerReply = $ticket->replies()->where('is_staff', false)->latest('created_at')->first();
                $ticket->forceFill([
                    'last_public_reply_at' => $lastReply?->created_at,
                    'last_customer_reply_at' => $lastCustomerReply?->created_at,
                    'updated_at' => $lastReply?->created_at,
                ])->saveQuietly();
            });

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
            ->create()
            ->each(function ($ticket) {
                // Weighted distribution of replies for realism
                $replyCount = fake()->randomElement([0, 0, 1, 1, 1, 2, 2, 3, 3, 4, 5, 6, 7]);
                $cursor = $ticket->created_at ?? now()->subDays(rand(1, 180));

                for ($i = 0; $i < $replyCount; $i++) {
                    $cursor = (clone $cursor)->addMinutes(rand(15, 60 * 72));

                    $isStaff = fake()->boolean(60);
                    $reply = \App\Models\TicketReply::factory()
                        ->for($ticket)
                        ->state([
                            'author_email' => $isStaff ? 'support@helpspot.com' : $ticket->requester_email,
                            'is_staff' => $isStaff,
                        ])
                        ->create();

                    $reply->created_at = $cursor;
                    $reply->updated_at = $cursor;
                    $reply->saveQuietly();
                }

                $lastReply = $ticket->replies()->latest('created_at')->first();
                $lastCustomerReply = $ticket->replies()->where('is_staff', false)->latest('created_at')->first();
                $ticket->forceFill([
                    'last_public_reply_at' => $lastReply?->created_at ?? $ticket->last_public_reply_at,
                    'last_customer_reply_at' => $lastCustomerReply?->created_at,
                    'updated_at' => max($ticket->updated_at, $lastReply?->created_at ?? $ticket->updated_at),
                ])->saveQuietly();
            });
    }
}
