<flux:card size="sm">
    <flux:heading level="3">Client Context Lookup</flux:heading>

    <form wire:submit="lookup" class="space-y-4">
        <flux:input
            wire:model="emailOrDomain"
            label="Email or Domain"
            placeholder="customer@example.com"
        />

        <flux:button type="submit" variant="primary" class="w-full">
            Look Up Customer
        </flux:button>
    </form>

    @if($foundCustomer)
        <flux:separator class="my-6" />

        <div class="space-y-3">
            <div>
                <flux:text variant="subtle">Company</flux:text>
                <flux:text variant="strong">{{ $foundCustomer->company_name }}</flux:text>
            </div>

            <div>
                <flux:text variant="subtle">Plan Tier</flux:text>
                <div>
                    <flux:badge :color="$foundCustomer->plan->color()">
                        {{ $foundCustomer->plan->value }}
                    </flux:badge>
                </div>
            </div>

            <div>
                <flux:text variant="subtle">Status</flux:text>
                <div>
                    <flux:badge :color="$foundCustomer->status->color()">
                        {{ $foundCustomer->status->label() }}
                    </flux:badge>
                </div>
            </div>

            <div>
                <flux:text variant="subtle">Last Invoice</flux:text>
                <flux:text>{{ $foundCustomer->last_invoice_date?->format('M d, Y') ?? 'N/A' }}</flux:text>
            </div>
        </div>
    @endif
</flux:card>
