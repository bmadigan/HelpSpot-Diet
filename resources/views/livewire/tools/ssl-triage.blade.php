<div class="bg-white rounded-lg shadow p-6">
    <h3 class="font-semibold mb-4">SSL Triage Tool</h3>

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
                <flux:button type="button" wire:click="reset" variant="ghost">
                    Reset
                </flux:button>
            @endif
        </div>
    </form>

    @if($analysis)
        <div class="mt-6 pt-6 border-t space-y-4">
            <div>
                <div class="text-sm font-semibold text-gray-600 mb-2">Diagnosis</div>
                <flux:badge color="blue" size="lg">
                    {{ $analysis['diagnosis'] }}
                </flux:badge>
            </div>

            <div>
                <div class="text-sm font-semibold text-gray-600 mb-2">Next Steps</div>
                <div class="space-y-2">
                    @foreach($analysis['next_steps'] as $index => $step)
                        <div class="flex gap-2 text-sm">
                            <span class="font-medium text-gray-400">{{ $index + 1 }}.</span>
                            <span>{{ $step }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
