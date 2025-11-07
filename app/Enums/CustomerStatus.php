<?php

namespace App\Enums;

enum CustomerStatus: string
{
    case ACTIVE = 'active';
    case TRIAL = 'trial';
    case CHURNED = 'churned';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::TRIAL => 'Trial',
            self::CHURNED => 'Churned',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'green',
            self::TRIAL => 'yellow',
            self::CHURNED => 'red',
        };
    }
}
