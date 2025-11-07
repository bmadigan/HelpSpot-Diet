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

        $tickets = \App\Models\Ticket::factory()
            ->count(10)
            ->recycle($customers)
            ->create();

        $tickets->each(function ($ticket) {
            \App\Models\TicketReply::factory()
                ->count(rand(0, 3))
                ->for($ticket)
                ->create();
        });

        \App\Models\Ticket::factory()
            ->count(3)
            ->recycle($customers)
            ->slaAtRisk()
            ->create();

        \App\Models\Ticket::factory()
            ->count(2)
            ->recycle($customers->where('plan', 'Enterprise'))
            ->create(['tier' => 'Enterprise']);
    }
}
