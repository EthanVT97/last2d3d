<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lottery;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect admin users to admin dashboard
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect regular users to home
        return redirect()->route('home');
    }

    private function getUserBalance()
    {
        return Transaction::where('user_id', auth()->id())
                         ->sum('amount');
    }

    private function getRecentPlays()
    {
        return Lottery::where('user_id', auth()->id())
                     ->latest()
                     ->take(5)
                     ->get();
    }

    private function getRecentWins()
    {
        return Lottery::where('user_id', auth()->id())
                     ->where('status', 'won')
                     ->latest()
                     ->take(5)
                     ->get();
    }
}
