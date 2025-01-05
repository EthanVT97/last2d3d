<?php

namespace App\Services;

use App\Models\Draw;
use App\Models\Play;
use App\Models\User;
use App\Models\LotteryResult;
use App\Events\DrawResultAnnounced;
use App\Events\WinningCalculated;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;

class LotteryManagementService
{
    /**
     * Create a new draw for a specific lottery type
     */
    public function createDraw(string $type, Carbon $drawTime): Draw
    {
        return Draw::create([
            'type' => $type,
            'draw_time' => $drawTime,
            'status' => 'pending',
        ]);
    }

    /**
     * Record lottery result and process winners
     */
    public function recordResult(Draw $draw, string $number): LotteryResult
    {
        if ($draw->status !== 'pending') {
            throw new \Exception('Invalid draw status');
        }

        DB::beginTransaction();

        try {
            // Create result record
            $result = LotteryResult::create([
                'draw_id' => $draw->id,
                'number' => $number,
            ]);

            // Update draw status
            $draw->update(['status' => 'completed']);

            // Process winning plays
            $this->processWinners($draw, $number);

            DB::commit();

            // Broadcast result
            Event::dispatch(new DrawResultAnnounced($draw->type, $number));

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to record lottery result: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process winners for a draw
     */
    public function processWinners(Draw $draw, string $number): void
    {
        // Get all winning plays
        $winningPlays = Play::where('draw_id', $draw->id)
            ->where('number', $number)
            ->where('status', 'pending')
            ->get();

        // Process each winning play
        foreach ($winningPlays as $play) {
            DB::beginTransaction();

            try {
                $user = $play->user;
                $prizeAmount = $this->calculatePrize($play);

                // Update play status and prize amount
                $play->update([
                    'status' => 'won',
                    'prize_amount' => $prizeAmount
                ]);

                // Add prize amount to user balance
                $user->increment('balance', $prizeAmount);

                // Create transaction record
                $user->transactions()->create([
                    'type' => 'prize',
                    'amount' => $prizeAmount,
                    'description' => "{$draw->type} ထီပေါက်ငွေ - နံပါတ် {$number}",
                    'status' => 'completed'
                ]);

                DB::commit();

                // Notify user
                Event::dispatch(new WinningCalculated($user, $play));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to process winning play {$play->id}: " . $e->getMessage());
            }
        }

        // Update non-winning plays
        Play::where('draw_id', $draw->id)
            ->where('number', '!=', $number)
            ->where('status', 'pending')
            ->update(['status' => 'lost']);
    }

    /**
     * Calculate prize amount based on play type and amount
     */
    public function calculatePrize(Play $play): float
    {
        $multiplier = match($play->draw->type) {
            '2d' => 85,
            '3d' => 500,
            default => throw new \Exception('Invalid lottery type')
        };

        return $play->amount * $multiplier;
    }

    /**
     * Validate play before accepting
     */
    public function validatePlay(string $type, string $number, float $amount, User $user): void
    {
        // Check if number format is valid
        if (!$this->isValidNumber($type, $number)) {
            throw new \Exception('နံပါတ်ဖော်မတ် မမှန်ကန်ပါ။');
        }

        // Check if number is blacklisted
        if ($this->isBlacklistedNumber($type, $number)) {
            throw new \Exception('ဤနံပါတ်ကို ထိုးခွင့်မပြုပါ။');
        }

        // Check minimum bet amount
        $minBet = match($type) {
            '2d' => 100,
            '3d' => 100,
            default => throw new \Exception('Invalid lottery type')
        };

        if ($amount < $minBet) {
            throw new \Exception("အနည်းဆုံး {$minBet} ကျပ် ထိုးရပါမည်။");
        }

        // Check if user has sufficient balance
        if ($user->balance < $amount) {
            throw new \Exception('လက်ကျန်ငွေ မလုံလောက်ပါ။');
        }
    }

    /**
     * Check if number format is valid
     */
    public function isValidNumber(string $type, string $number): bool
    {
        return match($type) {
            '2d' => preg_match('/^[0-9]{2}$/', $number),
            '3d' => preg_match('/^[0-9]{3}$/', $number),
            default => false
        };
    }

    /**
     * Check if number is blacklisted
     */
    public function isBlacklistedNumber(string $type, string $number): bool
    {
        // TODO: Implement blacklist logic
        return false;
    }

    /**
     * Get next draw time
     */
    public function getNextDrawTime(string $type): Carbon
    {
        $now = now();
        
        if ($type === '2d') {
            // 2D draws at 12:00 PM and 4:30 PM
            if ($now->hour < 12) {
                return $now->setTime(12, 0);
            } elseif ($now->hour < 16 || ($now->hour == 16 && $now->minute < 30)) {
                return $now->setTime(16, 30);
            } else {
                return $now->addDay()->setTime(12, 0);
            }
        } elseif ($type === '3d') {
            // 3D draws every Sunday and Thursday at 4:30 PM
            while (!in_array($now->dayOfWeek, [0, 4]) || ($now->hour >= 16 && $now->minute >= 30)) {
                $now = $now->addDay()->setTime(16, 30);
            }
            return $now;
        }

        throw new \Exception('Invalid lottery type');
    }
}
