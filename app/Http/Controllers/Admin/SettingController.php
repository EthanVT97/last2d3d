<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SettingController extends Controller
{
    public function index()
    {
        // Get time settings
        $timeSettings = Setting::where('key', 'lottery_time_settings')->first();
        $timeSettings = $timeSettings ? $timeSettings->value : [];

        // Get lottery settings
        $lotteryTypes = ['2d', '3d', 'thai'];
        $lotterySettings = [];
        
        foreach ($lotteryTypes as $type) {
            $setting = Setting::where('key', 'lottery_settings')
                ->where('type', $type)
                ->first();
            $lotterySettings[$type] = $setting ? $setting->value : [];
        }

        return view('admin.settings.index', [
            'timeSettings' => $timeSettings,
            'lotterySettings' => $lotterySettings
        ]);
    }

    public function edit($type)
    {
        if (!in_array($type, ['2d', '3d', 'thai'])) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Invalid lottery type.');
        }

        $settings = Setting::where('key', 'lottery_settings')
            ->where('type', $type)
            ->first();

        if (!$settings) {
            $settings = new Setting();
            $settings->key = 'lottery_settings';
            $settings->type = $type;
            $settings->value = [
                'min_amount' => 100,
                'max_amount' => 50000,
                'is_active' => true,
                'disabled_numbers' => []
            ];
        }

        return view('admin.settings.lottery', [
            'settings' => $settings,
            'type' => $type
        ]);
    }

    public function updateTimeSettings(Request $request)
    {
        $request->validate([
            '2d_morning_close_time' => 'required|date_format:H:i',
            '2d_morning_result_time' => 'required|date_format:H:i|after:2d_morning_close_time',
            '2d_evening_close_time' => 'required|date_format:H:i',
            '2d_evening_result_time' => 'required|date_format:H:i|after:2d_evening_close_time',
            '3d_close_time' => 'required|date_format:H:i',
            '3d_result_time' => 'required|date_format:H:i|after:3d_close_time',
            'thai_close_time' => 'required|date_format:H:i',
            'thai_result_time' => 'required|date_format:H:i|after:thai_close_time',
        ], [
            'required' => 'အချိန်ရွေးချယ်ပါ',
            'date_format' => 'အချိန်ဖြစ်ရမည်',
            'after' => 'ထီပေါက်ချိန်သည် ထီပိတ်ချိန်ထက် နောက်ကျရမည်',
        ]);

        $settings = Setting::where('key', 'lottery_time_settings')->first();
        if (!$settings) {
            $settings = new Setting();
            $settings->key = 'lottery_time_settings';
        }

        $settings->value = [
            '2d_morning_close_time' => $request->input('2d_morning_close_time'),
            '2d_morning_result_time' => $request->input('2d_morning_result_time'),
            '2d_evening_close_time' => $request->input('2d_evening_close_time'),
            '2d_evening_result_time' => $request->input('2d_evening_result_time'),
            '3d_close_time' => $request->input('3d_close_time'),
            '3d_result_time' => $request->input('3d_result_time'),
            'thai_close_time' => $request->input('thai_close_time'),
            'thai_result_time' => $request->input('thai_result_time'),
        ];

        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'ထီထိုးချိန် သတ်မှတ်ချက်များ အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
    }

    public function updateLotterySettings(Request $request, $type)
    {
        if (!in_array($type, ['2d', '3d', 'thai'])) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Invalid lottery type.');
        }

        $request->validate([
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'is_active' => 'boolean',
            'disabled_numbers' => 'nullable|array',
            'disabled_numbers.*' => [
                'required',
                'string',
                'regex:/^[0-9]{2}$/',
            ]
        ], [
            'required' => 'ထည့်သွင်းပေးပါ',
            'numeric' => 'ဂဏန်းဖြစ်ရမည်',
            'min' => 'အနည်းဆုံး 0 ဖြစ်ရမည်',
            'gt' => 'အများဆုံးပမာဏသည် အနည်းဆုံးပမာဏထက် များရမည်',
            'disabled_numbers.*.regex' => 'ဂဏန်းနှစ်လုံးဖြစ်ရမည်'
        ]);

        $settings = Setting::where('key', 'lottery_settings')
            ->where('type', $type)
            ->first();

        if (!$settings) {
            $settings = new Setting();
            $settings->key = 'lottery_settings';
            $settings->type = $type;
        }

        $settings->value = [
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'is_active' => $request->boolean('is_active'),
            'disabled_numbers' => $request->disabled_numbers ?? []
        ];
        
        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', strtoupper($type) . ' ထီ သတ်မှတ်ချက်များ အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
    }
}
