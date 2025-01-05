<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'preferred_payment_method' => 'nullable|exists:payment_methods,id',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        foreach ($validated as $key => $value) {
            $user->{$key} = $value;
        }
        $user->save();

        return redirect()->route('profile.show')->with('success', 'ပရိုဖိုင်အချက်အလက်များ အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'လျှို့ဝှက်နံပါတ် အောင်မြင်စွာ ပြောင်းလဲပြီးပါပြီ။');
    }

    public function referrals()
    {
        $user = auth()->user();
        $referrals = User::where('referred_by', $user->id)
                        ->with(['transactions' => function($query) {
                            $query->where('type', 'deposit')
                                 ->where('status', 'completed');
                        }])
                        ->paginate(10);

        return view('profile.referrals', compact('referrals'));
    }
}
