<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTransactionController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->transactions()->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function deposit()
    {
        return view('transactions.deposit');
    }

    public function storeDeposit(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
            'payment_method' => ['required', 'string'],
            'transaction_id' => ['required', 'string', 'unique:transactions,reference_id'],
            'screenshot' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('screenshot')->store('transaction-proofs', 'public');

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'deposit',
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'reference_id' => $request->transaction_id,
            'proof_image' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'ငွေသွင်းရန် တောင်းဆိုမှု အောင်မြင်ပါသည်။ ကျေးဇူးပြု၍ အတည်ပြုချိန်အနည်းငယ် စောင့်ဆိုင်းပေးပါ။');
    }

    public function withdraw()
    {
        return view('transactions.withdraw');
    }

    public function storeWithdraw(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1000', 'max:' . Auth::user()->balance],
            'payment_method' => ['required', 'string'],
            'account_info' => ['required', 'string'],
        ]);

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'withdraw',
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'account_info' => $request->account_info,
            'status' => 'pending',
        ]);

        // Deduct from user's balance
        $user = Auth::user();
        $user->updateBalance($request->amount, 'subtract');

        return redirect()->route('transactions.index')
            ->with('success', 'ငွေထုတ်ရန် တောင်းဆိုမှု အောင်မြင်ပါသည်။ ကျေးဇူးပြု၍ အတည်ပြုချိန်အနည်းငယ် စောင့်ဆိုင်းပေးပါ။');
    }
}
