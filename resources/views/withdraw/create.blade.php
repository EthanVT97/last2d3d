@extends('layouts.lottery-layout')

@section('title', 'ငွေထုတ်ရန်')

@section('content')
<div class="max-w-4xl mx-auto space-y-8" x-data="withdrawForm()">
    <!-- Balance Card -->
    <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-2xl shadow-lg overflow-hidden">
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-white/80 font-medium">လက်ကျန်ငွေ</h2>
                    <p class="mt-2 text-4xl font-bold text-white">{{ number_format(auth()->user()->balance) }} ကျပ်</p>
                </div>
                <div class="p-4 bg-white/10 backdrop-blur-sm rounded-full">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdraw Form -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-8">
            <h3 class="text-lg font-medium text-gray-900">ငွေထုတ်ယူရန်</h3>
            <form action="{{ route('withdraw.store') }}" method="POST" class="mt-6 space-y-6" @submit.prevent="submitForm">
                @csrf

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">ငွေပမာဏ</label>
                    <div class="mt-1">
                        <input type="number" 
                               name="amount" 
                               id="amount"
                               x-model="amount"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                               placeholder="5000">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">ငွေလက်ခံမည့် နည်းလမ်း</label>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <template x-for="method in paymentMethods" :key="method.code">
                            <div class="relative">
                                <input type="radio" 
                                       :name="'payment_method'" 
                                       :id="'method_' + method.code" 
                                       :value="method.code" 
                                       class="peer hidden" 
                                       x-model="selectedMethod">
                                <label :for="'method_' + method.code" 
                                       class="block p-4 bg-white border rounded-xl cursor-pointer transition-all
                                              peer-checked:border-primary-500 peer-checked:ring-2 peer-checked:ring-primary-500
                                              hover:border-primary-200">
                                    <div class="text-center">
                                        <img :src="'/images/payment/' + method.icon" 
                                             :alt="method.name" 
                                             class="h-12 mx-auto mb-4">
                                        <h3 class="font-medium text-gray-900" x-text="method.name"></h3>
                                    </div>
                                </label>
                            </div>
                        </template>
                    </div>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Details -->
                <div x-show="selectedMethod" class="space-y-6">
                    <div>
                        <label for="account_number" class="block text-sm font-medium text-gray-700">အကောင့်နံပါတ်</label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="account_number" 
                                   id="account_number"
                                   x-model="accountNumber"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="09xxxxxxxxx">
                        </div>
                        @error('account_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="account_name" class="block text-sm font-medium text-gray-700">အကောင့်အမည်</label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="account_name" 
                                   id="account_name"
                                   x-model="accountName"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="အကောင့်ပိုင်ရှင်အမည်">
                        </div>
                        @error('account_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                            :disabled="isSubmitting || !isValid"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            ငွေထုတ်မည်
                        </span>
                        <span x-show="isSubmitting">
                            <svg class="w-5 h-5 mr-2 -ml-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            စောင့်ဆိုင်းပါ...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Withdrawals -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900">မကြာသေးမီက ငွေထုတ်ယူမှုများ</h3>
            <div class="mt-6 space-y-4">
                @forelse($withdrawals as $withdrawal)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ number_format($withdrawal->amount) }} ကျပ်</p>
                        <p class="mt-1 text-sm text-gray-500">{{ $withdrawal->created_at->format('d-M-Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ $withdrawal->payment_method }}</p>
                        <p class="mt-1">{!! $withdrawal->status_badge !!}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="text-gray-400">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium">ငွေထုတ်ယူမှု မှတ်တမ်းမရှိသေးပါ</h3>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('withdrawForm', () => ({
        amount: '',
        selectedMethod: null,
        accountNumber: '',
        accountName: '',
        isSubmitting: false,
        paymentMethods: [
            {
                code: 'kbz',
                name: 'KBZ Pay',
                icon: 'kbz.svg'
            },
            {
                code: 'wave',
                name: 'Wave Pay',
                icon: 'wave.svg'
            },
            {
                code: 'cbpay',
                name: 'CB Pay',
                icon: 'cbpay.svg'
            }
        ],
        
        init() {
            this.$watch('selectedMethod', value => {
                if (!value) {
                    this.accountNumber = '';
                    this.accountName = '';
                }
            });
        },

        get isValid() {
            return this.amount >= 5000 && 
                   this.selectedMethod && 
                   this.accountNumber.length >= 8 &&
                   this.accountName.length > 0;
        },

        submitForm() {
            if (!this.isValid) return;
            
            this.isSubmitting = true;
            this.$el.submit();
        }
    }))
})
</script>
@endpush
