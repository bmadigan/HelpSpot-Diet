<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Support Tickets</h1>

        <flux:input
            wire:model.live.debounce.300ms="search"
            type="search"
            placeholder="Search tickets..."
            class="w-64"
        />
    </div>

    <flux:tabs wire:model="filter">
        <flux:tab name="all">All Tickets</flux:tab>
        <flux:tab name="sla">SLA at Risk</flux:tab>
        <flux:tab name="no-update">No Update (48h)</flux:tab>
        <flux:tab name="vip">VIP (Enterprise)</flux:tab>
    </flux:tabs>

    <flux:table>
        <flux:columns>
            <flux:column>Subject</flux:column>
            <flux:column>Requester</flux:column>
            <flux:column>Tier</flux:column>
            <flux:column>Status</flux:column>
            <flux:column>Last Reply</flux:column>
            <flux:column>Actions</flux:column>
        </flux:columns>

        <flux:rows>
            @forelse($this->tickets as $ticket)
                <flux:row :key="$ticket->id" wire:key="ticket-{{ $ticket->id }}">
                    <flux:cell>
                        <div class="font-medium">{{ $ticket->subject }}</div>
                    </flux:cell>
                    <flux:cell>
                        <div>{{ $ticket->requester_email }}</div>
                    </flux:cell>
                    <flux:cell>
                        @if($ticket->tier)
                            <flux:badge :color="$ticket->tier->color()">
                                {{ $ticket->tier->value }}
                            </flux:badge>
                        @else
                            <span class="text-gray-400">Unknown</span>
                        @endif
                    </flux:cell>
                    <flux:cell>
                        <flux:badge :color="$ticket->status->color()">
                            {{ $ticket->status->label() }}
                        </flux:badge>
                    </flux:cell>
                    <flux:cell>
                        {{ $ticket->last_public_reply_at?->diffForHumans() ?? 'Never' }}
                    </flux:cell>
                    <flux:cell>
                        <flux:button
                            href="{{ route('tickets.show', $ticket) }}"
                            size="sm"
                            variant="ghost"
                        >
                            View
                        </flux:button>
                    </flux:cell>
                </flux:row>
            @empty
                <flux:row>
                    <flux:cell colspan="6">
                        <div class="text-center py-8 text-gray-500">
                            No tickets found
                        </div>
                    </flux:cell>
                </flux:row>
            @endforelse
        </flux:rows>
    </flux:table>
</div>
