<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LotterySetting;
use Illuminate\Http\Request;

class LotterySettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $settings = LotterySetting::all();
        return view('admin.settings.lottery', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:2d,3d',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'min_number' => 'required|integer|min:0',
            'max_number' => 'required|integer|gt:min_number',
        ]);

        LotterySetting::updateOrCreate(
            ['type' => $request->type],
            [
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
                'min_number' => $request->min_number,
                'max_number' => $request->max_number,
                'is_active' => $request->has('is_active'),
            ]
        );

        return redirect()->route('admin.settings.lottery')
            ->with('success', 'Lottery settings updated successfully');
    }
}
