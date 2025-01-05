<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    public function index()
    {
        $query = User::where('is_agent', true)
            ->withCount(['referrals', 'plays'])
            ->latest();

        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $agents = $query->paginate(10);

        // Calculate stats
        $stats = [
            'total_agents' => User::where('is_agent', true)->count(),
            'active_agents' => User::where('is_agent', true)->whereNull('banned_at')->count(),
            'total_referrals' => User::whereNotNull('referred_by')->count(),
            'total_commission' => User::where('is_agent', true)->sum('commission_balance')
        ];

        return view('admin.agents.index', compact('agents', 'stats'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100']
        ]);

        $agent = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_agent' => true,
            'commission_rate' => $validated['commission_rate'],
            'commission_balance' => 0,
            'points' => 0
        ]);

        return redirect()->route('admin.agents.show', $agent)
            ->with('success', 'Agent created successfully.');
    }

    public function show(User $agent)
    {
        if (!$agent->is_agent) {
            abort(404);
        }

        $agent->loadCount(['referrals', 'plays']);
        $agent->load(['referrals' => function ($query) {
            $query->latest()->take(5);
        }]);

        return view('admin.agents.show', compact('agent'));
    }

    public function edit(User $agent)
    {
        if (!$agent->is_agent) {
            abort(404);
        }

        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, User $agent)
    {
        if (!$agent->is_agent) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($agent->id)],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed']
        ]);

        $agent->name = $validated['name'];
        $agent->phone = $validated['phone'];
        $agent->commission_rate = $validated['commission_rate'];

        if (isset($validated['password'])) {
            $agent->password = Hash::make($validated['password']);
        }

        $agent->save();

        return redirect()->route('admin.agents.show', $agent)
            ->with('success', 'Agent updated successfully.');
    }

    public function toggleStatus(User $agent)
    {
        if (!$agent->is_agent) {
            abort(404);
        }

        if ($agent->banned_at) {
            $agent->banned_at = null;
            $message = 'Agent activated successfully.';
        } else {
            $agent->banned_at = now();
            $message = 'Agent deactivated successfully.';
        }

        $agent->save();

        return back()->with('success', $message);
    }

    public function destroy(User $agent)
    {
        if (!$agent->is_agent) {
            abort(404);
        }

        // Instead of deleting, just remove agent status
        $agent->is_agent = false;
        $agent->commission_rate = null;
        $agent->save();

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent removed successfully.');
    }
}
