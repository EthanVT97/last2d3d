@extends('layouts.app')

@section('title', '2D လော့ထရီ')

@push('styles')
<style>
    .number-btn.selected {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    .number-btn:not(.selected):hover {
        background-color: #eff6ff;
        border-color: #3b82f6;
    }
    .quick-amount {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        border-width: 1px;
        font-size: 0.875rem;
        font-weight: 500;
        transition-property: color, background-color, border-color;
        transition-duration: 200ms;
    }
    .quick-amount.selected {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    .quick-amount:not(.selected):hover {
        background-color: #eff6ff;
        border-color: #3b82f6;
    }
    .sticky-bet-form {
        position: sticky;
        top: 80px;
        z-index: 40;
    }
</style>
@endpush

@section('content')
<div class="mt-16 container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Game Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">2D လော့ထရီ</h1>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        {{ Carbon\Carbon::parse('2025-01-05T07:34:27+06:30')->format('h:i A') }}
                    </div>
                </div>

                <!-- Session Status -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">နောက်ထီပိတ်ချိန်</h2>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ $nextDrawTime ? $nextDrawTime->format('h:i A') : 'Not set' }}
                            </p>
                        </div>
                        <div class="text-right">
                            @if(!isset($timeSettings['2d_morning_close_time']) || !isset($timeSettings['2d_evening_close_time']))
                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-2"></i>
                                    ထီပိတ်ချိန် စောင့်ဆိုင်းနေသည်
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full 
                                    {{ $isMorningSession ? 'bg-green-100 text-green-800' : 
                                       ($isEveningSession ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                    <i class="fas {{ $isClosedSession ? 'fa-lock' : 'fa-clock' }} mr-2"></i>
                                    {{ $isMorningSession ? 'မနက်ပိုင်း' : ($isEveningSession ? 'ညနေပိုင်း' : 'ပိတ်ထားသည်') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if((!isset($timeSettings['2d_morning_close_time']) || !isset($timeSettings['2d_evening_close_time'])))
                    <div class="text-center py-8">
                        <div class="text-6xl text-gray-300 mb-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="text-xl font-semibold text-gray-800 mb-2">ထီပိတ်ချိန် မသတ်မှတ်ရသေးပါ</div>
                        <p class="text-gray-600">Admin မှ ထီပိတ်ချိန်သတ်မှတ်ပြီးမှ ထီထိုးနိုင်ပါမည်</p>
                    </div>
                @elseif(!$isClosedSession)
                    <!-- Number Selection Grid -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">
                            <i class="fas fa-th mr-2"></i>ထီဂဏန်းရွေးရန်
                        </h2>
                        <div class="grid grid-cols-5 sm:grid-cols-10 gap-2">
                            @for($i = 0; $i <= 99; $i++)
                                <button 
                                    class="number-btn p-4 text-center border rounded-lg transition-colors duration-200"
                                    data-number="{{ sprintf('%02d', $i) }}"
                                >
                                    {{ sprintf('%02d', $i) }}
                                </button>
                            @endfor
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-6xl text-gray-300 mb-4">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="text-xl font-semibold text-gray-800 mb-2">ထီပိတ်ထားပါသည်</div>
                        <p class="text-gray-600">
                            နောက်ထီဖွင့်ချိန်: {{ $nextDrawTime ? $nextDrawTime->format('h:i A') : 'Not set' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Bet Form -->
        <div class="lg:col-span-1">
            @if(!$isClosedSession && isset($timeSettings['2d_morning_close_time']) && isset($timeSettings['2d_evening_close_time']))
                <div class="bg-white rounded-lg shadow-lg p-6 sticky-bet-form">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-receipt mr-2"></i>လက်မှတ်
                    </h2>
                    
                    <form id="betForm" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ရွေးချယ်ထားသောဂဏန်းများ</label>
                            <div id="selectedNumbers" class="p-4 border rounded-lg min-h-[100px] bg-gray-50"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-money-bill-wave mr-1"></i>ထိုးကြေး
                            </label>
                            <!-- Quick Amount Buttons -->
                            <div class="grid grid-cols-3 gap-2 mb-2">
                                @foreach([100, 500, 1000, 5000, 10000, 50000] as $amount)
                                    <button type="button" 
                                        class="quick-amount"
                                        data-amount="{{ $amount }}"
                                    >
                                        {{ number_format($amount) }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="number" id="amount" name="amount" 
                                min="{{ $setting->value['min_amount'] ?? 100 }}" 
                                max="{{ $setting->value['max_amount'] ?? 50000 }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="ထိုးကြေးထည့်ရန်">
                            <p class="mt-1 text-sm text-gray-500">
                                အနည်းဆုံး: {{ number_format($setting->value['min_amount'] ?? 100) }} ကျပ်
                                / အများဆုံး: {{ number_format($setting->value['max_amount'] ?? 50000) }} ကျပ်
                            </p>
                        </div>

                        <div class="flex justify-between space-x-4">
                            <button type="button" id="clearBtn" 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                                <i class="fas fa-trash-alt mr-2"></i>ရှင်းရန်
                            </button>
                            <button type="submit" 
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                                <i class="fas fa-paper-plane mr-2"></i>ထိုးမည်
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectedNumbers = new Set();
        const numberBtns = document.querySelectorAll('.number-btn');
        const selectedNumbersDiv = document.getElementById('selectedNumbers');
        const clearBtn = document.getElementById('clearBtn');
        const betForm = document.getElementById('betForm');
        const amountInput = document.getElementById('amount');
        const quickAmountBtns = document.querySelectorAll('.quick-amount');

        function updateSelectedNumbers() {
            selectedNumbersDiv.innerHTML = Array.from(selectedNumbers)
                .map(num => `<span class="inline-block px-3 py-1 m-1 bg-blue-100 text-blue-800 rounded-full">${num}</span>`)
                .join('') || '<div class="text-gray-500 text-center">ဂဏန်းများရွေးချယ်ပါ</div>';
        }

        numberBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const number = btn.dataset.number;
                if (selectedNumbers.has(number)) {
                    selectedNumbers.delete(number);
                    btn.classList.remove('selected');
                } else {
                    selectedNumbers.add(number);
                    btn.classList.add('selected');
                }
                updateSelectedNumbers();
            });
        });

        quickAmountBtns?.forEach(btn => {
            btn.addEventListener('click', () => {
                const amount = btn.dataset.amount;
                amountInput.value = amount;
                quickAmountBtns.forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
            });
        });

        clearBtn?.addEventListener('click', () => {
            selectedNumbers.clear();
            numberBtns.forEach(btn => {
                btn.classList.remove('selected');
            });
            updateSelectedNumbers();
            amountInput.value = '';
            quickAmountBtns.forEach(btn => btn.classList.remove('selected'));
        });

        betForm?.addEventListener('submit', (e) => {
            e.preventDefault();
            if (selectedNumbers.size === 0) {
                alert('ထီဂဏန်းရွေးချယ်ပါ');
                return;
            }
            const amount = amountInput.value;
            if (!amount) {
                alert('ထိုးကြေးထည့်ပါ');
                return;
            }
            const data = {
                numbers: Array.from(selectedNumbers),
                amount: parseInt(amount)
            };
            // Add your API call here to submit the bet
            console.log('Submitting bet:', data);
        });

        // Initialize empty state
        updateSelectedNumbers();
    });
</script>
@endpush
