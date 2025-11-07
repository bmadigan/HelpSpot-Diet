<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Tickets')]
class Inbox extends Component
{
    #[Url]
    public string $search = '';

    #[Url]
    public string $filter = 'all';

    #[Computed]
    public function tickets()
    {
        $query = Ticket::query()->with('customer');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', "%{$this->search}%")
                    ->orWhere('requester_email', 'like', "%{$this->search}%");
            });
        }

        return match ($this->filter) {
            'sla' => $query->slaAtRisk()->get(),
            'no-update' => $query->noUpdate48h()->get(),
            'vip' => $query->vip()->get(),
            default => $query->open()->latest()->get(),
        };
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function render()
    {
        return view('livewire.tickets.inbox');
    }
}
