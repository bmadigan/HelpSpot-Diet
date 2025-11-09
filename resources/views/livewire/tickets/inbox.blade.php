<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading level="1" size="xl">Support Tickets</flux:heading>

        <flux:input
            wire:model.live.debounce.300ms="search"
            type="search"
            placeholder="Search tickets..."
            class="w-64"
        />
    </div>

    <flux:tabs>
        @if($filter==='all')
            <flux:tab name="all" wire:click="setFilter('all')" selected>All Tickets</flux:tab>
        @else
            <flux:tab name="all" wire:click="setFilter('all')">All Tickets</flux:tab>
        @endif

        @if($filter==='sla')
            <flux:tab name="sla" wire:click="setFilter('sla')" selected>SLA at Risk</flux:tab>
        @else
            <flux:tab name="sla" wire:click="setFilter('sla')">SLA at Risk</flux:tab>
        @endif

        @if($filter==='no-update')
            <flux:tab name="no-update" wire:click="setFilter('no-update')" selected>No Update (48h)</flux:tab>
        @else
            <flux:tab name="no-update" wire:click="setFilter('no-update')">No Update (48h)</flux:tab>
        @endif

        @if($filter==='vip')
            <flux:tab name="vip" wire:click="setFilter('vip')" selected>VIP (Enterprise)</flux:tab>
        @else
            <flux:tab name="vip" wire:click="setFilter('vip')">VIP (Enterprise)</flux:tab>
        @endif
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
                        <flux:text inline variant="strong">{{ $ticket->subject }}</flux:text>
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
                            <flux:text inline variant="subtle">Unknown</flux:text>
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
                        <div class="text-center py-8">
                            <flux:text>No tickets found</flux:text>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
