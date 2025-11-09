<flux:card size="sm">
    <flux:heading level="3">SSL Triage Tool</flux:heading>

    <form wire:submit="analyze" class="space-y-4">
        <flux:textarea
            wire:model="opensslOutput"
            label="OpenSSL Output"
            placeholder="Paste: openssl s_client -connect host:443 -servername host -showcerts"
            rows="8"
        />

        <div class="flex gap-2">
            <flux:button type="submit" variant="primary">
                Analyze
            </flux:button>

            @if($analysis)
                <flux:button type="button" wire:click="resetTool" variant="ghost">
                    Reset
                </flux:button>
            @endif
        </div>
    </form>

    @if($analysis)
        <flux:separator class="my-6" />

        <div class="space-y-4">
            <div>
                <flux:text variant="subtle" class="mb-2">Diagnosis</flux:text>
                <flux:badge color="blue" size="lg">
                    {{ $analysis['diagnosis'] }}
                </flux:badge>
            </div>

            <div>
                <flux:text variant="subtle" class="mb-2">Next Steps</flux:text>
                <div class="space-y-2">
                    @foreach($analysis['next_steps'] as $index => $step)
                        <div class="flex gap-2">
                            <flux:text inline variant="subtle">{{ $index + 1 }}.</flux:text>
                            <flux:text inline>{{ $step }}</flux:text>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</flux:card>
