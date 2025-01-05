<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Play;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayController extends Controller
{
    public function index(Request $request)
    {
        $query = Play::with('user')
                    ->latest();

        // Apply type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $plays = $query->paginate(15);

        return view('admin.plays.index', compact('plays'));
    }

    public function show(Play $play)
    {
        $play->load('user');
        return view('admin.plays.show', compact('play'));
    }

    public function update(Request $request, Play $play)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $play->update([
                'status' => $request->status,
                'admin_note' => $request->admin_note,
            ]);

            DB::commit();
            return back()->with('success', 'ထီထိုးမှု အခြေအနေ ပြောင်းလဲခြင်း အောင်မြင်ပါသည်။');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'အခြေအနေ ပြောင်းလဲခြင်း မအောင်မြင်ပါ။ ထပ်မံကြိုးစားကြည့်ပါ။');
        }
    }

    /**
     * Approve multiple plays at once.
     */
    public function approveAll(Request $request)
    {
        $request->validate([
            'play_ids' => 'required|array',
            'play_ids.*' => 'exists:plays,id'
        ]);

        DB::beginTransaction();
        try {
            $plays = Play::whereIn('id', json_decode($request->play_ids))
                ->where('status', 'pending')
                ->get();

            foreach ($plays as $play) {
                $play->update(['status' => 'approved']);
            }

            DB::commit();
            return back()->with('success', count($plays) . ' ထီထိုးမှုများကို အတည်ပြုပြီးပါပြီ။');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'အတည်ပြုခြင်း မအောင်မြင်ပါ။ ထပ်မံကြိုးစားကြည့်ပါ။');
        }
    }

    private function calculateWinningAmount(Play $play)
    {
        switch ($play->type) {
            case '2d':
                return $play->amount * 85; // 85x for 2D
            case '3d':
                return $play->amount * 500; // 500x for 3D
            case 'thai':
                return $play->amount * 100; // 100x for Thai lottery
            case 'laos':
                return $play->amount * 100; // 100x for Laos lottery
            default:
                return 0;
        }
    }
}
