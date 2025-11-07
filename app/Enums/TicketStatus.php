<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = 'open';
    case PENDING = 'pending';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::PENDING => 'Pending',
            self::CLOSED => 'Closed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OPEN => 'blue',
            self::PENDING => 'yellow',
            self::CLOSED => 'gray',
        };
    }
}
