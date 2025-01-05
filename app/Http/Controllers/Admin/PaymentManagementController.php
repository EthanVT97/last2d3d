<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentManagementController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show payment management dashboard
     */
    public function index()
    {
        $pendingDeposits = Transaction::with('user')
            ->where('type', 'deposit')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pendingWithdrawals = Transaction::with('user')
            ->where('type', 'withdraw')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $recentTransactions = Transaction::with('user')
            ->whereIn('status', ['completed', 'rejected'])
            ->latest()
            ->limit(20)
            ->get();

        $stats = [
            'today_deposits' => Transaction::where('type', 'deposit')
                ->where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'today_withdrawals' => Transaction::where('type', 'withdraw')
                ->where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'pending_deposits_count' => $pendingDeposits->count(),
            'pending_withdrawals_count' => $pendingWithdrawals->count(),
        ];

        return view('admin.payment.index', compact(
            'pendingDeposits',
            'pendingWithdrawals',
            'recentTransactions',
            'stats'
        ));
    }

    /**
     * Show transaction details
     */
    public function show(Transaction $transaction)
    {
        return view('admin.payment.show', compact('transaction'));
    }

    /**
     * Approve deposit
     */
    public function approveDeposit(Transaction $transaction)
    {
        try {
            $this->paymentService->approveDeposit($transaction);
            return redirect()->back()->with('success', 'ငွေသွင်းခြင်း အတည်ပြုပြီးပါပြီ။');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'အတည်ပြုရာတွင် အမှားရှိနေပါသည်။ ထပ်မံကြိုးစားကြည့်ပါ။');
        }
    }

    /**
     * Approve withdrawal
     */
    public function approveWithdrawal(Transaction $transaction)
    {
        try {
            $this->paymentService->approveWithdrawal($transaction);
            return redirect()->back()->with('success', 'ငွေထုတ်ခြင်း အတည်ပြုပြီးပါပြီ။');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'အတည်ပြုရာတွင် အမှားရှိနေပါသည်။ ထပ်မံကြိုးစားကြည့်ပါ။');
        }
    }

    /**
     * Reject transaction
     */
    public function rejectTransaction(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        try {
            $this->paymentService->rejectTransaction($transaction, $request->reason);
            return redirect()->back()->with('success', 'ငွေလွှဲခြင်း ငြင်းပယ်ပြီးပါပြီ။');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ငြင်းပယ်ရာတွင် အမှားရှိနေပါသည်။ ထပ်မံကြိုးစားကြည့်ပါ။');
        }
    }

    /**
     * Show financial reports
     */
    public function reports()
    {
        $dailyStats = DB::table('transactions')
            ->select([
                DB::raw('DATE(created_at) as date'),
                'type',
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total_amount')
            ])
            ->whereIn('status', ['completed', 'rejected'])
            ->groupBy(['date', 'type', 'status'])
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        $monthlyStats = DB::table('transactions')
            ->select([
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                'type',
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total_amount')
            ])
            ->whereIn('status', ['completed', 'rejected'])
            ->groupBy(['month', 'type', 'status'])
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $paymentMethodStats = DB::table('transactions')
            ->select([
                'payment_method',
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total_amount')
            ])
            ->where('status', 'completed')
            ->groupBy(['payment_method', 'type'])
            ->get();

        return view('admin.payment.reports', compact(
            'dailyStats',
            'monthlyStats',
            'paymentMethodStats'
        ));
    }

    /**
     * Show payment settings
     */
    public function settings()
    {
        $paymentMethods = $this->paymentService->getPaymentMethods();
        return view('admin.payment.settings', compact('paymentMethods'));
    }

    /**
     * Update payment settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'payment_methods' => 'required|array',
            'payment_methods.*.name' => 'required|string',
            'payment_methods.*.account_name' => 'required|string',
            'payment_methods.*.account_number' => 'required|string',
        ]);

        // Update payment method settings in configuration
        // Implementation depends on how you store settings

        return redirect()->back()->with('success', 'ငွေပေးချေမှု အပြင်အဆင်များ ပြောင်းလဲပြီးပါပြီ။');
    }
}
