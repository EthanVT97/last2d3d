<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function create()
    {
        return view('user.withdrawals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
            'payment_method' => ['required', 'string'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
        ]);

        $user = auth()->user();

        // Check if user has sufficient balance
        if ($user->balance < $request->amount) {
            return back()->with('error', 'လက်ကျန်ငွေ မလုံလောက်ပါ။');
        }

        // Check if there's any pending withdrawal
        $pendingWithdrawal = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->exists();

        if ($pendingWithdrawal) {
            return back()->with('error', 'ယခင် ငွေထုတ်ယူမှု စစ်ဆေးနေဆဲ ဖြစ်ပါသည်။');
        }

        DB::beginTransaction();
        try {
            // Create withdrawal transaction
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $request->amount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'metadata' => [
                    'account_name' => $request->account_name,
                    'account_number' => $request->account_number,
                ],
                'approval_level' => 'admin'
            ]);

            // Deduct user balance
            User::where('id', $user->id)->update([
                'balance' => $user->balance - $request->amount
            ]);

            DB::commit();
            return redirect()->route('user.transactions.index')->with('success', 'ငွေထုတ်ယူရန် တောင်းဆိုမှု အောင်မြင်ပါသည်။');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'ငွေထုတ်ယူရန် တောင်းဆိုမှု မအောင်မြင်ပါ။');
        }
    }
}
