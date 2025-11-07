<div class="bg-white rounded-lg shadow p-6">
    <h3 class="font-semibold mb-4">Client Context Lookup</h3>

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
        <div class="mt-6 pt-6 border-t space-y-3">
            <div>
                <div class="text-sm text-gray-600">Company</div>
                <div class="font-medium">{{ $foundCustomer->company_name }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-600">Plan Tier</div>
                <flux:badge :color="$foundCustomer->plan->color()">
                    {{ $foundCustomer->plan->value }}
                </flux:badge>
            </div>

            <div>
                <div class="text-sm text-gray-600">Status</div>
                <flux:badge :color="$foundCustomer->status->color()">
                    {{ $foundCustomer->status->label() }}
                </flux:badge>
            </div>

            <div>
                <div class="text-sm text-gray-600">Last Invoice</div>
                <div>{{ $foundCustomer->last_invoice_date?->format('M d, Y') ?? 'N/A' }}</div>
            </div>
        </div>
    @endif
</div>
