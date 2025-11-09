<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <flux:card>
            <flux:heading level="1" size="xl" class="mb-2">{{ $ticket->subject }}</flux:heading>
            <div class="flex gap-4">
                <flux:text inline variant="subtle">From: {{ $ticket->requester_email }}</flux:text>
                <flux:text inline variant="subtle">Status: {{ $ticket->status->label() }}</flux:text>
                <flux:text inline variant="subtle">Priority: {{ $ticket->priority->label() }}</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-4">
                <flux:callout color="blue">
                    <flux:text variant="subtle" class="mb-2">{{ $ticket->created_at->format('M d, Y g:i A') }}</flux:text>
                    <flux:text>{{ $ticket->description }}</flux:text>
                </flux:callout>

                @foreach($ticket->replies as $reply)
                    <flux:callout color="{{ $reply->is_staff ? 'green' : 'zinc' }}">
                        <flux:text variant="subtle" class="mb-2">{{ $reply->author_email }} • {{ $reply->created_at->format('M d, Y g:i A') }}</flux:text>
                        <flux:text>{{ $reply->body }}</flux:text>
                    </flux:callout>
                @endforeach
            </div>

            <flux:separator class="my-6" />

            <form wire:submit="addReply" class="space-y-4">
                <flux:textarea
                    wire:model="replyBody"
                    label="Add Reply"
                    rows="4"
                    placeholder="Type your response..."
                />

                <flux:button type="submit" variant="primary">
                    Send Reply
                </flux:button>
            </form>
        </flux:card>
    </div>

    <div class="space-y-6">
        <livewire:panels.client-lookup :ticket="$ticket" />

        <livewire:tools.ssl-triage />
    </div>
</div>
