<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->transactions()->latest()->paginate(10);
        return view('user.transactions.index', compact('transactions'));
    }

    public function deposit()
    {
        $transactions = auth()->user()->transactions()->where('type', 'deposit')->latest()->paginate(10);
        return view('user.transactions.deposit', compact('transactions'));
    }

    public function withdrawal()
    {
        $transactions = auth()->user()->transactions()->where('type', 'withdrawal')->latest()->paginate(10);
        return view('user.transactions.withdrawal', compact('transactions'));
    }

    public function win()
    {
        $transactions = auth()->user()->transactions()->where('type', 'win')->latest()->paginate(10);
        return view('user.transactions.win', compact('transactions'));
    }

    public function loss()
    {
        $transactions = auth()->user()->transactions()->where('type', 'loss')->latest()->paginate(10);
        return view('user.transactions.loss', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        return view('user.transactions.show', compact('transaction'));
    }
}
