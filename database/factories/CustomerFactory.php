<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = fake()->company();
        $domain = \Illuminate\Support\Str::slug($company).'.com';

        return [
            'email' => fake()->unique()->companyEmail(),
            'domain' => $domain,
            'company_name' => $company,
            'plan' => fake()->randomElement(['Starter', 'Pro', 'Enterprise']),
            'status' => fake()->randomElement(['active', 'trial', 'churned']),
            'last_invoice_date' => fake()->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
