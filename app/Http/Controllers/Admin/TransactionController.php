<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $query = Transaction::with(['user', 'approvedBy', 'rejectedBy'])
            ->latest();

        // If agent, only show their transactions
        if (Auth::user()->role === 'agent') {
            $query->where('approval_level', 'agent');
        }

        $transactions = $query->paginate(15);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'approvedBy', 'rejectedBy', 'depositAccount']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function approve(Transaction $transaction)
    {
        $user = Auth::user();

        // Check if user has permission to approve
        if ($transaction->approval_level === 'admin' && $user->role !== 'admin') {
            return back()->with('error', 'အက်ဒမင်သာ အတည်ပြုခွင့်ရှိပါသည်');
        }

        if ($transaction->approval_level === 'agent' && !in_array($user->role, ['admin', 'agent'])) {
            return back()->with('error', 'အက်ဒမင် သို့မဟုတ် အေးဂျင့်သာ အတည်ပြုခွင့်ရှိပါသည်');
        }

        $transaction->update([
            'status' => 'completed',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'admin_note' => request('note')
        ]);

        // Update user balance for deposits and withdrawals
        if ($transaction->type === 'deposit') {
            $transaction->user->increment('balance', $transaction->amount);
        } elseif ($transaction->type === 'withdrawal') {
            $transaction->user->decrement('balance', $transaction->amount);
        }

        return back()->with('success', 'လုပ်ဆောင်မှု အောင်မြင်ပါသည်');
    }

    public function reject(Transaction $transaction)
    {
        $user = Auth::user();

        // Check if user has permission to reject
        if ($transaction->approval_level === 'admin' && $user->role !== 'admin') {
            return back()->with('error', 'အက်ဒမင်သာ ငြင်းပယ်ခွင့်ရှိပါသည်');
        }

        if ($transaction->approval_level === 'agent' && !in_array($user->role, ['admin', 'agent'])) {
            return back()->with('error', 'အက်ဒမင် သို့မဟုတ် အေးဂျင့်သာ ငြင်းပယ်ခွင့်ရှိပါသည်');
        }

        $transaction->update([
            'status' => 'rejected',
            'rejected_by' => $user->id,
            'rejected_at' => now(),
            'admin_note' => request('note')
        ]);

        return back()->with('success', 'လုပ်ဆောင်မှု အောင်မြင်ပါသည်');
    }
}
