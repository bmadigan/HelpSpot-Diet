<?php

namespace App\Actions;

use Carbon\Carbon;

class AnalyzeSslOutput
{
    public function __invoke(string $opensslOutput): array
    {
        $diagnosis = 'Unknown issue';
        $nextSteps = [];

        if (str_contains($opensslOutput, 'verify error') &&
            str_contains($opensslOutput, 'unable to get local issuer')) {
            $diagnosis = 'Missing Intermediate Certificate';
            $nextSteps = [
                'Your certificate chain is missing an intermediate certificate.',
                'Download the full chain from your certificate provider.',
                'Replace fullchain.pem with the complete chain.',
                'Reload your web server (nginx: sudo systemctl reload nginx).',
                'Test: openssl s_client -connect host:443 -servername host',
            ];
        } elseif ($this->detectExpiredCertificate($opensslOutput)) {
            $diagnosis = 'Expired Certificate';
            $nextSteps = [
                'Your SSL certificate has expired.',
                'Renew your certificate with your provider.',
                'Install the new certificate and full chain.',
                'Reload your web server.',
                'Verify: openssl s_client -connect host:443 -servername host | grep "Verify return code"',
            ];
        } elseif ($this->detectHostnameMismatch($opensslOutput)) {
            $diagnosis = 'Hostname Mismatch';
            $nextSteps = [
                'The certificate CN/SAN does not match your hostname.',
                'Re-issue the certificate for the correct domain.',
                'Install the new certificate.',
                'Reload your web server.',
            ];
        } elseif (str_contains($opensslOutput, 'Verify return code: 0')) {
            $diagnosis = 'SSL Certificate Valid';
            $nextSteps = [
                'Your SSL certificate appears to be configured correctly.',
                'If you\'re experiencing issues, check SMTP/STARTTLS configuration or firewall rules.',
            ];
        } else {
            $diagnosis = 'Unable to Determine Issue';
            $nextSteps = [
                'No obvious SSL chain errors found.',
                'Check SMTP/STARTTLS configuration if email-related.',
                'Verify firewall rules allow port 443.',
                'Review server error logs for additional clues.',
            ];
        }

        return [
            'diagnosis' => $diagnosis,
            'next_steps' => $nextSteps,
        ];
    }

    private function detectExpiredCertificate(string $output): bool
    {
        if (preg_match('/notAfter=(.+)/', $output, $matches)) {
            $expiryDate = Carbon::parse($matches[1]);

            return $expiryDate->isPast();
        }

        return str_contains($output, 'certificate has expired');
    }

    private function detectHostnameMismatch(string $output): bool
    {
        return str_contains($output, 'hostname mismatch') ||
               str_contains($output, 'certificate name does not match');
    }
}
