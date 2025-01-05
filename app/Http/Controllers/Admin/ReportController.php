<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Play;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function profit(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());

        // Calculate total income (completed deposits)
        $totalIncome = Transaction::where('type', 'deposit')
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->sum('amount');

        // Calculate total expenses (completed withdrawals + winning payouts)
        $totalWithdrawals = Transaction::where('type', 'withdrawal')
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->sum('amount');

        // For winning payouts, multiply the bet amount by the payout multiplier based on game type
        $totalWinningPayouts = Play::where('status', 'won')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->get()
            ->sum(function ($play) {
                // Payout multipliers based on game type
                $multipliers = [
                    '2d' => 85, // 85x payout for 2D
                    '3d' => 500 // 500x payout for 3D
                ];
                return $play->amount * ($multipliers[$play->type] ?? 1);
            });

        $totalExpenses = $totalWithdrawals + $totalWinningPayouts;

        // Calculate net profit
        $totalProfit = $totalIncome - $totalExpenses;

        $totalStats = [
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'total_profit' => $totalProfit
        ];

        // Calculate daily profit statistics
        $profitStats = collect();
        $currentDate = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        while ($currentDate <= $endDateTime) {
            $dayStart = $currentDate->copy()->startOfDay();
            $dayEnd = $currentDate->copy()->endOfDay();

            // Daily deposits
            $dailyIncome = Transaction::where('type', 'deposit')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('amount');

            // Daily withdrawals
            $dailyWithdrawals = Transaction::where('type', 'withdrawal')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('amount');

            // Daily winning payouts
            $dailyWinningPayouts = Play::where('status', 'won')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->get()
                ->sum(function ($play) {
                    $multipliers = [
                        '2d' => 85,
                        '3d' => 500
                    ];
                    return $play->amount * ($multipliers[$play->type] ?? 1);
                });

            $dailyExpenses = $dailyWithdrawals + $dailyWinningPayouts;
            $dailyProfit = $dailyIncome - $dailyExpenses;

            $profitStats->push((object)[
                'date' => $currentDate->format('Y-m-d'),
                'income' => $dailyIncome,
                'expenses' => $dailyExpenses,
                'net_profit' => $dailyProfit
            ]);

            $currentDate->addDay();
        }

        return view('admin.reports.profit', compact('totalStats', 'profitStats', 'startDate', 'endDate'));
    }

    public function expense(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());

        // Get withdrawals with pagination
        $withdrawals = Transaction::with(['user'])
            ->where('type', 'withdrawal')
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->latest()
            ->paginate(15);

        // Get winning plays with pagination
        $winningPlays = Play::with(['user'])
            ->where('status', 'won')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->latest()
            ->paginate(15);

        // Calculate total expenses
        $totalWithdrawals = Transaction::where('type', 'withdrawal')
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->sum('amount');

        $totalWinningPayouts = Play::where('status', 'won')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->get()
            ->sum(function ($play) {
                $multipliers = [
                    '2d' => 85,
                    '3d' => 500
                ];
                return $play->amount * ($multipliers[$play->type] ?? 1);
            });

        $totalExpenses = $totalWithdrawals + $totalWinningPayouts;

        $expenseStats = [
            'total_withdrawals' => $totalWithdrawals,
            'total_winning_payouts' => $totalWinningPayouts,
            'total_expenses' => $totalExpenses
        ];

        return view('admin.reports.expense', compact('withdrawals', 'winningPlays', 'expenseStats', 'startDate', 'endDate'));
    }

    public function userReport(Request $request)
    {
        $query = User::where('role', 'user')
            ->withCount(['transactions', 'plays'])
            ->withSum('transactions as total_deposits', function ($query) {
                $query->where('type', 'deposit')->where('status', 'completed');
            })
            ->withSum('transactions as total_withdrawals', function ($query) {
                $query->where('type', 'withdrawal')->where('status', 'completed');
            });

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.reports.users', compact('users'));
    }

    public function agents(Request $request)
    {
        $query = User::where('role', 'agent')
            ->withCount(['transactions', 'referrals'])
            ->withSum('transactions as total_deposits', function ($query) {
                $query->where('type', 'deposit')->where('status', 'completed');
            })
            ->withSum('transactions as total_withdrawals', function ($query) {
                $query->where('type', 'withdrawal')->where('status', 'completed');
            });

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $agents = $query->latest()->paginate(15);

        return view('admin.reports.agents', compact('agents'));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'approvedBy', 'rejectedBy']);

        // Date range filter
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Approval level filter
        if ($request->filled('approval_level')) {
            $query->where('approval_level', $request->approval_level);
        }

        // User search
        if ($request->filled('user_search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_search . '%')
                  ->orWhere('phone', 'like', '%' . $request->user_search . '%');
            });
        }

        // Amount range filter
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Get transactions with pagination
        $transactions = $query->latest()->paginate(15);

        // Calculate totals
        $totals = [
            'total_count' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'by_type' => [
                'deposit' => [
                    'count' => $query->clone()->where('type', 'deposit')->count(),
                    'amount' => $query->clone()->where('type', 'deposit')->sum('amount')
                ],
                'withdrawal' => [
                    'count' => $query->clone()->where('type', 'withdrawal')->count(),
                    'amount' => $query->clone()->where('type', 'withdrawal')->sum('amount')
                ]
            ],
            'by_status' => [
                'pending' => $query->clone()->where('status', 'pending')->count(),
                'completed' => $query->clone()->where('status', 'completed')->count(),
                'rejected' => $query->clone()->where('status', 'rejected')->count()
            ]
        ];

        return view('admin.reports.transactions', compact('transactions', 'totals'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'transactions');
        $format = $request->input('format', 'csv');
        
        switch ($type) {
            case 'transactions':
                return $this->exportTransactions($format);
            case 'users':
                return $this->exportUsers($format);
            case 'agents':
                return $this->exportAgents($format);
            default:
                return back()->with('error', 'Invalid export type');
        }
    }

    private function exportTransactions($format)
    {
        $transactions = Transaction::with(['user', 'approvedBy', 'rejectedBy'])->get();
        
        $data = $transactions->map(function ($transaction) {
            return [
                'ID' => $transaction->id,
                'Type' => $transaction->type_text,
                'User' => $transaction->user->name,
                'Phone' => $transaction->user->phone,
                'Amount' => $transaction->amount,
                'Status' => $transaction->status,
                'Approval Level' => $transaction->approval_level,
                'Approved By' => optional($transaction->approvedBy)->name,
                'Approved At' => optional($transaction->approved_at)->format('Y-m-d H:i:s'),
                'Created At' => $transaction->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($data->first()));
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 'transactions.csv');
    }

    private function exportUsers($format)
    {
        $users = User::where('role', 'user')
            ->withCount(['transactions', 'plays'])
            ->withSum('transactions as total_deposits', function ($query) {
                $query->where('type', 'deposit')->where('status', 'completed');
            })
            ->withSum('transactions as total_withdrawals', function ($query) {
                $query->where('type', 'withdrawal')->where('status', 'completed');
            })
            ->get();
        
        $data = $users->map(function ($user) {
            return [
                'ID' => $user->id,
                'Name' => $user->name,
                'Phone' => $user->phone,
                'Total Transactions' => $user->transactions_count,
                'Total Plays' => $user->plays_count,
                'Total Deposits' => $user->total_deposits,
                'Total Withdrawals' => $user->total_withdrawals,
                'Balance' => $user->balance,
                'Created At' => $user->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($data->first()));
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 'users.csv');
    }

    private function exportAgents($format)
    {
        $agents = User::where('role', 'agent')
            ->withCount(['transactions', 'referrals'])
            ->withSum('transactions as total_deposits', function ($query) {
                $query->where('type', 'deposit')->where('status', 'completed');
            })
            ->withSum('transactions as total_withdrawals', function ($query) {
                $query->where('type', 'withdrawal')->where('status', 'completed');
            })
            ->get();
        
        $data = $agents->map(function ($agent) {
            return [
                'ID' => $agent->id,
                'Name' => $agent->name,
                'Phone' => $agent->phone,
                'Total Transactions' => $agent->transactions_count,
                'Total Referrals' => $agent->referrals_count,
                'Total Deposits' => $agent->total_deposits,
                'Total Withdrawals' => $agent->total_withdrawals,
                'Balance' => $agent->balance,
                'Created At' => $agent->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($data->first()));
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 'agents.csv');
    }
}
