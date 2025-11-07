<?php

namespace App\Actions;

use App\Models\Customer;
use App\Models\Ticket;

class ApplyCustomerContextToTicket
{
    public function __invoke(Ticket $ticket, Customer $customer): Ticket
    {
        $ticket->update([
            'tier' => $customer->plan,
            'customer_status' => $customer->status,
        ]);

        return $ticket->fresh();
    }
}
