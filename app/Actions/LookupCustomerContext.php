<?php

namespace App\Actions;

use App\Models\Customer;

class LookupCustomerContext
{
    public function __invoke(string $emailOrDomain): ?Customer
    {
        return Customer::query()
            ->where('email', $emailOrDomain)
            ->orWhere('domain', $emailOrDomain)
            ->first();
    }
}
