<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Ticket $ticket;

    public string $replyBody = '';

    public function mount(Ticket $ticket): void
    {
        $this->ticket = $ticket->load('replies', 'customer');
    }

    #[On('customer-context-applied')]
    public function refreshTicket(): void
    {
        $this->ticket = $this->ticket->fresh(['customer']);
    }

    public function addReply(): void
    {
        $this->validate([
            'replyBody' => 'required|min:10',
        ]);

        $this->ticket->replies()->create([
            'body' => $this->replyBody,
            'author_email' => 'support@helpspot.com',
            'author_name' => 'Support Team',
            'is_public' => true,
            'is_staff' => true,
        ]);

        $this->ticket->update([
            'last_public_reply_at' => now(),
        ]);

        $this->replyBody = '';
        $this->ticket = $this->ticket->fresh(['replies']);

        $this->dispatch('toast', message: 'Reply added successfully');
    }

    public function render()
    {
        return view('livewire.tickets.show');
    }
}
