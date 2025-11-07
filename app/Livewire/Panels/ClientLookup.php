<?php

namespace App\Livewire\Panels;

use App\Actions\ApplyCustomerContextToTicket;
use App\Actions\LookupCustomerContext;
use App\Models\Customer;
use App\Models\Ticket;
use Livewire\Component;

class ClientLookup extends Component
{
    public Ticket $ticket;

    public string $emailOrDomain = '';

    public ?Customer $foundCustomer = null;

    public function mount(Ticket $ticket): void
    {
        $this->ticket = $ticket;
        $this->emailOrDomain = $ticket->requester_email;
    }

    public function lookup(): void
    {
        $this->foundCustomer = app(LookupCustomerContext::class)($this->emailOrDomain);

        if (! $this->foundCustomer) {
            $this->dispatch('toast', message: 'No customer found', type: 'warning');

            return;
        }

        app(ApplyCustomerContextToTicket::class)($this->ticket, $this->foundCustomer);

        $this->dispatch('customer-context-applied');
        $this->dispatch('toast', message: 'Customer context applied. Filters will now pick this up.');
    }

    public function render()
    {
        return view('livewire.panels.client-lookup');
    }
}
