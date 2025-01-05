<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $withdrawals = Transaction::where('user_id', Auth::id())
                    ->where('type', 'withdraw')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();

        return view('withdraw.index', compact('withdrawals'));
    }

    public function create()
    {
        $withdrawals = Transaction::where('user_id', Auth::id())
                    ->where('type', 'withdraw')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();

        return view('withdraw.create', compact('withdrawals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5000|max:1000000',
            'payment_method' => 'required|in:kbz,wave,cbpay',
            'account_number' => 'required|string|min:8|max:15',
            'account_name' => 'required|string|max:100'
        ]);

        $user = Auth::user();

        if ($user->balance < $request->amount) {
            return back()->withErrors(['amount' => 'လက်ကျန်ငွေ မလုံလောက်ပါ။']);
        }

        try {
            DB::transaction(function () use ($request, $user) {
                // Create withdrawal transaction
                Transaction::create([
                    'user_id' => Auth::id(),
                    'type' => 'withdraw',
                    'amount' => $request->amount,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'reference_id' => $request->account_number . '_' . time(),
                    'metadata' => [
                        'account_number' => $request->account_number,
                        'account_name' => $request->account_name
                    ]
                ]);

                // Deduct from user's balance
                User::where('id', Auth::id())->update([
                    'balance' => DB::raw('balance - ' . $request->amount)
                ]);
            });

            return redirect()->route('dashboard')
                ->with('success', 'ငွေထုတ်ယူမှု အောင်မြင်ပါသည်။ မိနစ် ၃၀ အတွင်း သင့်အကောင့်သို့ လွှဲပေးပါမည်။');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'ငွေထုတ်ယူမှု မအောင်မြင်ပါ။ ထပ်မံကြိုးစားကြည့်ပါ။']);
        }
    }
}
