@extends('layouts.app')

@section('title', 'လာအို လော့ထရီ')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8">လာအို လော့ထရီ</h1>

    <!-- Latest Result -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">နောက်ဆုံးရလဒ်</h2>
        <div class="text-center">
            <p class="text-4xl font-bold text-gray-700">{{ $latest_result->number ?? '00000' }}</p>
            <p class="text-gray-600">{{ $latest_result->drawn_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <!-- Play Form -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">လာအို လော့ထရီ ထိုးရန်</h2>
            <p class="text-gray-600">လက်ကျန်ငွေ: {{ number_format(auth()->user()->balance) }} ကျပ်</p>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('lottery.store', ['type' => 'laos']) }}" method="POST" id="lotteryForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="numbers">
                    နံပါတ် (၀၀၀၀၀)
                </label>
                <input type="text" 
                       name="numbers" 
                       id="numbers"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('numbers') border-red-500 @enderror"
                       pattern="[0-9]{5}"
                       maxlength="5"
                       placeholder="00000"
                       value="{{ old('numbers') }}"
                       required>
                @error('numbers')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">
                    ထိုးကြေး (အနည်းဆုံး ၁၀၀ ကျပ်)
                </label>
                <div class="flex gap-2">
                    <input type="number" 
                           name="amount" 
                           id="amount"
                           min="100"
                           step="100"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror"
                           value="{{ old('amount', 100) }}"
                           required>
                    
                    <!-- Quick amount buttons -->
                    <button type="button" class="amount-btn px-3 py-1 border rounded hover:bg-gray-100" data-amount="100">100</button>
                    <button type="button" class="amount-btn px-3 py-1 border rounded hover:bg-gray-100" data-amount="500">500</button>
                    <button type="button" class="amount-btn px-3 py-1 border rounded hover:bg-gray-100" data-amount="1000">1000</button>
                </div>
                @error('amount')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" 
                        class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submitBtn">
                    ထိုးမည်
                </button>
                <p class="text-gray-600">စုစုပေါင်း: <span id="totalAmount">0</span> ကျပ်</p>
            </div>
        </form>
    </div>

    <!-- Today's Plays -->
    @if($plays->isNotEmpty())
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">ယနေ့ထိုးထားသော နံပါတ်များ</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                                           ($play->status === 'won' ? 'အနိုင်ရ' : 'ရှုံး') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Rules -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">စည်းမျဉ်းစည်းကမ်းများ</h2>
        <ul class="list-disc list-inside space-y-2 text-gray-700">
            <li>အပတ်စဉ် တနင်္လာ၊ ဗုဒ္ဓဟူး၊ သောကြာနေ့များတွင် ပေါက်ဂဏန်းထုတ်ပါသည်</li>
            <li>First Prize ပေါက်ပါက ထိုးငွေ၏ ၈၀၀ ဆ ပြန်လည်ရရှိမည်</li>
            <li>Last Two Digits ပေါက်ပါက ထိုးငွေ၏ ၈၀ ဆ ပြန်လည်ရရှိမည်</li>
            <li>ထိုးငွေအနည်းဆုံး ၁၀၀ ကျပ်မှ အများဆုံး ၅၀,၀၀၀ ကျပ်အထိ ထိုးနိုင်ပါသည်</li>
            <li>ပေါက်ဂဏန်းမထွက်မီ ၁ နာရီ အလိုတွင် ထိုးခွင့်ပိတ်မည်</li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        const amountBtns = document.querySelectorAll('.amount-btn');
        const totalAmountSpan = document.getElementById('totalAmount');
        
        // Quick amount buttons
        amountBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const amount = btn.dataset.amount;
                amountInput.value = amount;
                updateTotal();
                
                // Remove active class from all buttons
                amountBtns.forEach(b => b.classList.remove('bg-blue-100'));
                // Add active class to clicked button
                btn.classList.add('bg-blue-100');
            });
        });
        
        // Update total when amount changes
        amountInput.addEventListener('input', updateTotal);
        
        function updateTotal() {
            const amount = parseInt(amountInput.value) || 0;
            totalAmountSpan.textContent = amount.toLocaleString();
        }
        
        // Initialize total
        updateTotal();
    });
</script>
@endpush

@push('styles')
<style>
    .amount-btn.active {
        background-color: rgb(243 244 246);
    }
    
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        appearance: none;
        margin: 0;
    }
    
    input[type="number"] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
</style>
@endpush
