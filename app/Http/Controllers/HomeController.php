<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LotteryResult;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $recentResults = LotteryResult::latest('draw_time')
            ->take(5)
            ->get();

        $recentTransactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('home', compact('recentResults', 'recentTransactions'));
    }
}
