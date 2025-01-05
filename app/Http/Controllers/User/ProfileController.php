<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function show()
    {
        return view('user.profile.show');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users,phone,' . auth()->id()],
        ]);

        $user = auth()->user();
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'phone' => $request->phone
        ]);

        return back()->with('success', 'ပရိုဖိုင် အချက်အလက်များ ပြောင်းလဲပြီးပါပြီ။');
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'စကားဝှက် ပြောင်းလဲခြင်း အောင်မြင်ပါသည်။');
    }

    /**
     * Show user's referrals
     */
    public function referrals()
    {
        $user = Auth::user();
        $referrals = User::where('referred_by', $user->referral_code)
            ->latest()
            ->paginate(10);

        return view('user.profile.referrals', compact('referrals'));
    }
}
