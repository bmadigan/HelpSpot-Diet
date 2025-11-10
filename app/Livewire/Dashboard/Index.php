<?php

namespace App\Livewire\Dashboard;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Index extends Component
{
    #[Computed]
    public function totals(): array
    {
        $total = Ticket::query()->count();
        $open = Ticket::query()->open()->count();
        $pending = Ticket::query()->where('status', TicketStatus::PENDING)->count();
        $closed = Ticket::query()->where('status', TicketStatus::CLOSED)->count();
        $slaAtRisk = Ticket::query()->slaAtRisk()->count();
        $noUpdate48h = Ticket::query()->noUpdate48h()->count();
        $vip = Ticket::query()->vip()->count();
        $today = Ticket::query()->whereDate('created_at', now()->toDateString())->count();
        $avgReplies = $total > 0 ? round(TicketReply::query()->count() / $total, 2) : 0.0;

        return [
            'total' => $total,
            'open' => $open,
            'pending' => $pending,
            'closed' => $closed,
            'slaAtRisk' => $slaAtRisk,
            'noUpdate48h' => $noUpdate48h,
            'vip' => $vip,
            'today' => $today,
            'avgReplies' => $avgReplies,
        ];
    }

    #[Computed]
    public function requestsOverTime(): array
    {
        $from = now()->subDays(30)->startOfDay();

        $rows = Ticket::query()
            ->where('created_at', '>=', $from)
            ->selectRaw('date(created_at) as date, count(*) as count')
            ->groupBy(DB::raw('date(created_at)'))
            ->orderBy('date')
            ->get();

        // Fill missing dates with zero for smoother chart
        $data = [];
        $cursor = $from->clone();
        $byDate = $rows->keyBy('date');

        while ($cursor->lte(now())) {
            $date = $cursor->toDateString();
            $data[] = [
                'date' => $date,
                'count' => (int) ($byDate[$date]->count ?? 0),
            ];
            $cursor->addDay();
        }

        return $data;
    }

    #[Computed]
    public function requestsAndRepliesOverTime(): array
    {
        $from = now()->subDays(30)->startOfDay();

        $reqRows = Ticket::query()
            ->where('created_at', '>=', $from)
            ->selectRaw('date(created_at) as date, count(*) as requests')
            ->groupBy(DB::raw('date(created_at)'))
            ->pluck('requests', 'date');

        $repRows = TicketReply::query()
            ->where('created_at', '>=', $from)
            ->selectRaw('date(created_at) as date, count(*) as replies')
            ->groupBy(DB::raw('date(created_at)'))
            ->pluck('replies', 'date');

        $data = [];
        $cursor = $from->clone();
        while ($cursor->lte(now())) {
            $date = $cursor->toDateString();
            $data[] = [
                'date' => $date,
                'requests' => (int) ($reqRows[$date] ?? 0),
                'replies' => (int) ($repRows[$date] ?? 0),
            ];
            $cursor->addDay();
        }

        return $data;
    }

    #[Computed]
    public function sparkRequests(): array
    {
        $from = now()->subDays(14)->startOfDay();

        $rows = Ticket::query()
            ->where('created_at', '>=', $from)
            ->selectRaw('date(created_at) as date, count(*) as count')
            ->groupBy(DB::raw('date(created_at)'))
            ->pluck('count', 'date');

        $data = [];
        $cursor = $from->clone();
        while ($cursor->lte(now())) {
            $date = $cursor->toDateString();
            $data[] = [
                'date' => $date,
                'count' => (int) ($rows[$date] ?? 0),
            ];
            $cursor->addDay();
        }

        return $data;
    }

    #[Computed]
    public function sparkReplies(): array
    {
        $from = now()->subDays(14)->startOfDay();

        $rows = TicketReply::query()
            ->where('created_at', '>=', $from)
            ->selectRaw('date(created_at) as date, count(*) as count')
            ->groupBy(DB::raw('date(created_at)'))
            ->pluck('count', 'date');

        $data = [];
        $cursor = $from->clone();
        while ($cursor->lte(now())) {
            $date = $cursor->toDateString();
            $data[] = [
                'date' => $date,
                'count' => (int) ($rows[$date] ?? 0),
            ];
            $cursor->addDay();
        }

        return $data;
    }

    #[Computed]
    public function statusBreakdown(): array
    {
        return [
            'Open' => Ticket::query()->where('status', TicketStatus::OPEN)->count(),
            'Pending' => Ticket::query()->where('status', TicketStatus::PENDING)->count(),
            'Closed' => Ticket::query()->where('status', TicketStatus::CLOSED)->count(),
        ];
    }

    #[Computed]
    public function topCustomers()
    {
        return Ticket::query()
            ->join('customers', 'tickets.requester_email', '=', 'customers.email')
            ->select('customers.company_name as company', 'customers.plan as plan', DB::raw('count(*) as tickets'))
            ->groupBy('company', 'plan')
            ->orderByDesc('tickets')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function recentTickets()
    {
        return Ticket::query()->with('customer')->latest()->limit(8)->get();
    }

    #[Computed]
    public function trends(): array
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $todayNew = Ticket::query()->whereDate('created_at', $today)->count();
        $yesterdayNew = Ticket::query()->whereDate('created_at', $yesterday)->count();

        $last7Start = now()->subDays(7)->startOfDay();
        $prev7Start = now()->subDays(14)->startOfDay();
        $prev7End = now()->subDays(7)->startOfDay();

        $last7New = Ticket::query()->where('created_at', '>=', $last7Start)->count();
        $prev7New = Ticket::query()->whereBetween('created_at', [$prev7Start, $prev7End])->count();

        $last7Replies = TicketReply::query()->where('created_at', '>=', $last7Start)->count();
        $prev7Replies = TicketReply::query()->whereBetween('created_at', [$prev7Start, $prev7End])->count();

        $pct = function (int $current, int $previous): ?float {
            if ($previous === 0) {
                return $current > 0 ? 100.0 : null;
            }

            return round((($current - $previous) / $previous) * 100, 1);
        };

        return [
            'todayNew' => $todayNew,
            'yesterdayNew' => $yesterdayNew,
            'todayVsYesterday' => $pct($todayNew, $yesterdayNew),
            'last7New' => $last7New,
            'prev7New' => $prev7New,
            'last7VsPrev7' => $pct($last7New, $prev7New),
            'last7Replies' => $last7Replies,
            'prev7Replies' => $prev7Replies,
            'replies7dChange' => $pct($last7Replies, $prev7Replies),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
