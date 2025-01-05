<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Play;
use App\Models\Result;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $charts = [
            'revenue' => $this->getRevenueData(),
            'lottery' => $this->getLotteryData(),
            'users' => $this->getUserData(),
            'plays' => $this->getPlaysData(),
        ];

        $lastMonth = Carbon::now()->subMonth();
        $today = Carbon::today();
        
        // Get current stats
        $stats = [
            'total_users' => User::count(),
            'total_plays' => Play::count(),
            'total_revenue' => Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_results' => Result::count(),
            'total_deposits' => Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawals' => Transaction::where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            
            // Today's activity
            'today' => [
                'new_users' => User::whereDate('created_at', $today)->count(),
                'total_plays' => Play::whereDate('created_at', $today)->count(),
                'total_deposits' => Transaction::where('type', 'deposit')
                    ->where('status', 'completed')
                    ->whereDate('created_at', $today)
                    ->sum('amount'),
                'total_withdrawals' => Transaction::where('type', 'withdrawal')
                    ->where('status', 'completed')
                    ->whereDate('created_at', $today)
                    ->sum('amount')
            ],

            // Pending actions
            'pending' => [
                'deposits' => Transaction::where('type', 'deposit')
                    ->where('status', 'pending')
                    ->count(),
                'withdrawals' => Transaction::where('type', 'withdrawal')
                    ->where('status', 'pending')
                    ->count()
            ],

            // Recent activities
            'recent_users' => User::latest()->take(5)->get(),
            'recent_plays' => Play::with('user')->latest()->take(5)->get(),
            'recent_transactions' => Transaction::with('user')->latest()->take(5)->get(),
            
            'growth' => [
                'users' => [
                    'percentage' => $this->calculateGrowth(
                        User::where('created_at', '>=', $lastMonth)->count(),
                        User::count()
                    )
                ],
                'plays' => [
                    'percentage' => $this->calculateGrowth(
                        Play::where('created_at', '>=', $lastMonth)->count(),
                        Play::count()
                    )
                ],
                'revenue' => [
                    'percentage' => $this->calculateGrowth(
                        Transaction::where('type', 'deposit')
                            ->where('status', 'completed')
                            ->where('created_at', '>=', $lastMonth)
                            ->sum('amount'),
                        Transaction::where('type', 'deposit')
                            ->where('status', 'completed')
                            ->sum('amount')
                    )
                ],
                'deposits' => [
                    'percentage' => $this->calculateGrowth(
                        Transaction::where('type', 'deposit')
                            ->where('status', 'completed')
                            ->where('created_at', '>=', $lastMonth)
                            ->sum('amount'),
                        Transaction::where('type', 'deposit')
                            ->where('status', 'completed')
                            ->sum('amount')
                    )
                ],
                'withdrawals' => [
                    'percentage' => $this->calculateGrowth(
                        Transaction::where('type', 'withdrawal')
                            ->where('status', 'completed')
                            ->where('created_at', '>=', $lastMonth)
                            ->sum('amount'),
                        Transaction::where('type', 'withdrawal')
                            ->where('status', 'completed')
                            ->sum('amount')
                    )
                ]
            ]
        ];

        return view('admin.dashboard', compact('charts', 'stats'));
    }

    private function calculateGrowth($current, $total) 
    {
        if ($total == 0) return 0;
        $previous = $total - $current;
        if ($previous == 0) return 100;
        return round((($current - $previous) / $previous) * 100, 1);
    }

    public function reports()
    {
        $charts = [
            'revenue' => $this->getRevenueData(),
            'lottery' => $this->getLotteryData(),
            'users' => $this->getUserData(),
            'plays' => $this->getPlaysData(),
        ];

        return view('admin.reports.index', compact('charts'));
    }

    public function revenue(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());

        $stats = [
            'total_revenue' => Transaction::whereBetween('created_at', [$startDate, $endDate])
                ->where('type', 'deposit')
                ->sum('amount'),
            'total_bets' => Play::whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount'),
            'total_payouts' => Play::whereBetween('created_at', [$startDate, $endDate])
                ->where('win', true)
                ->sum('payout_amount'),
        ];

        $stats['net_profit'] = $stats['total_revenue'] - $stats['total_payouts'];

        $daily_stats = Transaction::selectRaw('
            DATE(created_at) as date,
            SUM(CASE WHEN type = "deposit" THEN amount ELSE 0 END) as revenue,
            SUM(CASE WHEN type = "withdrawal" THEN amount ELSE 0 END) as expenses,
            COUNT(DISTINCT CASE WHEN type = "play" THEN user_id END) as total_plays,
            COUNT(DISTINCT CASE WHEN type = "win" THEN user_id END) as total_wins
        ')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->paginate(15);

        foreach ($daily_stats as $stat) {
            $stat->profit = $stat->revenue - $stat->expenses;
        }

        $chart = [
            'labels' => $daily_stats->pluck('date')->toArray(),
            'revenue' => $daily_stats->pluck('revenue')->toArray(),
            'profit' => $daily_stats->pluck('profit')->toArray(),
        ];

        return view('admin.reports.revenue', compact('stats', 'daily_stats', 'chart'));
    }

    private function getRevenueData()
    {
        $days = 7;
        $data = Transaction::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('type', 'deposit')
            ->where('created_at', '>=', Carbon::now()->subDays($days)->toDateTimeString())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ];
    }

    private function getLotteryData()
    {
        $data = Result::selectRaw('DATE(drawn_at) as date, COUNT(*) as total')
            ->where('drawn_at', '>=', Carbon::now()->subDays(7)->toDateTimeString())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ];
    }

    private function getUserData()
    {
        $data = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7)->toDateTimeString())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ];
    }

    private function getPlaysData()
    {
        $data = Play::selectRaw("
            CASE 
                WHEN amount < 1000 THEN '1-999'
                WHEN amount < 5000 THEN '1000-4999'
                WHEN amount < 10000 THEN '5000-9999'
                ELSE '10000+'
            END as amount_range,
            COUNT(*) as total
        ")
        ->where('created_at', '>=', Carbon::now()->subDays(7)->toDateTimeString())
        ->groupBy('amount_range')
        ->orderBy(DB::raw('MIN(amount)'))
        ->get();

        return [
            'labels' => $data->pluck('amount_range')->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ];
    }
}
