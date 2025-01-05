@extends('layouts.lottery-layout')

@section('title', 'ငွေသွင်းရန်')

@section('content')
<div class="max-w-4xl mx-auto space-y-8" x-data="depositForm">
    <!-- Balance Card -->
    <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-2xl shadow-lg overflow-hidden">
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-white">လက်ကျန်ငွေ</h3>
                    <p class="mt-1 text-2xl font-bold text-white">{{ number_format(auth()->user()->balance) }} Ks</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Deposit Form -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <!-- Progress Steps -->
        <div class="px-6 py-8">
            <div class="relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200"></div>
                <div class="relative flex justify-between">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center"
                            :class="step >= 1 ? 'border-primary-500 bg-primary-50 text-primary-500' : 'border-gray-300 bg-white text-gray-500'">
                            <span class="text-sm font-medium">1</span>
                        </div>
                        <p class="mt-2 text-sm font-medium" :class="step >= 1 ? 'text-primary-500' : 'text-gray-500'">ငွေပမာဏ</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center"
                            :class="step >= 2 ? 'border-primary-500 bg-primary-50 text-primary-500' : 'border-gray-300 bg-white text-gray-500'">
                            <span class="text-sm font-medium">2</span>
                        </div>
                        <p class="mt-2 text-sm font-medium" :class="step >= 2 ? 'text-primary-500' : 'text-gray-500'">ငွေလွှဲနည်း</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center"
                            :class="step >= 3 ? 'border-primary-500 bg-primary-50 text-primary-500' : 'border-gray-300 bg-white text-gray-500'">
                            <span class="text-sm font-medium">3</span>
                        </div>
                        <p class="mt-2 text-sm font-medium" :class="step >= 3 ? 'text-primary-500' : 'text-gray-500'">အတည်ပြုခြင်း</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200">
            <form action="{{ route('deposit.store') }}" method="POST" enctype="multipart/form-data"
                  @submit.prevent="isSubmitting = true; $el.submit()">
                @csrf
                
                <!-- Step 1: Amount -->
                <div x-show="step === 1">
                    <div class="space-y-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">ငွေပမာဏ</label>
                            <div class="mt-1">
                                <input type="number" name="amount" id="amount" x-model="amount"
                                    class="block w-full px-4 py-3 text-gray-900 border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                    placeholder="ငွေပမာဏ ထည့်သွင်းပါ">
                            </div>
                            @error('amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Step 2: Payment Method -->
                <div x-show="step === 2">
                    <div class="space-y-6">
                        <!-- Payment Methods Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($paymentMethods as $code => $method)
                            <div class="relative">
                                <input type="radio" name="payment_method" id="payment_{{ $code }}" value="{{ $code }}"
                                    class="peer sr-only" @click="selectMethod($event.target.value)">
                                <label for="payment_{{ $code }}"
                                    class="block p-4 bg-white border rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-primary-500 peer-checked:ring-1 peer-checked:ring-primary-500">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="{{ asset('images/payment/' . $method['icon']) }}" alt="{{ $method['name'] }}"
                                                class="w-12 h-12 object-contain">
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $method['name'] }}</p>
                                            </div>
                                        </div>
                                        <svg class="hidden w-5 h-5 text-primary-500 peer-checked:block"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </label>
                            </div>
                            @empty
                            <p class="col-span-3 text-sm text-gray-500 text-center py-4">ငွေလွှဲနည်းများ မရရှိနိုင်သေးပါ။</p>
                            @endforelse
                        </div>

                        @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Selected Payment Method Details -->
                        <div x-show="selectedMethod" class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <template x-if="selectedMethod">
                                <div>
                                    <h4 class="font-medium text-gray-900">လွှဲပို့ရန် အချက်အလက်များ</h4>
                                    <dl class="mt-4 space-y-3">
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">အကောင့်အမည်</dt>
                                            <dd class="text-sm font-medium text-gray-900" x-text="paymentMethods[selectedMethod].account_name"></dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">အကောင့်နံပါတ်</dt>
                                            <dd class="text-sm font-medium text-gray-900" x-text="paymentMethods[selectedMethod].account_number"></dd>
                                        </div>
                                    </dl>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Confirmation -->
                <div x-show="step === 3">
                    <div class="space-y-6">
                        <div>
                            <label for="sender_phone" class="block text-sm font-medium text-gray-700">ပို့သူဖုန်းနံပါတ်</label>
                            <div class="mt-1">
                                <input type="text" name="sender_phone" id="sender_phone" x-model="senderPhone"
                                    class="block w-full px-4 py-3 text-gray-900 border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                    placeholder="သင်၏ဖုန်းနံပါတ် ထည့်သွင်းပါ">
                            </div>
                            @error('sender_phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="transaction_id" class="block text-sm font-medium text-gray-700">ငွေလွှဲအမှတ်စဉ်</label>
                            <div class="mt-1">
                                <input type="text" name="transaction_id" id="transaction_id" x-model="transactionId"
                                    class="block w-full px-4 py-3 text-gray-900 border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                    placeholder="ငွေလွှဲအမှတ်စဉ် ထည့်သွင်းပါ">
                            </div>
                            @error('transaction_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="screenshot" class="block text-sm font-medium text-gray-700">ငွေလွှဲပြေစာဓာတ်ပုံ</label>
                            <div class="mt-1">
                                <input type="file" name="screenshot" id="screenshot" accept="image/*"
                                    @change="handleFileUpload($event)"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            </div>
                            @error('screenshot')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Summary -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium text-gray-900">အတည်ပြုရန်</h4>
                            <dl class="mt-4 space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">ငွေပမာဏ</dt>
                                    <dd class="text-sm font-medium text-gray-900" x-text="amount + ' Ks'"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">ငွေလွှဲနည်း</dt>
                                    <dd class="text-sm font-medium text-gray-900" x-text="selectedMethod ? paymentMethods[selectedMethod].name : ''"></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-8 flex justify-between">
                    <button type="button"
                            x-show="step > 1"
                            @click="prevStep"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        နောက်သို့
                    </button>

                    <button type="button"
                            x-show="step < 3"
                            @click="nextStep"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        ရှေ့သို့
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <button type="submit"
                            x-show="step === 3"
                            :disabled="isSubmitting"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <span x-show="!isSubmitting">အတည်ပြုမည်</span>
                        <span x-show="isSubmitting">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            စောင့်ဆိုင်းပေးပါ...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900">နောက်ဆုံးငွေသွင်းမှတ်တမ်းများ</h3>
            <div class="mt-6 flow-root">
                <div class="-my-5 divide-y divide-gray-200">
                    @forelse(auth()->user()->transactions()->where('type', 'deposit')->latest()->take(5)->get() as $transaction)
                    <div class="py-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ number_format($transaction->amount) }} Ks</p>
                                <p class="text-sm text-gray-500">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ $transaction->status === 'completed' ? 'အတည်ပြုပြီး' : 
                                   ($transaction->status === 'pending' ? 'စောင့်ဆိုင်းဆဲ' : 'ငြင်းပယ်ခဲ့သည်') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="py-5 text-sm text-gray-500 text-center">ငွေသွင်းမှတ်တမ်း မရှိသေးပါ။</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('depositForm', () => {
            return {
                step: 1,
                amount: '',
                selectedMethod: null,
                senderPhone: '',
                transactionId: '',
                screenshot: null,
                isSubmitting: false,
                paymentMethods: JSON.parse('{!! addslashes(json_encode($paymentMethods)) !!}'),

                init() {
                    this.step = 1;
                },

                nextStep() {
                    if (this.step === 1 && !this.amount) {
                        alert('ငွေပမာဏ ထည့်သွင်းပါ။');
                        return;
                    }

                    if (this.step === 2 && !this.selectedMethod) {
                        alert('ငွေလွှဲနည်း ရွေးချယ်ပါ။');
                        return;
                    }

                    if (this.step < 3) {
                        this.step++;
                    }
                },

                prevStep() {
                    if (this.step > 1) {
                        this.step--;
                    }
                },

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.screenshot = file;
                    }
                },

                selectMethod(method) {
                    this.selectedMethod = method;
                }
            };
        });
    });
</script>
@endpush
