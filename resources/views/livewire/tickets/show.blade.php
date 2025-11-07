<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-2">{{ $ticket->subject }}</h1>
            <div class="flex gap-4 text-sm text-gray-600">
                <span>From: {{ $ticket->requester_email }}</span>
                <span>Status: {{ $ticket->status->label() }}</span>
                <span>Priority: {{ $ticket->priority->label() }}</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 space-y-4">
                <div class="border-l-4 border-blue-500 pl-4">
                    <div class="text-sm text-gray-600 mb-2">
                        {{ $ticket->created_at->format('M d, Y g:i A') }}
                    </div>
                    <div class="prose">
                        {{ $ticket->description }}
                    </div>
                </div>

                @foreach($ticket->replies as $reply)
                    <div class="border-l-4 {{ $reply->is_staff ? 'border-green-500' : 'border-gray-300' }} pl-4">
                        <div class="text-sm text-gray-600 mb-2">
                            {{ $reply->author_email }} • {{ $reply->created_at->format('M d, Y g:i A') }}
                        </div>
                        <div class="prose">
                            {{ $reply->body }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t p-6">
                <form wire:submit="addReply">
                    <flux:textarea
                        wire:model="replyBody"
                        label="Add Reply"
                        rows="4"
                        placeholder="Type your response..."
                    />

                    <div class="mt-4">
                        <flux:button type="submit" variant="primary">
                            Send Reply
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <livewire:panels.client-lookup :ticket="$ticket" />

        <livewire:tools.ssl-triage />
    </div>
</div>
