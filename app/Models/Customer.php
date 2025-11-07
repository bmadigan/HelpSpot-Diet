<?php

namespace App\Models;

use App\Enums\CustomerStatus;
use App\Enums\PlanTier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'domain',
        'company_name',
        'plan',
        'status',
        'last_invoice_date',
    ];

    protected function casts(): array
    {
        return [
            'last_invoice_date' => 'date',
            'plan' => PlanTier::class,
            'status' => CustomerStatus::class,
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'requester_email', 'email');
    }
}
