<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositAccount;
use Illuminate\Http\Request;

class DepositAccountController extends Controller
{
    public function index()
    {
        $depositAccounts = DepositAccount::latest()->paginate(10);
        return view('admin.deposit-accounts.index', compact('depositAccounts'));
    }

    public function create()
    {
        return view('admin.deposit-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'status' => 'boolean',
            'remarks' => 'nullable|string'
        ]);

        DepositAccount::create($validated);

        return redirect()->route('admin.deposit-accounts.index')
            ->with('success', 'Deposit account created successfully.');
    }

    public function edit(DepositAccount $depositAccount)
    {
        return view('admin.deposit-accounts.edit', compact('depositAccount'));
    }

    public function update(Request $request, DepositAccount $depositAccount)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'status' => 'boolean',
            'remarks' => 'nullable|string'
        ]);

        $depositAccount->update($validated);

        return redirect()->route('admin.deposit-accounts.index')
            ->with('success', 'Deposit account updated successfully.');
    }

    public function destroy(DepositAccount $depositAccount)
    {
        $depositAccount->delete();

        return redirect()->route('admin.deposit-accounts.index')
            ->with('success', 'Deposit account deleted successfully.');
    }
}
