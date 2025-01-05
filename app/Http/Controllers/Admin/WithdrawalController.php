<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $withdrawals = Transaction::with(['user', 'approvedBy', 'rejectedBy'])
            ->where('type', 'withdrawal')
            ->latest()
            ->paginate(15);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Transaction $withdrawal)
    {
        if ($withdrawal->type !== 'withdrawal' || $withdrawal->status !== 'pending') {
            return back()->with('error', 'Invalid withdrawal transaction.');
        }

        DB::beginTransaction();
        try {
            $withdrawal->update([
                'status' => 'completed',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'ငွေထုတ်ယူမှု အတည်ပြုပြီးပါပြီ။');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'ငွေထုတ်ယူမှု အတည်ပြုခြင်း မအောင်မြင်ပါ။');
        }
    }

    public function reject(Request $request, Transaction $withdrawal)
    {
        if ($withdrawal->type !== 'withdrawal' || $withdrawal->status !== 'pending') {
            return back()->with('error', 'Invalid withdrawal transaction.');
        }

        $request->validate([
            'admin_note' => ['required', 'string', 'max:255']
        ]);

        DB::beginTransaction();
        try {
            // Refund the amount to user's balance
            $withdrawal->user->increment('balance', $withdrawal->amount);

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'rejected',
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'admin_note' => $request->admin_note
            ]);

            DB::commit();
            return back()->with('success', 'ငွေထုတ်ယူမှု ငြင်းပယ်ပြီးပါပြီ။');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'ငွေထုတ်ယူမှု ငြင်းပယ်ခြင်း မအောင်မြင်ပါ။');
        }
    }
}
