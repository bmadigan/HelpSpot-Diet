<?php

namespace App\Models;

use App\Enums\CustomerStatus;
use App\Enums\PlanTier;
use App\Enums\Priority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'description',
        'requester_email',
        'requester_name',
        'status',
        'priority',
        'tier',
        'customer_status',
        'last_public_reply_at',
        'last_customer_reply_at',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'last_public_reply_at' => 'datetime',
            'last_customer_reply_at' => 'datetime',
            'status' => TicketStatus::class,
            'priority' => Priority::class,
            'tier' => PlanTier::class,
            'customer_status' => CustomerStatus::class,
        ];
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'requester_email', 'email');
    }

    public function scopeOpen(Builder $query): void
    {
        $query->whereIn('status', [TicketStatus::OPEN, TicketStatus::PENDING]);
    }

    public function scopeSlaAtRisk(Builder $query): void
    {
        $query->open()
            ->where('last_public_reply_at', '<', now()->subDay())
            ->orderBy('last_public_reply_at', 'asc');
    }

    public function scopeNoUpdate48h(Builder $query): void
    {
        $query->open()
            ->where('last_public_reply_at', '<', now()->subDays(2))
            ->orderBy('last_public_reply_at', 'asc');
    }

    public function scopeVip(Builder $query): void
    {
        $query->open()
            ->where('tier', PlanTier::ENTERPRISE);
    }
}
