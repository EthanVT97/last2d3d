<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::where('key', 'lottery_time_settings')
            ->first();

        $timeSettings = $settings ? $settings->value : [];

        return view('admin.settings.index', [
            'settings' => $timeSettings
        ]);
    }

    public function update(Request $request)
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

        $timeSettings = [
            '2d_morning_close_time' => $request->input('2d_morning_close_time'),
            '2d_morning_result_time' => $request->input('2d_morning_result_time'),
            '2d_evening_close_time' => $request->input('2d_evening_close_time'),
            '2d_evening_result_time' => $request->input('2d_evening_result_time'),
            '3d_close_time' => $request->input('3d_close_time'),
            '3d_result_time' => $request->input('3d_result_time'),
            'thai_close_time' => $request->input('thai_close_time'),
            'thai_result_time' => $request->input('thai_result_time'),
        ];

        Setting::updateOrCreate(
            ['key' => 'lottery_time_settings'],
            ['value' => $timeSettings]
        );

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'ထီထိုးချိန် သတ်မှတ်ချက်များကို အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ');
    }

    public function edit($type)
    {
        $settings = Setting::where('key', 'lottery_settings')
            ->where('type', $type)
            ->first();

        $defaultSettings = [
            'min_amount' => 100,
            'max_amount' => 10000,
            'min_number' => $type === '2d' ? 0 : ($type === '3d' ? 0 : 1),
            'max_number' => $type === '2d' ? 99 : ($type === '3d' ? 999 : 999999),
            'is_active' => true
        ];

        $lotterySettings = $settings ? $settings->value : $defaultSettings;

        return view('admin.settings.edit', [
            'type' => $type,
            'settings' => $lotterySettings
        ]);
    }

    public function updateLotterySettings(Request $request, $type)
    {
        $request->validate([
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gt:min_amount',
            'min_number' => 'required|numeric|min:0',
            'max_number' => 'required|numeric|gt:min_number',
            'is_active' => 'boolean'
        ], [
            'required' => ':attribute ဖြည့်သွင်းရန်လိုအပ်ပါသည်',
            'numeric' => ':attribute သည် ဂဏန်းဖြစ်ရပါမည်',
            'min' => ':attribute သည် :min ထက်ကြီးရပါမည်',
            'gt' => ':attribute သည် :value ထက်ကြီးရပါမည်'
        ]);

        $settings = [
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
            'min_number' => $request->input('min_number'),
            'max_number' => $request->input('max_number'),
            'is_active' => $request->boolean('is_active')
        ];

        Setting::updateOrCreate(
            [
                'key' => 'lottery_settings',
                'type' => $type
            ],
            ['value' => $settings]
        );

        return redirect()
            ->route('admin.settings.edit', $type)
            ->with('success', 'ထီအမျိုးအစားအလိုက် သတ်မှတ်ချက်များကို အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ');
    }
}
