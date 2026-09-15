<?php

namespace App\Livewire\Admin;

use App\Models\SupportTicket;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Support\DailySeries;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin Dashboard')]
#[Layout('layouts.admin')]
class Dashboard extends Component
{
    private const WINDOW_DAYS = 30;

    /**
     * A row only represents settled money once it reaches one of these — deposits in this
     * app only ever land on 'completed', withdrawals split across both 'approved' and
     * 'completed', so filtering on either status alone silently drops real settled money.
     */
    private const SETTLED_STATUSES = ['approved', 'completed'];

    public function render(): View
    {
        $start = now()->startOfDay()->toImmutable()->subDays(self::WINDOW_DAYS - 1);
        $end = now()->endOfDay()->toImmutable();
        $prevStart = $start->subDays(self::WINDOW_DAYS);
        $prevEnd = $start->subSecond();

        $spine = DailySeries::spine($start, self::WINDOW_DAYS);

        [$registrations, $chartLabels, $chartShortLabels] = $this->registrationSeries($spine, $start, $end);
        $registrationsAvg7 = DailySeries::trailingAverage($registrations, 7);
        $signupsCurrentTotal = array_sum($registrations);
        $signupsPreviousTotal = User::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $signupsChangePercent = $signupsPreviousTotal > 0
            ? round((($signupsCurrentTotal - $signupsPreviousTotal) / $signupsPreviousTotal) * 100, 1)
            : null;

        $peakCount = $registrations ? max($registrations) : 0;
        $peakIndex = $peakCount > 0 ? array_search($peakCount, $registrations, true) : false;
        $peakLabel = $peakIndex !== false ? $chartLabels[$peakIndex] : null;

        [$depositsIn, $withdrawalsOut] = $this->transactionSeries($spine, $start, $end);
        $netFlowSeries = array_map(fn ($in, $out) => $in - $out, $depositsIn, $withdrawalsOut);
        $netFlowAvg7 = DailySeries::trailingAverage($netFlowSeries, 7);
        $netFlow = array_sum($netFlowSeries);
        $prevNetFlow = $this->netFlowTotal($prevStart, $prevEnd);
        $netFlowChangePercent = $prevNetFlow != 0.0
            ? round((($netFlow - $prevNetFlow) / abs($prevNetFlow)) * 100, 1)
            : null;

        $volume24h = $this->settledVolume(now()->subDay(), now());
        $volumePrevious24h = $this->settledVolume(now()->subDays(2), now()->subDay());
        $volumeChangePercent = $volumePrevious24h != 0.0
            ? round((($volume24h - $volumePrevious24h) / abs($volumePrevious24h)) * 100, 1)
            : null;

        return view('livewire.admin.dashboard', [
            'pendingDeposits' => WalletTransaction::where('type', 'deposit')->where('status', 'pending')->count(),
            'pendingWithdrawals' => WalletTransaction::where('type', 'withdrawal')->where('status', 'pending')->count(),
            'openTickets' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
            'totalUsers' => User::count(),
            'newUsersThisWeek' => User::where('created_at', '>=', now()->subWeek())->count(),
            'volume24h' => $volume24h,
            'volumeChangePercent' => $volumeChangePercent,
            'windowDays' => self::WINDOW_DAYS,
            'chartLabels' => $chartLabels,
            'chartShortLabels' => $chartShortLabels,
            'registrations' => $registrations,
            'registrationsAvg7' => $registrationsAvg7,
            'signupsCurrentTotal' => $signupsCurrentTotal,
            'signupsChangePercent' => $signupsChangePercent,
            'peakCount' => $peakCount,
            'peakLabel' => $peakLabel,
            'depositsIn' => $depositsIn,
            'withdrawalsOut' => $withdrawalsOut,
            'netFlowAvg7' => $netFlowAvg7,
            'netFlow' => $netFlow,
            'netFlowChangePercent' => $netFlowChangePercent,
        ]);
    }

    /**
     * @param  Collection<int, string>  $spine
     * @return array{0: array<int, int>, 1: array<int, string>, 2: array<int, string>}
     */
    private function registrationSeries(Collection $spine, CarbonImmutable $start, CarbonImmutable $end): array
    {
        $counts = User::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');

        $registrations = [];
        $labels = [];
        $shortLabels = [];

        foreach ($spine as $date) {
            $day = CarbonImmutable::parse($date);
            $registrations[] = (int) ($counts[$date] ?? 0);
            $labels[] = $day->format('M j');
            $shortLabels[] = $day->format('n/j');
        }

        return [$registrations, $labels, $shortLabels];
    }

    /**
     * @param  Collection<int, string>  $spine
     * @return array{0: array<int, float>, 1: array<int, float>}
     */
    private function transactionSeries(Collection $spine, CarbonImmutable $start, CarbonImmutable $end): array
    {
        $rows = DB::table('wallet_transactions')
            ->whereIn('type', ['deposit', 'withdrawal'])
            ->whereIn('status', self::SETTLED_STATUSES)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, type, SUM(ABS(amount)) as total')
            ->groupBy('d', 'type')
            ->get();

        $byDate = [];

        foreach ($rows as $row) {
            $byDate[$row->d][$row->type] = (float) $row->total;
        }

        $depositsIn = [];
        $withdrawalsOut = [];

        foreach ($spine as $date) {
            $depositsIn[] = $byDate[$date]['deposit'] ?? 0.0;
            $withdrawalsOut[] = $byDate[$date]['withdrawal'] ?? 0.0;
        }

        return [$depositsIn, $withdrawalsOut];
    }

    private function netFlowTotal(CarbonImmutable $start, CarbonImmutable $end): float
    {
        $totals = WalletTransaction::whereIn('type', ['deposit', 'withdrawal'])
            ->whereIn('status', self::SETTLED_STATUSES)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('type, SUM(ABS(amount)) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        return (float) ($totals['deposit'] ?? 0) - (float) ($totals['withdrawal'] ?? 0);
    }

    private function settledVolume(CarbonInterface $from, CarbonInterface $to): float
    {
        return (float) (WalletTransaction::whereIn('status', self::SETTLED_STATUSES)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('SUM(ABS(amount)) as total')
            ->value('total') ?? 0.0);
    }
}
