<?php

namespace App\Http\Controllers;

use App\Models\Play;
use App\Models\Result;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LotteryController extends Controller
{
    protected $lotteryTypes = ['2d', '3d', 'thai', 'laos'];
    protected $minAmount = 100;
    protected $maxAmount = 50000;

    public function __construct()
    {
        $this->middleware('auth');
        // Redirect GET requests to store endpoints back to the lottery index
        $this->middleware(function ($request, $next) {
            if ($request->isMethod('get') && str_contains($request->path(), '/store')) {
                return redirect()->route('lottery.index');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $lotteryTypes = [
            '2d' => [
                'name' => '2D',
                'description' => '2 ဂဏန်း ထီ',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'primary'
            ],
            '3d' => [
                'name' => '3D',
                'description' => '3 ဂဏန်း ထီ',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'indigo'
            ],
            'thai' => [
                'name' => 'Thai Lottery',
                'description' => 'ထိုင်းထီ',
                'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'blue'
            ],
            'laos' => [
                'name' => 'Laos Lottery',
                'description' => 'လာအိုထီ',
                'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'green'
            ]
        ];

        return view('lottery.index', compact('lotteryTypes'));
    }

    public function show($type)
    {
        if (!in_array($type, $this->lotteryTypes)) {
            abort(404);
        }

        $plays = Play::where('user_id', Auth::id())
                    ->where('type', $type)
                    ->whereDate('created_at', Carbon::today())
                    ->orderBy('created_at', 'desc')
                    ->get();

        if ($type === '2d') {
            $latest_result = Result::where('type', '2d')
                                ->latest('drawn_at')
                                ->first();

            // Get lottery settings
            $settings = Setting::where('key', 'lottery_settings')
                ->where('type', $type)
                ->first();

            if (!$settings) {
                $settings = new Setting();
                $settings->value = [
                    'min_amount' => 100,
                    'max_amount' => 50000,
                    'is_active' => true,
                    'disabled_numbers' => []
                ];
            }

            return view("lottery.{$type}", compact('plays', 'latest_result', 'settings'));
        }

        if ($type === '3d') {
            $latest_result = Result::where('type', '3d')
                                ->latest('drawn_at')
                                ->first();

            if (!$latest_result) {
                $latest_result = (object)[
                    'number' => '---',
                    'drawn_at' => Carbon::now()->startOfMonth()
                ];
            }
            
            return view("lottery.{$type}", compact('plays', 'latest_result'));
        }

        if ($type === 'thai') {
            $latest_result = Result::where('type', 'thai')
                                ->latest('drawn_at')
                                ->first();

            if (!$latest_result) {
                $latest_result = (object)[
                    'metadata' => [
                        'first_prize' => '000000',
                        'last_two' => '00',
                        'first_three' => '000',
                        'last_three' => '000'
                    ],
                    'drawn_at' => Carbon::now()->startOfMonth()
                ];
            }
            
            return view("lottery.{$type}", compact('plays', 'latest_result'));
        }

        if ($type === 'laos') {
            $latest_result = Result::where('type', 'laos')
                                ->latest('drawn_at')
                                ->first();

            if (!$latest_result) {
                $latest_result = (object)[
                    'number' => '00000',
                    'drawn_at' => Carbon::now()->startOfMonth()
                ];
            }
            
            return view("lottery.{$type}", compact('plays', 'latest_result'));
        }

        return view("lottery.{$type}", compact('plays'));
    }

    /**
     * Store a new lottery play.
     */
    public function store(Request $request)
    {
        Log::info('Lottery store request', [
            'type' => $request->type,
            'numbers' => $request->numbers,
            'amount' => $request->amount,
            'all' => $request->all()
        ]);

        // Set number validation based on lottery type
        $numberValidation = match ($request->type) {
            '2d' => ['regex:/^[0-9]{2}$/'],
            '3d' => ['regex:/^[0-9]{3}$/'],
            'thai' => ['regex:/^[0-9]{6}$/'],
            'laos' => ['regex:/^[0-9]{5}$/'],
            default => ['regex:/^[0-9]+$/']
        };

        try {
            // Handle single play submission (from form)
            if ($request->has('numbers') && $request->has('amount')) {
                Log::info('Converting single play to plays array');
                $request->merge([
                    'plays' => [[
                        'number' => $request->numbers,
                        'amount' => $request->amount
                    ]]
                ]);
            }

            $validated = $request->validate([
                'type' => ['required', 'string', 'in:' . implode(',', $this->lotteryTypes)],
                'plays' => ['required', 'array', 'min:1'],
                'plays.*.number' => [
                    'required', 
                    'string', 
                    $numberValidation,
                    function ($attribute, $value, $fail) use ($request) {
                        $settings = Setting::where('key', 'lottery_settings')
                            ->where('type', $request->type)
                            ->first();
                        if (isset($settings->value['disabled_numbers']) && 
                            in_array($value, $settings->value['disabled_numbers'])) {
                            $fail("ဂဏန်း $value သည် ပိတ်ထားသောဂဏန်းဖြစ်ပါသည်။");
                        }
                    }
                ],
                'plays.*.amount' => ['required', 'numeric', 'min:' . $this->minAmount, 'max:' . $this->maxAmount],
            ]);

            Log::info('Validation passed', $validated);

            // Get lottery limits from settings
            $settings = Setting::where('key', 'lottery_settings')
                ->where('type', $request->type)
                ->first();

            if (!$settings) {
                if ($request->expectsJson()) {
                    return $this->jsonError('ထီအမျိုးအစား မှားယွင်းနေပါသည်။');
                }
                return back()->with('error', 'ထီအမျိုးအစား မှားယွင်းနေပါသည်။');
            }

            $limits = $settings->value;
            if (!is_array($limits)) {
                $limits = json_decode($limits, true);
            }

            // Check if lottery is active
            if (!($limits['is_active'] ?? false)) {
                if ($request->expectsJson()) {
                    return $this->jsonError('ထီထိုးခြင်း ယာယီပိတ်ထားပါသည်။');
                }
                return back()->with('error', 'ထီထိုးခြင်း ယာယီပိတ်ထားပါသည်။');
            }

            $totalAmount = collect($request->plays)->sum('amount');

            // Check total amount limits
            if ($totalAmount < ($limits['min_amount'] ?? $this->minAmount)) {
                if ($request->expectsJson()) {
                    return $this->jsonError('အနည်းဆုံး ' . number_format($limits['min_amount'] ?? $this->minAmount) . ' ကျပ် ထိုးရပါမည်။');
                }
                return back()->with('error', 'အနည်းဆုံး ' . number_format($limits['min_amount'] ?? $this->minAmount) . ' ကျပ် ထိုးရပါမည်။');
            }

            if ($totalAmount > ($limits['max_amount'] ?? $this->maxAmount)) {
                if ($request->expectsJson()) {
                    return $this->jsonError('အများဆုံး ' . number_format($limits['max_amount'] ?? $this->maxAmount) . ' ကျပ်သာ ထိုးနိုင်ပါသည်။');
                }
                return back()->with('error', 'အများဆုံး ' . number_format($limits['max_amount'] ?? $this->maxAmount) . ' ကျပ်သာ ထိုးနိုင်ပါသည်။');
            }

            // Check user balance
            if ($request->user()->balance < $totalAmount) {
                if ($request->expectsJson()) {
                    return $this->jsonError('လက်ကျန်ငွေ မလုံလောက်ပါ။');
                }
                return back()->with('error', 'လက်ကျန်ငွေ မလုံလောက်ပါ။');
            }

            // Check if 2D lottery is closed for current session
            if ($request->type === '2d') {
                $now = Carbon::now();
                
                // Get time settings
                $timeSettings = Setting::where('key', 'lottery_time_settings')->first();
                $settings = $timeSettings ? $timeSettings->value : [];
                
                // Get morning and evening cutoff times
                $morningCutoff = isset($settings['2d_morning_close_time']) 
                    ? Carbon::createFromFormat('H:i', $settings['2d_morning_close_time'])
                    : Carbon::createFromTime(11, 30);
                    
                $eveningCutoff = isset($settings['2d_evening_close_time'])
                    ? Carbon::createFromFormat('H:i', $settings['2d_evening_close_time'])
                    : Carbon::createFromTime(16, 30);

                // Validate session
                if (!$request->has('session')) {
                    if ($request->expectsJson()) {
                        return $this->jsonError('ထိုးမည့်အချိန် ရွေးချယ်ပေးပါ။');
                    }
                    return back()->with('error', 'ထိုးမည့်အချိန် ရွေးချယ်ပေးပါ။');
                }

                // Check if morning session is still available
                $canPlayMorning = $now->hour < $morningCutoff->hour || 
                    ($now->hour == $morningCutoff->hour && $now->minute < $morningCutoff->minute);
                
                // Check if evening session is available
                $canPlayEvening = ($now->hour < $eveningCutoff->hour || 
                    ($now->hour == $eveningCutoff->hour && $now->minute < $eveningCutoff->minute)) 
                    && ($now->hour > $morningCutoff->hour || 
                    ($now->hour == $morningCutoff->hour && $now->minute >= $morningCutoff->minute));

                if ($request->session === 'morning') {
                    if (!$canPlayMorning) {
                        if ($request->expectsJson()) {
                            return $this->jsonError('မနက်ပိုင်း ထီပိတ်ချိန် ကျော်လွန်သွားပါပြီ။');
                        }
                        return back()->with('error', 'မနက်ပိုင်း ထီပိတ်ချိန် ကျော်လွန်သွားပါပြီ။');
                    }
                } else if ($request->session === 'evening') {
                    if (!$canPlayEvening) {
                        if ($request->expectsJson()) {
                            return $this->jsonError('ညနေပိုင်း ထီပိတ်ချိန် ကျော်လွန်သွားပါပြီ။');
                        }
                        return back()->with('error', 'ညနေပိုင်း ထီပိတ်ချိန် ကျော်လွန်သွားပါပြီ။');
                    }
                } else {
                    if ($request->expectsJson()) {
                        return $this->jsonError('ထိုးမည့်အချိန် မှားယွင်းနေပါသည်။');
                    }
                    return back()->with('error', 'ထိုးမည့်အချိန် မှားယွင်းနေပါသည်။');
                }
            }

            DB::beginTransaction();
            try {
                $plays = collect();
                foreach ($request->plays as $play) {
                    $plays->push(Play::create([
                        'user_id' => $request->user()->id,
                        'type' => $request->type,
                        'number' => $play['number'],
                        'amount' => $play['amount'],
                        'status' => 'approved'
                    ]));
                }

                // Create transaction record
                Transaction::create([
                    'user_id' => $request->user()->id,
                    'type' => 'bet',
                    'amount' => $totalAmount,
                    'status' => 'completed',
                    'metadata' => [
                        'type' => $request->type,
                        'plays' => $plays->pluck('id')->toArray()
                    ]
                ]);

                // Deduct user balance
                $request->user()->decrement('balance', $totalAmount);

                DB::commit();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'ထီထိုးခြင်း အောင်မြင်ပါသည်။'
                    ]);
                }
                return redirect()->route('lottery.' . $request->type)->with('success', 'ထီထိုးခြင်း အောင်မြင်ပါသည်။');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error creating play: ' . $e->getMessage());
                if ($request->expectsJson()) {
                    return $this->jsonError('ထီထိုးခြင်း မအောင်မြင်ပါ။ ထပ်မံကြိုးစားကြည့်ပါ။');
                }
                return back()->with('error', 'ထီထိုးခြင်း မအောင်မြင်ပါ။ ထပ်မံကြိုးစားကြည့်ပါ။');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return $this->jsonError('ထီထိုးခြင်း မအောင်မြင်ပါ။ ထပ်မံကြိုးစားကြည့်ပါ။');
            }
            return back()->with('error', 'ထီထိုးခြင်း မအောင်မြင်ပါ။ ထပ်မံကြိုးစားကြည့်ပါ။');
        }
    }

    /**
     * Return JSON error response
     */
    private function jsonError($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], 400);
    }

    public function history()
    {
        $plays = Play::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('lottery.history', compact('plays'));
    }

    public function twoD()
    {
        $timeSettings = Setting::first()->value ?? [];
        $currentTime = now();
        $nextDrawTime = null;
        $isMorningSession = false;
        $isEveningSession = false;
        $isClosedSession = true;

        if (isset($timeSettings['2d_morning_close_time'])) {
            $morningCloseTime = Carbon::createFromFormat('H:i', $timeSettings['2d_morning_close_time']);
            if ($currentTime->format('H:i') < $timeSettings['2d_morning_close_time']) {
                $nextDrawTime = $morningCloseTime;
                $isMorningSession = true;
                $isClosedSession = false;
            }
        }

        if (isset($timeSettings['2d_evening_close_time'])) {
            $eveningCloseTime = Carbon::createFromFormat('H:i', $timeSettings['2d_evening_close_time']);
            if ($currentTime->format('H:i') < $timeSettings['2d_evening_close_time'] && !$isMorningSession) {
                $nextDrawTime = $eveningCloseTime;
                $isEveningSession = true;
                $isClosedSession = false;
            }
        }

        return view('lottery.2d', compact(
            'timeSettings',
            'nextDrawTime',
            'isMorningSession',
            'isEveningSession',
            'isClosedSession'
        ));
    }

    public function threeD()
    {
        $timeSettings = Setting::first()->value ?? [];
        $currentTime = now();
        $nextDrawTime = null;
        $isClosedSession = true;

        if (isset($timeSettings['3d_close_time'])) {
            $closeTime = Carbon::createFromFormat('H:i', $timeSettings['3d_close_time']);
            if ($currentTime->format('H:i') < $timeSettings['3d_close_time']) {
                $nextDrawTime = $closeTime;
                $isClosedSession = false;
            }
        }

        // Get latest result
        $latest_result = Result::where('type', '3d')
            ->latest('drawn_at')
            ->first();

        if (!$latest_result) {
            $latest_result = (object)[
                'number' => '---',
                'drawn_at' => now()->startOfMonth()
            ];
        }

        // Get today's plays for the user
        $plays = Play::where('user_id', Auth::id())
            ->where('type', '3d')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('lottery.3d', compact(
            'timeSettings',
            'nextDrawTime',
            'isClosedSession',
            'latest_result',
            'plays'
        ));
    }

    public function thai()
    {
        $latest_result = Result::where('type', 'thai')
                            ->latest('drawn_at')
                            ->first();

        if (!$latest_result) {
            $latest_result = (object)[
                'metadata' => [
                    'first_prize' => '000000',
                    'last_two' => '00',
                    'first_three' => '000',
                    'last_three' => '000'
                ],
                'drawn_at' => Carbon::now()->startOfMonth()
            ];
        }
        
        $plays = Play::where('user_id', Auth::id())
                    ->where('type', 'thai')
                    ->whereDate('created_at', Carbon::today())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view("lottery.thai", compact('plays', 'latest_result'));
    }

    public function laos()
    {
        $latest_result = Result::where('type', 'laos')
                            ->latest('drawn_at')
                            ->first();

        if (!$latest_result) {
            $latest_result = (object)[
                'number' => '00000',
                'drawn_at' => Carbon::now()->startOfMonth()
            ];
        }
        
        $plays = Play::where('user_id', Auth::id())
                    ->where('type', 'laos')
                    ->whereDate('created_at', Carbon::today())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view("lottery.laos", compact('plays', 'latest_result'));
    }

    public function results()
    {
        $results = [
            '2d' => Result::where('type', '2d')->latest('drawn_at')->first(),
            '3d' => Result::where('type', '3d')->latest('drawn_at')->first(),
            'thai' => Result::where('type', 'thai')->latest('drawn_at')->first(),
            'laos' => Result::where('type', 'laos')->latest('drawn_at')->first(),
        ];

        return view('lottery.results', compact('results'));
    }
}
