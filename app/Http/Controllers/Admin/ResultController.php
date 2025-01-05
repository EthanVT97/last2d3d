<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LotteryResult;
use App\Models\Play;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $results = LotteryResult::with('creator')
            ->latest()
            ->paginate(15);

        return view('admin.results.index', compact('results'));
    }

    public function create()
    {
        return view('admin.results.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:2d,3d,thai,laos',
            'draw_time' => 'required|date',
            'numbers' => 'required|array',
            'numbers.*' => 'required|string',
            'prize_amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $result = LotteryResult::create([
                ...$validated,
                'created_by' => auth()->id(),
                'status' => 'pending'
            ]);

            // Process winning plays
            $this->processWinningPlays($result);
        });

        return redirect()->route('admin.results.index')
            ->with('success', 'ထီထွက်ဂဏန်း ထည့်သွင်းပြီးပါပြီ။');
    }

    public function edit(LotteryResult $result)
    {
        return view('admin.results.edit', compact('result'));
    }

    public function update(Request $request, LotteryResult $result)
    {
        $validated = $request->validate([
            'type' => 'required|in:2d,3d,thai,laos',
            'draw_time' => 'required|date',
            'numbers' => 'required|array',
            'numbers.*' => 'required|string',
            'prize_amount' => 'required|numeric|min:0',
        ]);

        $result->update($validated);

        return redirect()->route('admin.results.index')
            ->with('success', 'ထွက်ဂဏန်း ပြင်ဆင်ပြီးပါပြီ။');
    }

    public function destroy(LotteryResult $result)
    {
        $result->delete();

        return redirect()->route('admin.results.index')
            ->with('success', 'ထွက်ဂဏန်း ဖျက်ပြီးပါပြီ။');
    }

    protected function processWinningPlays(LotteryResult $result)
    {
        $plays = Play::where('type', $result->type)
            ->where('status', 'pending')
            ->whereDate('created_at', $result->draw_time->toDateString())
            ->get();

        foreach ($plays as $play) {
            $isWinner = $this->checkWinningNumber($play, $result);
            
            if ($isWinner) {
                $winAmount = $this->calculateWinAmount($play, $result);
                
                DB::transaction(function () use ($play, $winAmount) {
                    // Update play status
                    $play->update([
                        'status' => 'won',
                        'win_amount' => $winAmount
                    ]);

                    // Update user balance
                    $play->user->increment('balance', $winAmount);
                });
            } else {
                $play->update(['status' => 'lost']);
            }
        }
    }

    protected function checkWinningNumber($play, $result)
    {
        switch ($result->type) {
            case '2d':
                return in_array($play->numbers, $result->numbers);
            case '3d':
                return $play->numbers === $result->numbers[0];
            case 'thai':
                // Implement Thai lottery winning logic
                return false;
            case 'laos':
                return $play->numbers === $result->numbers[0];
            default:
                return false;
        }
    }

    protected function calculateWinAmount($play, $result)
    {
        $multipliers = [
            '2d' => 85,
            '3d' => 500,
            'thai' => [
                'first' => 1000,
                'last_two' => 65,
                'first_three' => 166,
                'last_three' => 166,
            ],
            'laos' => 800,
        ];

        switch ($result->type) {
            case '2d':
                return $play->amount * $multipliers['2d'];
            case '3d':
                return $play->amount * $multipliers['3d'];
            case 'thai':
                // Implement Thai lottery prize calculation
                return 0;
            case 'laos':
                return $play->amount * $multipliers['laos'];
            default:
                return 0;
        }
    }
}
