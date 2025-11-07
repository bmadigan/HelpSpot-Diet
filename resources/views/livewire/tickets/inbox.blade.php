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
        <flux:table.columns>
            <flux:table.column>Subject</flux:table.column>
            <flux:table.column>Requester</flux:table.column>
            <flux:table.column>Tier</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Last Reply</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($this->tickets as $ticket)
                <flux:table.row :key="$ticket->id" wire:key="ticket-{{ $ticket->id }}">
                    <flux:table.cell>
                        <div class="font-medium">{{ $ticket->subject }}</div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div>{{ $ticket->requester_email }}</div>
                    </flux:table.cell>
                    <flux:table.cell>
                        @if($ticket->tier)
                            <flux:badge :color="$ticket->tier->color()">
                                {{ $ticket->tier->value }}
                            </flux:badge>
                        @else
                            <span class="text-gray-400">Unknown</span>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$ticket->status->color()">
                            {{ $ticket->status->label() }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $ticket->last_public_reply_at?->diffForHumans() ?? 'Never' }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button
                            href="{{ route('tickets.show', $ticket) }}"
                            size="sm"
                            variant="ghost"
                        >
                            View
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6">
                        <div class="text-center py-8 text-gray-500">
                            No tickets found
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
