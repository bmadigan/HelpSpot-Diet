<?php

namespace App\Enums;

enum PlanTier: string
{
    case STARTER = 'Starter';
    case PRO = 'Pro';
    case ENTERPRISE = 'Enterprise';

    public function isVip(): bool
    {
        return $this === self::ENTERPRISE;
    }

    public function color(): string
    {
        return match ($this) {
            self::STARTER => 'gray',
            self::PRO => 'blue',
            self::ENTERPRISE => 'purple',
        };
    }
}
