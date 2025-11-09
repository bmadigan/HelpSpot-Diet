<div class="space-y-8">
    <flux:heading level="1" size="xl" class="mb-2">Help Desk Dashboard</flux:heading>
    <flux:text>Key metrics and trends across your help desk.</flux:text>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <flux:card class="relative overflow-hidden bg-gradient-to-br from-indigo-500/5 via-sky-500/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-indigo-500/10 blur-2xl"></div>
            <div class="flex items-start justify-between">
                <div>
                    <flux:subheading>New (7d)</flux:subheading>
                    <flux:heading size="xl">{{ number_format($this->trends['last7New']) }}</flux:heading>
                    <div class="mt-1 flex items-center gap-2">
                        @php $c = $this->trends['last7VsPrev7']; @endphp
                        <flux:badge :color="$c === null ? 'zinc' : ($c >= 0 ? 'green' : 'red')">
                            <flux:icon icon="arrow-trending-{{ ($c ?? 0) >= 0 ? 'up' : 'down' }}" variant="mini" />
                            {{ $c === null ? 'N/A' : ($c > 0 ? '+' : '') . $c . '%' }} vs prev 7d
                        </flux:badge>
                    </div>
                </div>
                <flux:icon icon="arrow-trending-up" variant="outline" class="size-10 text-indigo-500/80 dark:text-indigo-400/80" />
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden bg-gradient-to-br from-emerald-500/5 via-teal-500/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-emerald-500/10 blur-2xl"></div>
            <div class="flex items-start justify-between">
                <div>
                    <flux:subheading>Open</flux:subheading>
                    <flux:heading size="xl">{{ number_format($this->totals['open']) }}</flux:heading>
                    <flux:text variant="subtle">Total tickets currently open</flux:text>
                </div>
                <flux:icon icon="inbox" variant="outline" class="size-10 text-emerald-500/80 dark:text-emerald-400/80" />
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden bg-gradient-to-br from-amber-500/5 via-orange-500/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-amber-500/10 blur-2xl"></div>
            <div class="flex items-start justify-between">
                <div>
                    <flux:subheading>SLA at Risk</flux:subheading>
                    <flux:heading size="xl">{{ number_format($this->totals['slaAtRisk']) }}</flux:heading>
                    <flux:text variant="subtle">No reply in 24h</flux:text>
                </div>
                <flux:icon icon="exclamation-triangle" variant="outline" class="size-10 text-amber-500/80 dark:text-amber-400/80" />
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden bg-gradient-to-br from-fuchsia-500/5 via-purple-500/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-fuchsia-500/10 blur-2xl"></div>
            <div class="flex items-start justify-between">
                <div>
                    <flux:subheading>VIP (Enterprise)</flux:subheading>
                    <flux:heading size="xl">{{ number_format($this->totals['vip']) }}</flux:heading>
                    <flux:text variant="subtle">Enterprise tier customers</flux:text>
                </div>
                <flux:icon icon="star" variant="outline" class="size-10 text-fuchsia-500/80 dark:text-fuchsia-400/80" />
            </div>
        </flux:card>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <flux:card class="relative overflow-hidden bg-gradient-to-br from-sky-500/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-sky-500/10 blur-2xl"></div>
            <div class="flex items-start justify-between">
                <div>
                    <flux:subheading>Today</flux:subheading>
                    <flux:heading size="xl">{{ number_format($this->trends['todayNew']) }}</flux:heading>
                    <div class="mt-1">
                        @php $d = $this->trends['todayVsYesterday']; @endphp
                        <flux:text variant="subtle">
                            {{ $d === null ? 'No change data' : (($d > 0 ? '+' : '') . $d . '% vs yesterday') }}
                        </flux:text>
                    </div>
                </div>
                <flux:icon icon="calendar" variant="outline" class="size-10 text-sky-500/80 dark:text-sky-400/80" />
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden bg-gradient-to-br from-rose-500/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-rose-500/10 blur-2xl"></div>
            <div class="flex items-start justify-between">
                <div>
                    <flux:subheading>Pending</flux:subheading>
                    <flux:heading size="xl">{{ number_format($this->totals['pending']) }}</flux:heading>
                    <flux:text variant="subtle">Awaiting agent/customer action</flux:text>
                </div>
                <flux:icon icon="pause" variant="outline" class="size-10 text-rose-500/80 dark:text-rose-400/80" />
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden bg-gradient-to-br from-emerald-500/5 to-transparent">
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-emerald-500/10 blur-2xl"></div>
            <div class="flex items-start justify-between">
                <div>
                    <flux:subheading>Avg Replies / Ticket</flux:subheading>
                    <flux:heading size="xl">{{ number_format($this->totals['avgReplies'], 2) }}</flux:heading>
                    <flux:text variant="subtle">{{ number_format($this->trends['last7Replies']) }} replies in last 7d</flux:text>
                </div>
                <flux:icon icon="chat-bubble-left-right" variant="outline" class="size-10 text-emerald-500/80 dark:text-emerald-400/80" />
            </div>
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
                        <flux:badge :color="match($label){'Open'=>'blue','Pending'=>'amber','Closed'=>'zinc',default=>'zinc'}">{{ number_format($count) }}</flux:badge>
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
