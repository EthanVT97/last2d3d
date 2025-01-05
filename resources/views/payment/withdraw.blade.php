@extends('layouts.lottery-layout')

@section('title', 'ငွေထုတ်ရန်')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Balance Card -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900">လက်ကျန်ငွေ</h2>
                <p class="mt-1 text-3xl font-bold text-primary-600">{{ number_format(auth()->user()->balance) }} ကျပ်</p>
            </div>
            <div class="p-3 bg-primary-100 rounded-full">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        @if(auth()->user()->pending_withdrawal > 0)
            <div class="mt-4 p-4 bg-yellow-50 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">ငွေထုတ်ယူမှု စောင့်ဆိုင်းနေဆဲ</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>{{ number_format(auth()->user()->pending_withdrawal) }} ကျပ် ငွေထုတ်ယူမှု စောင့်ဆိုင်းနေပါသည်။</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Withdrawal Form -->
    <div class="bg-white rounded-lg shadow-sm"
         x-data="{
            step: 1,
            amount: '',
            selectedMethod: null,
            accountName: '',
            accountNumber: '',
            isSubmitting: false,
            
            nextStep() {
                if (this.step === 1 && !this.amount) {
                    alert('ငွေပမာဏ ထည့်သွင်းပါ။');
                    return;
                }
                if (this.step === 2 && !this.selectedMethod) {
                    alert('ငွေထုတ်ယူမည့် နည်းလမ်း ရွေးချယ်ပါ။');
                    return;
                }
                if (this.step === 2 && (!this.accountName || !this.accountNumber)) {
                    alert('အကောင့် အချက်အလက်များ ဖြည့်သွင်းပါ။');
                    return;
                }
                this.step++;
            },
            prevStep() {
                this.step--;
            }
         }">
        
        <!-- Progress Steps -->
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <span class="w-8 h-8 flex items-center justify-center rounded-full" 
                          :class="step >= 1 ? 'bg-primary-600 text-white' : 'bg-gray-200'">1</span>
                    <div class="ml-4">
                        <p class="text-sm font-medium" :class="step >= 1 ? 'text-gray-900' : 'text-gray-500'">ငွေပမာဏ</p>
                    </div>
                </div>
                <div class="hidden sm:block w-16 h-0.5" :class="step >= 2 ? 'bg-primary-600' : 'bg-gray-200'"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 flex items-center justify-center rounded-full"
                          :class="step >= 2 ? 'bg-primary-600 text-white' : 'bg-gray-200'">2</span>
                    <div class="ml-4">
                        <p class="text-sm font-medium" :class="step >= 2 ? 'text-gray-900' : 'text-gray-500'">အကောင့်အချက်အလက်</p>
                    </div>
                </div>
                <div class="hidden sm:block w-16 h-0.5" :class="step >= 3 ? 'bg-primary-600' : 'bg-gray-200'"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 flex items-center justify-center rounded-full"
                          :class="step >= 3 ? 'bg-primary-600 text-white' : 'bg-gray-200'">3</span>
                    <div class="ml-4">
                        <p class="text-sm font-medium" :class="step >= 3 ? 'text-gray-900' : 'text-gray-500'">အတည်ပြုခြင်း</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('withdraw.store') }}" method="POST"
                  @submit.prevent="isSubmitting = true; $el.submit()">
                @csrf
                
                <!-- Step 1: Amount -->
                <div x-show="step === 1">
                    <div class="space-y-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">ငွေပမာဏ</label>
                            <div class="mt-1">
                                <input type="number" name="amount" id="amount" required
                                       class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       x-model="amount"
                                       min="5000" step="1000"
                                       :max="{{ auth()->user()->balance }}"
                                       placeholder="အနည်းဆုံး 5,000 ကျပ်">
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4">
                            @foreach([10000, 50000, 100000, 500000] as $quickAmount)
                                <button type="button"
                                        class="px-4 py-2 border rounded-md text-sm font-medium hover:bg-gray-50 focus:outline-none"
                                        :class="amount == {{ $quickAmount }} ? 'border-primary-500 text-primary-700' : 'border-gray-300 text-gray-700'"
                                        @click="amount = {{ $quickAmount }}">
                                    {{ number_format($quickAmount) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Step 2: Account Details -->
                <div x-show="step === 2">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            @foreach($paymentMethods as $key => $method)
                                <div class="relative rounded-lg border p-4 cursor-pointer hover:border-primary-500"
                                     :class="selectedMethod === '{{ $key }}' ? 'border-primary-500 ring-2 ring-primary-500' : 'border-gray-300'"
                                     @click="selectedMethod = '{{ $key }}'">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="{{ asset('images/payment/' . $method['icon']) }}" 
                                                 alt="{{ $method['name'] }}"
                                                 class="h-8 w-auto">
                                            <span class="ml-3 font-medium text-gray-900">{{ $method['name'] }}</span>
                                        </div>
                                        <div class="flex-shrink-0" x-show="selectedMethod === '{{ $key }}'">
                                            <svg class="h-5 w-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <input type="hidden" name="payment_method" x-model="selectedMethod">

                        <div class="space-y-4" x-show="selectedMethod">
                            <div>
                                <label for="account_name" class="block text-sm font-medium text-gray-700">အကောင့်အမည်</label>
                                <div class="mt-1">
                                    <input type="text" name="account_name" id="account_name" required
                                           class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           x-model="accountName">
                                </div>
                            </div>

                            <div>
                                <label for="account_number" class="block text-sm font-medium text-gray-700">အကောင့်နံပါတ်</label>
                                <div class="mt-1">
                                    <input type="text" name="account_number" id="account_number" required
                                           class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           x-model="accountNumber">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Confirmation -->
                <div x-show="step === 3">
                    <div class="rounded-lg bg-gray-50 p-6">
                        <h3 class="text-lg font-medium text-gray-900">အတည်ပြုပါ</h3>
                        <dl class="mt-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">ငွေပမာဏ</dt>
                                <dd class="text-sm font-medium text-gray-900" x-text="`${amount} ကျပ်`"></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">ငွေထုတ်ယူမှု</dt>
                                <dd class="text-sm font-medium text-gray-900" x-text="selectedMethod"></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">အကောင့်အမည်</dt>
                                <dd class="text-sm font-medium text-gray-900" x-text="accountName"></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">အကောင့်နံပါတ်</dt>
                                <dd class="text-sm font-medium text-gray-900" x-text="accountNumber"></dd>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <dt class="text-base font-medium text-gray-900">စုစုပေါင်း</dt>
                                    <dd class="text-base font-medium text-gray-900" x-text="`${amount} ကျပ်`"></dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="mt-8 flex justify-between">
                    <button type="button"
                            x-show="step > 1"
                            @click="prevStep"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        နောက်သို့
                    </button>
                    <button type="button"
                            x-show="step < 3"
                            @click="nextStep"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        ရှေ့သို့
                    </button>
                    <button type="submit"
                            x-show="step === 3"
                            :disabled="isSubmitting"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting">အတည်ပြုမည်</span>
                        <span x-show="isSubmitting">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            စောင့်ဆိုင်းပါ...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">ငွေထုတ်ယူမှု မှတ်တမ်းများ</h2>
        </div>
        <div class="border-t border-gray-200 divide-y divide-gray-200">
            @forelse($transactions as $transaction)
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($transaction->status === 'completed')
                                    <div class="p-2 bg-green-100 rounded-full">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                @elseif($transaction->status === 'pending')
                                    <div class="p-2 bg-yellow-100 rounded-full">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="p-2 bg-red-100 rounded-full">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ number_format($transaction->amount) }} ကျပ်
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $transaction->created_at->format('Y-m-d h:i A') }}
                                </p>
                            </div>
                        </div>
                        <div class="ml-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-red-100 text-red-800') }}">
                                {{ $transaction->status === 'completed' ? 'အတည်ပြုပြီး' : 
                                   ($transaction->status === 'pending' ? 'စောင့်ဆိုင်းဆဲ' : 'ငြင်းပယ်ခဲ့သည်') }}
                            </span>
                        </div>
                    </div>
                    @if($transaction->status === 'rejected' && $transaction->rejection_reason)
                        <div class="mt-2 ml-11">
                            <p class="text-sm text-red-600">
                                {{ $transaction->rejection_reason }}
                            </p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    ငွေထုတ်ယူမှု မှတ်တမ်း မရှိသေးပါ
                </div>
            @endforelse
        </div>
        @if($transactions->hasPages())
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
