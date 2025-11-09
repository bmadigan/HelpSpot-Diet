<div class="space-y-8">
    <flux:heading level="1" size="xl" class="mb-2">Help Desk Dashboard</flux:heading>
    <flux:text>Key metrics and trends across your help desk.</flux:text>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <flux:card>
            <flux:subheading>Total Tickets</flux:subheading>
            <flux:heading size="xl">{{ number_format($this->totals['total']) }}</flux:heading>
        </flux:card>

        <flux:card>
            <flux:subheading>Open</flux:subheading>
            <flux:heading size="xl">{{ number_format($this->totals['open']) }}</flux:heading>
        </flux:card>

        <flux:card>
            <flux:subheading>Pending</flux:subheading>
            <flux:heading size="xl">{{ number_format($this->totals['pending']) }}</flux:heading>
        </flux:card>

        <flux:card>
            <flux:subheading>Closed</flux:subheading>
            <flux:heading size="xl">{{ number_format($this->totals['closed']) }}</flux:heading>
        </flux:card>

        <flux:card>
            <flux:subheading>SLA at Risk</flux:subheading>
            <flux:heading size="xl">{{ number_format($this->totals['slaAtRisk']) }}</flux:heading>
        </flux:card>

        <flux:card>
            <flux:subheading>VIP (Enterprise)</flux:subheading>
            <flux:heading size="xl">{{ number_format($this->totals['vip']) }}</flux:heading>
        </flux:card>

        <flux:card>
            <flux:subheading>No Update (48h)</flux:subheading>
            <flux:heading size="xl">{{ number_format($this->totals['noUpdate48h']) }}</flux:heading>
        </flux:card>

        <flux:card>
            <flux:subheading>New Today</flux:subheading>
            <flux:heading size="xl">{{ number_format($this->totals['today']) }}</flux:heading>
        </flux:card>

        <flux:card>
            <flux:subheading>Avg Replies / Ticket</flux:subheading>
            <flux:heading size="xl">{{ number_format($this->totals['avgReplies'], 2) }}</flux:heading>
        </flux:card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <flux:card class="lg:col-span-2">
            <flux:heading size="lg" class="mb-4">Requests Over Time (30 days)</flux:heading>

            <flux:chart :value="$this->requestsOverTime">
                <flux:chart.viewport class="h-64">
                    <flux:chart.svg>
                        <flux:chart.area field="count" class="text-blue-200/70 dark:text-blue-400/20" />
                        <flux:chart.line field="count" class="text-blue-500 dark:text-blue-400" />
                        <flux:chart.point field="count" />
                    </flux:chart.svg>

                    <flux:chart.axis axis="x" />
                </flux:chart.viewport>
            </flux:chart>
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Status Breakdown</flux:heading>
            <div class="space-y-3">
                @foreach($this->statusBreakdown as $label => $count)
                    <div class="flex items-center justify-between">
                        <flux:text inline variant="strong">{{ $label }}</flux:text>
                        <flux:badge>{{ number_format($count) }}</flux:badge>
                    </div>
                @endforeach
            </div>
        </flux:card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <flux:card>
            <flux:heading size="lg" class="mb-4">Top Customers</flux:heading>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Customer</flux:table.column>
                    <flux:table.column>Plan</flux:table.column>
                    <flux:table.column class="text-right">Tickets</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($this->topCustomers as $row)
                        <flux:table.row>
                            <flux:table.cell>{{ $row->company }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge>{{ $row->plan }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-right">{{ number_format($row->tickets) }}</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3">
                                <flux:text>No data</flux:text>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Recent Tickets</flux:heading>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Subject</flux:table.column>
                    <flux:table.column>Requester</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($this->recentTickets as $ticket)
                        <flux:table.row>
                            <flux:table.cell>
                                <flux:text inline variant="strong">{{ $ticket->subject }}</flux:text>
                            </flux:table.cell>
                            <flux:table.cell>{{ $ticket->requester_email }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge :color="$ticket->status->color()">{{ $ticket->status->label() }}</flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3">
                                <flux:text>No recent tickets</flux:text>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>
</div>

