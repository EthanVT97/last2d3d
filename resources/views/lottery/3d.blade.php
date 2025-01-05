@extends('layouts.app')

@section('title', 'မြန်မာ 3D လော့ထရီ')

@push('styles')
<style>
    .number-input {
        font-size: 2rem;
        letter-spacing: 0.5rem;
        text-align: center;
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
</style>
@endpush

@section('content')
<div class="mt-16 container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Game Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">3D လော့ထရီ</h1>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        {{ Carbon\Carbon::parse('2025-01-05T07:42:25+06:30')->format('h:i A') }}
                    </div>
                </div>

                <!-- Latest Result -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="text-center">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">နောက်ဆုံးရလဒ်</h2>
                        <p class="text-5xl font-bold text-blue-600 mb-2">{{ $latest_result->number }}</p>
                        <p class="text-gray-600">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ $latest_result->drawn_at->format('Y-m-d H:i A') }}
                        </p>
                    </div>
                </div>

                @if((!isset($timeSettings['3d_close_time'])))
                    <div class="text-center py-8">
                        <div class="text-6xl text-gray-300 mb-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="text-xl font-semibold text-gray-800 mb-2">ထီပိတ်ချိန် မသတ်မှတ်ရသေးပါ</div>
                        <p class="text-gray-600">Admin မှ ထီပိတ်ချိန်သတ်မှတ်ပြီးမှ ထီထိုးနိုင်ပါမည်</p>
                    </div>
                @elseif(!$isClosedSession)
                    <!-- Play Form -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">
                            <i class="fas fa-ticket-alt mr-2"></i>3D ထိုးရန်
                        </h2>
                        <form action="{{ route('lottery.store', ['type' => '3d']) }}" method="POST" id="lotteryForm">
                            @csrf
                            <input type="hidden" name="type" value="3d">
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    နံပါတ် (၀၀၀-၉၉၉)
                                </label>
                                <input type="text" 
                                    name="numbers" 
                                    id="numbers"
                                    class="number-input shadow-sm block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('numbers') border-red-500 @enderror"
                                    pattern="[0-9]{3}"
                                    maxlength="3"
                                    placeholder="000"
                                    value="{{ old('numbers') }}"
                                    required>
                                @error('numbers')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </form>
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

                <!-- Today's Plays -->
                @if($plays->isNotEmpty())
                    <div class="bg-white rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">
                            <i class="fas fa-history mr-2"></i>ယနေ့ထိုးထားသော နံပါတ်များ
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">အချိန်</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">နံပါတ်</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ထိုးကြေး</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">အခြေအနေ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($plays as $play)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $play->created_at->format('H:i:s') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                                {{ $play->numbers }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($play->amount) }} ကျပ်
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $play->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($play->status === 'won' ? 'bg-green-100 text-green-800' : 
                                                        'bg-red-100 text-red-800') }}">
                                                    {{ $play->status === 'pending' ? 'စောင့်ဆိုင်းဆဲ' : 
                                                       ($play->status === 'won' ? 'ထီပေါက်' : 'မပေါက်') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Bet Form -->
        <div class="lg:col-span-1">
            @if(!$isClosedSession && isset($timeSettings['3d_close_time']))
                <div class="bg-white rounded-lg shadow-lg p-6 sticky-bet-form">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-receipt mr-2"></i>လက်မှတ်
                    </h2>
                    
                    <form id="betForm" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ရွေးချယ်ထားသောဂဏန်းများ</label>
                            <div id="selectedNumber" class="p-4 border rounded-lg min-h-[100px] bg-gray-50 text-center text-2xl font-bold text-blue-600">
                                ---
                            </div>
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
        const form = document.getElementById('betForm');
        const numbersInput = document.getElementById('numbers');
        const selectedNumberDiv = document.getElementById('selectedNumber');
        const amountInput = document.getElementById('amount');
        const totalAmountSpan = document.getElementById('totalAmount');
        const quickAmountBtns = document.querySelectorAll('.quick-amount');
        const clearBtn = document.getElementById('clearBtn');
        
        function updateSelectedNumber() {
            const number = numbersInput.value;
            selectedNumberDiv.textContent = number || '---';
        }
        
        numbersInput?.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);
            updateSelectedNumber();
        });
        
        quickAmountBtns?.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = parseInt(this.dataset.amount);
                amountInput.value = amount;
                quickAmountBtns.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
        
        clearBtn?.addEventListener('click', function() {
            numbersInput.value = '';
            amountInput.value = '';
            updateSelectedNumber();
            quickAmountBtns.forEach(btn => btn.classList.remove('selected'));
        });
        
        form?.addEventListener('submit', function(e) {
            e.preventDefault();
            const number = numbersInput.value;
            const amount = parseInt(amountInput.value);
            
            if (!/^\d{3}$/.test(number)) {
                alert('နံပါတ် ၃ လုံး ထည့်ပါ။');
                return;
            }
            
            if (!amount || amount < 100) {
                alert('ထိုးကြေးအနည်းဆုံး ၁၀၀ ကျပ် ထည့်ပါ။');
                return;
            }
            
            // Submit the form
            document.getElementById('lotteryForm').submit();
        });
    });
</script>
@endpush
