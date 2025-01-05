<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'agent']);
    }

    public function dashboard()
    {
        $user = auth()->user();
        $transactions = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['point_credit', 'point_debit'])
            ->latest()
            ->take(10)
            ->get();

        $userTransfers = Transaction::where('from_user_id', $user->id)
            ->where('type', 'point_transfer')
            ->latest()
            ->take(10)
            ->get();

        return view('agent.dashboard', compact('transactions', 'userTransfers'));
    }

    public function transferPoints(Request $request)
    {
        $agent = auth()->user();
        abort_if($agent->role !== 'agent', 404);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|numeric|min:1|max:' . $agent->points,
            'note' => 'nullable|string|max:255'
        ]);

        $user = User::findOrFail($validated['user_id']);
        abort_if($user->role === 'admin' || $user->role === 'agent', 403, 'Cannot transfer points to admin or agent');

        DB::transaction(function () use ($agent, $user, $validated) {
            // Deduct points from agent
            $agent->points = $agent->points - $validated['points'];
            $agent->save();

            // Add points to user
            $user->points = $user->points + $validated['points'];
            $user->save();

            // Record the transaction
            Transaction::create([
                'user_id' => $user->id,
                'from_user_id' => $agent->id,
                'type' => 'point_transfer',
                'amount' => $validated['points'],
                'status' => 'completed',
                'note' => $validated['note'] ?? 'Points transferred from agent',
            ]);
        });

        return back()->with('success', "Transferred {$validated['points']} points to user successfully.");
    }

    public function users()
    {
        $users = User::where('role', 'user')
            ->latest()
            ->paginate(15);

        return view('agent.users', compact('users'));
    }
}
