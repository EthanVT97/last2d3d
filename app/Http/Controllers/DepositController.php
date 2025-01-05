<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\DepositAccount;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
                    ->where('type', 'deposit')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();

        $paymentMethods = DepositAccount::where('status', true)
                    ->get()
                    ->mapWithKeys(function ($account) {
                        return [$account->bank_name => [
                            'name' => $account->bank_name,
                            'icon' => strtolower($account->bank_name) . '.svg',
                            'account_name' => $account->account_name,
                            'account_number' => $account->account_number,
                            'min_amount' => 1000,
                            'max_amount' => 1000000,
                            'instructions' => $account->remarks
                        ]];
                    })
                    ->toArray();
        
        return view('payment.deposit', compact('transactions', 'paymentMethods'));
    }

    public function create()
    {
        return redirect()->route('deposit.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'sender_phone' => 'required|string',
            'transaction_id' => 'required|string|unique:transactions,reference_id',
            'screenshot' => 'required|image|max:2048'
        ]);

        $screenshot = $request->file('screenshot');
        $path = $screenshot->store('public/deposits');

        // Find the deposit account
        $depositAccount = DepositAccount::where('bank_name', $request->payment_method)
            ->where('status', true)
            ->firstOrFail();

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'deposit',
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'reference_id' => $request->transaction_id,
            'deposit_account_id' => $depositAccount->id,
            'metadata' => [
                'sender_phone' => $request->sender_phone,
                'screenshot' => $path,
                'account_name' => $depositAccount->account_name,
                'account_number' => $depositAccount->account_number
            ]
        ]);

        return redirect()->route('deposit.index')
            ->with('success', 'ငွေသွင်းရန် တောင်းဆိုမှု အောင်မြင်ပါသည်။ စစ်ဆေးပြီးပါက ငွေသွင်းပေးပါမည်။');
    }
}
