<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()
                    ->withCount(['plays', 'transactions'])
                    ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['user', 'admin', 'agent'])],
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'အသုံးပြုသူ အသစ်ထည့်သွင်းခြင်း အောင်မြင်ပါသည်။');
    }

    public function show(User $user)
    {
        $user->loadCount(['plays', 'transactions']);
        
        $recentPlays = $user->plays()
                           ->with('result')
                           ->latest()
                           ->take(5)
                           ->get();
                           
        $recentTransactions = $user->transactions()
                                  ->latest()
                                  ->take(5)
                                  ->get();

        return view('admin.users.show', compact('user', 'recentPlays', 'recentTransactions'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['user', 'admin', 'agent'])],
            'balance' => ['required', 'numeric', 'min:0'],
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => $request->role,
            'balance' => $request->balance,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'အသုံးပြုသူ အချက်အလက် ပြောင်းလဲခြင်း အောင်မြင်ပါသည်။');
    }

    public function destroy(User $user)
    {
        // Don't allow deleting users with transactions or plays
        if ($user->transactions()->exists() || $user->plays()->exists()) {
            return back()->with('error', 'ဤအသုံးပြုသူတွင် ထီထိုးမှု သို့မဟုတ် ငွေသွင်း/ထုတ်မှု မှတ်တမ်းများ ရှိနေသောကြောင့် ဖျက်၍မရပါ။');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'အသုံးပြုသူအား ဖျက်ပစ်ခြင်း အောင်မြင်ပါသည်။');
    }

    public function ban(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Admin အသုံးပြုသူအား ပိတ်ပင်၍မရပါ။');
        }

        $user->update([
            'banned_at' => now(),
            'ban_reason' => request('reason')
        ]);

        return back()->with('success', 'အသုံးပြုသူအား ပိတ်ပင်ခြင်း အောင်မြင်ပါသည်။');
    }

    public function unban(User $user)
    {
        $user->update([
            'banned_at' => null,
            'ban_reason' => null
        ]);

        return back()->with('success', 'အသုံးပြုသူ၏ ပိတ်ပင်ခြင်းအား ပယ်ဖျက်ခြင်း အောင်မြင်ပါသည်။');
    }
}
