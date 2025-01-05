<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Play;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $today = Carbon::today();
        $lastMonth = Carbon::now()->subMonth();

        $stats = [
            // Overall Stats
            'total_users' => User::count(),
            'total_plays' => Play::count(),
            'total_deposits' => Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawals' => Transaction::where('type', 'withdraw')->where('status', 'completed')->sum('amount'),
            
            // Growth Stats (compared to last month)
            'growth' => [
                'users' => [
                    'current' => User::where('created_at', '>=', $lastMonth)->count(),
                    'previous' => User::where('created_at', '<', $lastMonth)
                                    ->where('created_at', '>=', $lastMonth->copy()->subMonth())
                                    ->count(),
                ],
                'plays' => [
                    'current' => Play::where('created_at', '>=', $lastMonth)->count(),
                    'previous' => Play::where('created_at', '<', $lastMonth)
                                    ->where('created_at', '>=', $lastMonth->copy()->subMonth())
                                    ->count(),
                ],
                'deposits' => [
                    'current' => Transaction::where('type', 'deposit')
                                        ->where('status', 'completed')
                                        ->where('created_at', '>=', $lastMonth)
                                        ->sum('amount'),
                    'previous' => Transaction::where('type', 'deposit')
                                        ->where('status', 'completed')
                                        ->where('created_at', '<', $lastMonth)
                                        ->where('created_at', '>=', $lastMonth->copy()->subMonth())
                                        ->sum('amount'),
                ],
                'withdrawals' => [
                    'current' => Transaction::where('type', 'withdraw')
                                        ->where('status', 'completed')
                                        ->where('created_at', '>=', $lastMonth)
                                        ->sum('amount'),
                    'previous' => Transaction::where('type', 'withdraw')
                                        ->where('status', 'completed')
                                        ->where('created_at', '<', $lastMonth)
                                        ->where('created_at', '>=', $lastMonth->copy()->subMonth())
                                        ->sum('amount'),
                ],
            ],
            
            // Recent Activity
            'recent_users' => User::latest()->take(5)->get(),
            'recent_plays' => Play::with('user')->latest()->take(5)->get(),
            'recent_transactions' => Transaction::with('user')->latest()->take(5)->get(),
            
            // User Activity Stats
            'user_activity' => [
                'online' => User::where('last_activity_at', '>=', now()->subMinutes(5))->count(),
                'today' => User::where('last_activity_at', '>=', today())->count(),
                'this_week' => User::where('last_activity_at', '>=', now()->subDays(7))->count(),
                'this_month' => User::where('last_activity_at', '>=', now()->subDays(30))->count(),
                'banned' => User::where('status', 'banned')->count(),
            ],
            
            // Today's Stats
            'today' => [
                'new_users' => User::whereDate('created_at', $today)->count(),
                'total_plays' => Play::whereDate('created_at', $today)->count(),
                'total_deposits' => Transaction::whereDate('created_at', $today)
                                             ->where('type', 'deposit')
                                             ->where('status', 'completed')
                                             ->sum('amount'),
                'total_withdrawals' => Transaction::whereDate('created_at', $today)
                                                ->where('type', 'withdraw')
                                                ->where('status', 'completed')
                                                ->sum('amount'),
            ],
            
            // Pending Actions
            'pending' => [
                'deposits' => Transaction::where('type', 'deposit')->where('status', 'pending')->count(),
                'withdrawals' => Transaction::where('type', 'withdraw')->where('status', 'pending')->count(),
            ],
        ];

        // Calculate growth percentages
        foreach (['users', 'plays', 'deposits', 'withdrawals'] as $metric) {
            $current = is_numeric($stats['growth'][$metric]['current']) ? $stats['growth'][$metric]['current'] : 0;
            $previous = is_numeric($stats['growth'][$metric]['previous']) ? $stats['growth'][$metric]['previous'] : 0;
            
            if ($previous > 0) {
                $stats['growth'][$metric]['percentage'] = round((($current - $previous) / $previous) * 100, 1);
            } else {
                $stats['growth'][$metric]['percentage'] = $current > 0 ? 100 : 0;
            }
        }

        return view('admin.dashboard', compact('stats'));
    }

    // Users Management
    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function showUser(User $user)
    {
        $user->load(['plays', 'transactions']);
        return view('admin.users.show', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);
        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function banUser(User $user)
    {
        $user->update([
            'status' => 'banned',
            'banned_at' => now(),
        ]);
        return back()->with('success', 'User banned successfully.');
    }

    public function unbanUser(User $user)
    {
        $user->update([
            'status' => 'active',
            'banned_at' => null,
        ]);
        return back()->with('success', 'User unbanned successfully.');
    }

    // Plays Management
    public function plays()
    {
        $plays = Play::with('user')->latest()->paginate(15);
        return view('admin.plays.index', compact('plays'));
    }

    public function showPlay(Play $play)
    {
        $play->load('user');
        return view('admin.plays.show', compact('play'));
    }

    public function updatePlay(Request $request, Play $play)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
            'result' => 'nullable|string',
        ]);

        $play->update($validated);
        return redirect()->route('admin.plays.show', $play)->with('success', 'Play updated successfully.');
    }

    public function deletePlay(Play $play)
    {
        $play->delete();
        return redirect()->route('admin.plays.index')->with('success', 'Play deleted successfully.');
    }

    // Transactions Management
    public function transactions()
    {
        $transactions = Transaction::with('user')
            ->when(request('type'), function($query) {
                return $query->where('type', request('type'));
            })
            ->when(request('status'), function($query) {
                return $query->where('status', request('status'));
            })
            ->when(request('search'), function($query) {
                return $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                      ->orWhere('phone', 'like', '%' . request('search') . '%');
                });
            })
            ->latest()
            ->paginate(10);

        $stats = [
            'total_transactions' => Transaction::count(),
            'total_deposits' => Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawals' => Transaction::where('type', 'withdraw')->where('status', 'completed')->sum('amount'),
            'pending_transactions' => Transaction::where('status', 'pending')->count()
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    public function showTransaction(Transaction $transaction)
    {
        $transaction->load('user');
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateTransaction(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,rejected',
            'notes' => 'nullable|string',
        ]);

        $transaction->update($validated);
        return redirect()->route('admin.transactions.show', $transaction)->with('success', 'Transaction updated successfully.');
    }

    public function approveTransaction(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'completed',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_note' => request('note')
        ]);

        // Update user balance for deposits and withdrawals
        if ($transaction->type === 'deposit') {
            $transaction->user->increment('balance', $transaction->amount);
        } elseif ($transaction->type === 'withdrawal') {
            $transaction->user->decrement('balance', $transaction->amount);
        }

        return back()->with('success', 'ငွေလွှဲမှုကို အတည်ပြုပြီးပါပြီ');
    }

    public function rejectTransaction(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'admin_note' => request('note')
        ]);

        return back()->with('success', 'ငွေလွှဲမှုကို ငြင်းပယ်လိုက်ပါပြီ');
    }

    public function userPlays(Request $request, User $user)
    {
        $query = Play::where('user_id', $user->id);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('created_at', $date);
        }

        $plays = $query->latest()->paginate(15);

        $stats = [
            'total_plays' => Play::where('user_id', $user->id)->count(),
            'total_wins' => Play::where('user_id', $user->id)->where('status', 'won')->count(),
            'total_losses' => Play::where('user_id', $user->id)->where('status', 'lost')->count(),
            'total_pending' => Play::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];

        return view('admin.users.plays', compact('user', 'plays', 'stats'));
    }

    public function userTransactions(Request $request, User $user)
    {
        $query = Transaction::where('user_id', $user->id);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('created_at', $date);
        }

        $transactions = $query->latest()->paginate(15);

        $stats = [
            'total_deposits' => Transaction::where('user_id', $user->id)
                                         ->where('type', 'deposit')
                                         ->sum('amount'),
            'total_withdrawals' => Transaction::where('user_id', $user->id)
                                            ->where('type', 'withdraw')
                                            ->sum('amount'),
        ];

        return view('admin.users.transactions', compact('user', 'transactions', 'stats'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_admin' => 'required|boolean'
        ]);

        $user->update($validated);

        return back()->with('success', 'အသုံးပြုသူ၏ အခွင့်အရေးကို ပြောင်းလဲပြီးပါပြီ။');
    }

    protected function getDailyStats()
    {
        $today = Carbon::today();
        
        return [
            'new_users' => User::whereDate('created_at', $today)->count(),
            'total_plays' => Play::whereDate('created_at', $today)->count(),
            'total_deposits' => Transaction::whereDate('created_at', $today)
                                         ->where('type', 'deposit')
                                         ->sum('amount'),
            'total_withdrawals' => Transaction::whereDate('created_at', $today)
                                            ->where('type', 'withdraw')
                                            ->sum('amount'),
            'total_wins' => Play::whereDate('created_at', $today)
                               ->where('status', 'won')
                               ->count(),
            'total_pending' => Play::whereDate('created_at', $today)
                                  ->where('status', 'pending')
                                  ->count(),
        ];
    }

    // Agent Management Methods
    public function agents()
    {
        $agents = User::where('role', 'agent')
            ->withCount('referrals')
            ->withSum('transactions', 'amount')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_agents' => User::where('role', 'agent')->count(),
            'active_agents' => User::where('role', 'agent')->where('status', 'active')->count(),
            'total_referrals' => User::where('role', 'agent')->withCount('referrals')->get()->sum('referrals_count'),
            'total_commission' => User::where('role', 'agent')->sum('commission_balance'),
        ];

        return view('admin.agents.index', compact('agents', 'stats'));
    }

    public function createAgent()
    {
        return view('admin.agents.create');
    }

    public function storeAgent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'points' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'agent',
                'status' => 'active',
                'commission_rate' => $validated['commission_rate'],
                'commission_balance' => 0,
                'points' => $validated['points'],
                'referral_code' => strtoupper(Str::random(8)),
            ]);

            // Record the initial points transaction if points > 0
            if ($validated['points'] > 0) {
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'point_credit',
                    'amount' => $validated['points'],
                    'status' => 'completed',
                    'note' => 'Initial points allocation',
                ]);
            }

            return $user;
        });

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent created successfully with initial points.');
    }

    public function showAgent(User $user)
    {
        abort_if(!$user->role === 'agent', 404);

        $user->load(['referrals', 'transactions']);

        $stats = [
            'total_referrals' => $user->referrals()->count(),
            'active_referrals' => $user->referrals()->where('is_banned', false)->count(),
            'total_commission' => $user->commission_balance,
            'recent_transactions' => $user->transactions()->latest()->take(5)->get(),
            'recent_referrals' => $user->referrals()->latest()->take(5)->get(),
        ];

        return view('admin.agents.show', compact('user', 'stats'));
    }

    public function editAgent(User $user)
    {
        abort_if(!$user->role === 'agent', 404);
        return view('admin.agents.edit', compact('user'));
    }

    public function updateAgent(Request $request, User $user)
    {
        abort_if(!$user->role === 'agent', 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'commission_rate' => 'required|numeric|min:0|max:100',
            'password' => 'nullable|string|min:6',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'commission_rate' => $validated['commission_rate'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('admin.agents.show', $user)
            ->with('success', 'Agent updated successfully.');
    }

    public function deleteAgent(User $user)
    {
        abort_if(!$user->role === 'agent', 404);
        $user->delete();
        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent deleted successfully.');
    }

    public function toggleAgentStatus(User $user)
    {
        abort_if($user->role !== 'agent', 404);
        
        $user->update([
            'status' => $user->status === 'active' ? 'banned' : 'active'
        ]);

        return back()->with('success', 
            'Agent status changed to ' . ($user->status === 'active' ? 'active' : 'banned') . ' successfully.');
    }

    public function addPointsToAgent(Request $request, User $user)
    {
        abort_if($user->role !== 'agent', 404);

        $validated = $request->validate([
            'points' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($user, $validated) {
            // Add points to agent
            $user->points = $user->points + $validated['points'];
            $user->save();

            // Record the transaction
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'point_credit',
                'amount' => $validated['points'],
                'status' => 'completed',
                'note' => $validated['note'] ?? 'Points added by admin',
            ]);
        });

        return back()->with('success', "Added {$validated['points']} points to agent successfully.");
    }

    public function showAgentPoints(User $user)
    {
        abort_if($user->role !== 'agent', 404);

        $pointTransactions = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['point_credit', 'point_debit'])
            ->latest()
            ->paginate(15);

        return view('admin.agents.points', compact('user', 'pointTransactions'));
    }
}
