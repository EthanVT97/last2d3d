<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->middleware('auth');
    }

    /**
     * Show deposit form
     */
    public function showDepositForm()
    {
        $depositAccounts = $this->paymentService->getDepositAccounts();
        $transactions = Transaction::where('user_id', auth()->id())
            ->where('type', 'deposit')
            ->latest()
            ->paginate(10);

        return view('payment.deposit', compact('depositAccounts', 'transactions'));
    }

    /**
     * Show withdrawal form
     */
    public function showWithdrawForm()
    {
        $paymentMethods = $this->paymentService->getPaymentMethods();
        $transactions = Transaction::where('user_id', auth()->id())
            ->where('type', 'withdraw')
            ->latest()
            ->paginate(10);

        return view('payment.withdraw', compact('paymentMethods', 'transactions'));
    }

    /**
     * Process deposit request
     */
    public function processDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'screenshot' => 'required|image|max:5120', // 5MB max
        ]);

        try {
            // Upload screenshot
            $path = $request->file('screenshot')->store('payment-screenshots', 'public');

            // Create deposit request
            $transaction = $this->paymentService->createDepositRequest(auth()->user(), [
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'screenshot' => $path,
            ]);

            return redirect()->route('deposit')
                ->with('success', 'ငွေသွင်း တောင်းဆိုချက် အောင်မြင်ပါသည်။ ခဏစောင့်ပါ။');
        } catch (\Exception $e) {
            // Delete uploaded file if transaction fails
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()
                ->with('error', 'ငွေသွင်း တောင်းဆိုချက် မအောင်မြင်ပါ။ ထပ်မံကြိုးစားကြည့်ပါ။')
                ->withInput();
        }
    }

    /**
     * Process withdrawal request
     */
    public function processWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:5000',
                'max:' . auth()->user()->balance
            ],
            'payment_method' => 'required|string',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
        ]);

        try {
            // Create withdrawal request
            $transaction = $this->paymentService->createWithdrawalRequest(auth()->user(), [
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
            ]);

            return redirect()->route('withdraw')
                ->with('success', 'ငွေထုတ် တောင်းဆိုချက် အောင်မြင်ပါသည်။ ခဏစောင့်ပါ။');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'ငွေထုတ် တောင်းဆိုချက် မအောင်မြင်ပါ။ ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show transaction history
     */
    public function transactionHistory()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('payment.history', compact('transactions'));
    }
}
