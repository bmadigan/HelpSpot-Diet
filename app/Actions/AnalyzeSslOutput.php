<?php

namespace App\Actions;

use Illuminate\Support\Carbon;

class AnalyzeSslOutput
{
    public function __invoke(string $opensslOutput): array
    {
        $diagnosis = 'Unknown issue';
        $nextSteps = [];

        $text = strtolower($opensslOutput);

        // 1) Prefer explicit verify return code from OpenSSL if present.
        if (preg_match('/verify\s+return\s+code:\s*(\d+)/i', $opensslOutput, $m)) {
            $code = (int) ($m[1] ?? 0);
            if ($code === 0) {
                return [
                    'diagnosis' => 'SSL Certificate Valid',
                    'next_steps' => [
                        'Your SSL certificate appears to be configured correctly.',
                        'If issues persist, check SMTP/STARTTLS configuration or firewall rules.',
                    ],
                ];
            }

            // Map a few common OpenSSL verify codes
            $map = [
                10 => 'Expired Certificate',
                18 => 'Self-signed Certificate',
                20 => 'Missing/Untrusted Chain (unable to get local issuer)',
                21 => 'Missing/Untrusted Chain (unable to verify first cert)',
                62 => 'Hostname Mismatch', // when using -verify_hostname
            ];

            $diagnosis = $map[$code] ?? 'OpenSSL Verify Error (code '.$code.')';
            $nextSteps = $this->stepsFor($diagnosis);

            return ['diagnosis' => $diagnosis, 'next_steps' => $nextSteps];
        }

        // 2) Common textual matches (case-insensitive)
        if (str_contains($text, 'unable to get local issuer')) {
            $diagnosis = 'Missing/Untrusted Chain (unable to get local issuer)';
            $nextSteps = $this->stepsFor($diagnosis);
        } elseif (str_contains($text, 'unable to verify the first certificate')) {
            $diagnosis = 'Missing/Untrusted Chain (unable to verify first cert)';
            $nextSteps = $this->stepsFor($diagnosis);
        } elseif ($this->detectExpiredCertificate($opensslOutput)) {
            $diagnosis = 'Expired Certificate';
            $nextSteps = $this->stepsFor($diagnosis);
        } elseif ($this->detectHostnameMismatch($opensslOutput)) {
            $diagnosis = 'Hostname Mismatch';
            $nextSteps = $this->stepsFor($diagnosis);
        } elseif (preg_match('/self\s*signed\s*certificate/i', $opensslOutput)) {
            $diagnosis = 'Self-signed Certificate';
            $nextSteps = $this->stepsFor($diagnosis);
        } elseif (preg_match('/verify\s*error/i', $opensslOutput)) {
            $diagnosis = 'OpenSSL Verify Error';
            $nextSteps = $this->stepsFor($diagnosis);
        } elseif (preg_match('/verify\s*return\s*code:\s*0/i', $opensslOutput)) {
            $diagnosis = 'SSL Certificate Valid';
            $nextSteps = $this->stepsFor($diagnosis);
        } else {
            $diagnosis = 'Unable to Determine Issue';
            $nextSteps = $this->stepsFor($diagnosis);
        }

        return [
            'diagnosis' => $diagnosis,
            'next_steps' => $nextSteps,
        ];
    }

    private function detectExpiredCertificate(string $output): bool
    {
        // Typical verify message
        if (preg_match('/certificate\s+has\s+expired/i', $output)) {
            return true;
        }

        // Some outputs include notAfter/Not After or expire date
        if (preg_match('/not\s*after\s*[:=]\s*(.+)/i', $output, $m)) {
            $expiry = trim($m[1]);
            try {
                return Carbon::parse($expiry)->isPast();
            } catch (\Throwable) {
                // fall through
            }
        }

        if (preg_match('/expire\s*date\s*[:=]\s*(.+)/i', $output, $m)) {
            $expiry = trim($m[1]);
            try {
                return Carbon::parse($expiry)->isPast();
            } catch (\Throwable) {
                // fall through
            }
        }

        return false;
    }

    private function detectHostnameMismatch(string $output): bool
    {
        return (bool) preg_match('/hostname\s*mismatch/i', $output)
            || (bool) preg_match('/certificate\s+name\s+does\s+not\s+match/i', $output)
            || (bool) preg_match('/does\s+not\s+match\s+target\s+host/i', $output);
    }

    private function stepsFor(string $diagnosis): array
    {
        return match ($diagnosis) {
            'SSL Certificate Valid' => [
                'Your SSL certificate appears to be configured correctly.',
                'If issues persist, check SMTP/STARTTLS configuration or firewall rules.',
            ],
            'Expired Certificate' => [
                'Your SSL certificate has expired.',
                'Renew your certificate with your provider.',
                'Install the new certificate and full chain.',
                'Reload your web server.',
                'Verify: openssl s_client -connect host:443 -servername host | grep "Verify return code"',
            ],
            'Missing/Untrusted Chain (unable to get local issuer)',
            'Missing/Untrusted Chain (unable to verify first cert)' => [
                'Your certificate chain is missing or untrusted.',
                'Download the full chain (intermediates) from your CA.',
                'Ensure you are serving fullchain.pem (not only cert.pem).',
                'Reload your web server (nginx: sudo systemctl reload nginx).',
                'Test: openssl s_client -connect host:443 -servername host -showcerts 2>&1 | grep -i "Verify"',
            ],
            'Self-signed Certificate' => [
                'A self-signed certificate was detected.',
                'Obtain a certificate from a trusted CA (e.g. Let’s Encrypt).',
                'Install the new certificate and full chain.',
                'Reload your web server and test again.',
            ],
            'Hostname Mismatch' => [
                'The certificate CN/SAN does not match your hostname.',
                'Re-issue the certificate for the correct domain.',
                'Install the new certificate and reload your web server.',
                'Re-test with: openssl s_client -connect host:443 -servername host',
            ],
            default => [
                'No obvious SSL chain errors found.',
                'Check SMTP/STARTTLS configuration if email-related.',
                'Verify firewall rules allow port 443.',
                'Review server error logs for additional clues.',
            ],
        };
    }
}
