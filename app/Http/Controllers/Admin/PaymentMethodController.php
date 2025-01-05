<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $methods = PaymentMethod::latest()->paginate(10);
        return view('admin.payment-methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'ငွေပေးချေမှုနည်းလမ်း အသစ်ထည့်သွင်းပြီးပါပြီ။');
    }

    public function edit(PaymentMethod $method)
    {
        return view('admin.payment-methods.edit', compact('method'));
    }

    public function update(Request $request, PaymentMethod $method)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code,' . $method->id,
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $method->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'ငွေပေးချေမှုနည်းလမ်း ပြင်ဆင်ပြီးပါပြီ။');
    }

    public function destroy(PaymentMethod $method)
    {
        $method->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'ငွေပေးချေမှုနည်းလမ်း ဖျက်ပြီးပါပြီ။');
    }
}
