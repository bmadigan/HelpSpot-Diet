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
        $subjects = [
            'Cannot log in to portal',
            'Password reset link not working',
            'Billing discrepancy on latest invoice',
            'Emails not sending from app',
            'App is timing out behind proxy',
            'SSO SAML assertion error',
            'Webhook failing with 400 Bad Request',
            'SSL certificate expired warning',
            'Analytics dashboard not loading data',
            'Attachment upload fails over 10MB',
            'Search returns incomplete results',
            'Two-factor authentication codes not accepted',
            'User role permissions incorrect',
            'Incoming email parser dropping messages',
            'Outbound email DKIM failing',
            'Custom domain not verifying',
            'Mobile app push notifications delayed',
            'Import stuck in processing',
            'Automation rule not triggering',
            'Email template variables not rendering',
        ];

        $descriptions = [
            "Steps to reproduce:\n1) Go to Settings > Security\n2) Attempt action\nExpected: Success\nActual: Fails with error.",
            'Customer reports timeouts from office network. Works from home. Possible firewall/proxy issue.',
            'Observed bounce logs show SPF/DKIM failures for sender domain. Requesting review and fix.',
            'User cannot access account after password reset. Token likely expired. Please investigate.',
            'Report shows zero data after import. Source CSV attached. Need validation and re-run.',
        ];

        $createdAt = fake()->dateTimeBetween('-180 days', 'now');
        $lastReply = fake()->boolean(85) ? fake()->dateTimeBetween($createdAt, 'now') : null;

        return [
            'subject' => fake()->randomElement($subjects),
            'description' => fake()->randomElement($descriptions),
            'requester_email' => fake()->safeEmail(),
            'requester_name' => fake()->name(),
            'status' => fake()->randomElement(['open', 'pending', 'closed']),
            'priority' => fake()->randomElement(['low', 'normal', 'high', 'urgent']),
            'last_public_reply_at' => $lastReply,
            'assigned_to' => fake()->name(),
            'created_at' => $createdAt,
            'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
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
