<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\LotteryResult;
use App\Services\LotteryManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LotteryManagementController extends Controller
{
    protected $lotteryService;

    public function __construct(LotteryManagementService $lotteryService)
    {
        $this->lotteryService = $lotteryService;
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display lottery management dashboard
     */
    public function index()
    {
        // Get next draws
        $next2DDraw = Draw::where('type', '2d')
            ->where('status', 'pending')
            ->orderBy('draw_time')
            ->first();

        $next3DDraw = Draw::where('type', '3d')
            ->where('status', 'pending')
            ->orderBy('draw_time')
            ->first();

        // Create draws if they don't exist
        if (!$next2DDraw) {
            $next2DDraw = $this->lotteryService->createDraw(
                '2d',
                $this->lotteryService->getNextDrawTime('2d')
            );
        }

        if (!$next3DDraw) {
            $next3DDraw = $this->lotteryService->createDraw(
                '3d',
                $this->lotteryService->getNextDrawTime('3d')
            );
        }

        // Get previous results with statistics
        $previousResults = LotteryResult::with(['draw' => function ($query) {
            $query->withCount(['plays', 'winningPlays'])
                ->withSum('winningPlays', 'prize_amount');
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('admin.lottery.results', compact(
            'next2DDraw',
            'next3DDraw',
            'previousResults'
        ));
    }

    /**
     * Record lottery result
     */
    public function recordResult(Request $request)
    {
        $request->validate([
            'draw_id' => 'required|exists:draws,id',
            'type' => 'required|in:2d,3d',
            'number' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === '2d' && !preg_match('/^\d{2}$/', $value)) {
                        $fail('2D နံပါတ်သည် ဂဏန်းနှစ်လုံး ဖြစ်ရမည်။');
                    }
                    if ($request->type === '3d' && !preg_match('/^\d{3}$/', $value)) {
                        $fail('3D နံပါတ်သည် ဂဏန်းသုံးလုံး ဖြစ်ရမည်။');
                    }
                },
            ],
        ]);

        try {
            $draw = Draw::findOrFail($request->draw_id);
            
            // Check if draw is pending
            if ($draw->status !== 'pending') {
                return back()->with('error', 'ထီဖွင့်ပြီးသား ဖြစ်နေပါသည်။');
            }

            // Record result and process winners
            $result = $this->lotteryService->recordResult($draw, $request->number);

            return back()->with('success', 'ထီပေါက်စဉ် မှတ်တမ်းတင်ပြီးပါပြီ။');
        } catch (\Exception $e) {
            return back()->with('error', 'ထီပေါက်စဉ် မှတ်တမ်းတင်ရာတွင် အမှားရှိနေပါသည်။ ထပ်မံကြိုးစားကြည့်ပါ။');
        }
    }

    /**
     * Show lottery statistics
     */
    public function statistics()
    {
        $stats = DB::table('plays')
            ->select([
                'type',
                DB::raw('COUNT(*) as total_plays'),
                DB::raw('SUM(CASE WHEN status = "won" THEN 1 ELSE 0 END) as total_wins'),
                DB::raw('SUM(amount) as total_bets'),
                DB::raw('SUM(CASE WHEN status = "won" THEN prize_amount ELSE 0 END) as total_payouts'),
            ])
            ->groupBy('type')
            ->get();

        $popularNumbers = DB::table('plays')
            ->select([
                'type',
                'number',
                DB::raw('COUNT(*) as play_count'),
                DB::raw('SUM(amount) as total_amount')
            ])
            ->groupBy(['type', 'number'])
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->get();

        $dailyStats = DB::table('plays')
            ->select([
                DB::raw('DATE(created_at) as date'),
                'type',
                DB::raw('COUNT(*) as total_plays'),
                DB::raw('SUM(amount) as total_amount')
            ])
            ->groupBy(['date', 'type'])
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return view('admin.lottery.statistics', compact(
            'stats',
            'popularNumbers',
            'dailyStats'
        ));
    }

    /**
     * Show risk management dashboard
     */
    public function riskManagement()
    {
        $highRiskNumbers = DB::table('plays')
            ->select([
                'type',
                'number',
                DB::raw('COUNT(*) as play_count'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('SUM(amount * CASE WHEN type = "2d" THEN 85 ELSE 500 END) as potential_payout')
            ])
            ->where('status', 'pending')
            ->groupBy(['type', 'number'])
            ->havingRaw('potential_payout > ?', [1000000]) // Numbers with potential payout > 1M
            ->orderBy('potential_payout', 'desc')
            ->get();

        $userExposure = DB::table('plays')
            ->select([
                'users.name',
                DB::raw('COUNT(*) as play_count'),
                DB::raw('SUM(amount) as total_bets'),
                DB::raw('SUM(amount * CASE WHEN type = "2d" THEN 85 ELSE 500 END) as potential_winnings')
            ])
            ->join('users', 'plays.user_id', '=', 'users.id')
            ->where('plays.status', 'pending')
            ->groupBy('users.id', 'users.name')
            ->orderBy('potential_winnings', 'desc')
            ->limit(20)
            ->get();

        return view('admin.lottery.risk-management', compact(
            'highRiskNumbers',
            'userExposure'
        ));
    }
}
