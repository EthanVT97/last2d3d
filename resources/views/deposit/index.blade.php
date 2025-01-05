@extends('layouts.app')

@section('title', 'ငွေသွင်းရန်')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">ငွေသွင်းရန်</h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('deposit.store') }}">
            @csrf

            <!-- Amount Input -->
            <div class="mb-6">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">
                    ငွေပမာဏ (အနည်းဆုံး ၁,၀၀၀ ကျပ်)
                </label>
                <input type="number" 
                       name="amount" 
                       id="amount" 
                       min="1000"
                       max="1000000"
                       step="100"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror"
                       value="{{ old('amount') }}"
                       required>
                @error('amount')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Method Selection -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    ငွေပေးချေမှုနည်းလမ်း
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50 @error('payment_method') border-red-500 @enderror payment-method-label">
                        <input type="radio" name="payment_method" value="kbz" class="absolute opacity-0" {{ old('payment_method') == 'kbz' ? 'checked' : '' }}>
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/kbzpay.png') }}" alt="KBZ Pay" class="h-12">
                        </div>
                        <p class="text-center mt-2">KBZ Pay</p>
                    </label>

                    <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50 payment-method-label">
                        <input type="radio" name="payment_method" value="wave" class="absolute opacity-0" {{ old('payment_method') == 'wave' ? 'checked' : '' }}>
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/wavepay.png') }}" alt="Wave Pay" class="h-12">
                        </div>
                        <p class="text-center mt-2">Wave Pay</p>
                    </label>

                    <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50 payment-method-label">
                        <input type="radio" name="payment_method" value="cbpay" class="absolute opacity-0" {{ old('payment_method') == 'cbpay' ? 'checked' : '' }}>
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/cbpay.png') }}" alt="CB Pay" class="h-12">
                        </div>
                        <p class="text-center mt-2">CB Pay</p>
                    </label>
                </div>
                @error('payment_method')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Instructions -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-bold mb-2">ငွေလွှဲရန် အကောင့်များ</h3>
                <div class="space-y-2">
                    <p><strong>KBZ Pay:</strong> 09-XXXXXXXXX (အမည် - ဦးကျော်ကျော်)</p>
                    <p><strong>Wave Pay:</strong> 09-XXXXXXXXX (အမည် - ဦးကျော်ကျော်)</p>
                    <p><strong>CB Pay:</strong> 09-XXXXXXXXX (အမည် - ဦးကျော်ကျော်)</p>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-600">
                        * ငွေလွှဲပြီးပါက သင်ရရှိသော Transaction ID ကို အောက်တွင် ဖြည့်သွင်းပါ။<br>
                        * ငွေလွှဲပြီး ၅ မိနစ်အတွင်း သင့်အကောင့်သို့ ထည့်သွင်းပေးပါမည်။
                    </p>
                </div>
            </div>

            <!-- Transaction ID Input -->
            <div class="mb-6">
                <label for="transaction_id" class="block text-gray-700 text-sm font-bold mb-2">
                    Transaction ID
                </label>
                <input type="text" 
                       name="transaction_id" 
                       id="transaction_id" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('transaction_id') border-red-500 @enderror"
                       value="{{ old('transaction_id') }}"
                       required>
                @error('transaction_id')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    ငွေသွင်းမည်
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Deposits -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">မကြာသေးမီက ငွေသွင်းမှုများ</h2>
        @if($deposits->isEmpty())
            <p class="text-gray-600">မရှိသေးပါ</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ရက်စွဲ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ပမာဏ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">နည်းလမ်း</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">အခြေအနေ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($deposits as $deposit)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $deposit->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($deposit->amount) }} ကျပ်
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ strtoupper($deposit->payment_method) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $deposit->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($deposit->status === 'failed' ? 'bg-red-100 text-red-800' : 
                                            'bg-yellow-100 text-yellow-800') }}">
                                        {{ $deposit->status === 'completed' ? 'အောင်မြင်' : 
                                           ($deposit->status === 'failed' ? 'မအောင်မြင်' : 'စောင့်ဆိုင်းဆဲ') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentLabels = document.querySelectorAll('.payment-method-label');
        
        paymentLabels.forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            
            // Set initial state
            if (radio.checked) {
                label.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
            }
            
            label.addEventListener('click', () => {
                // Remove selection from all labels
                paymentLabels.forEach(l => {
                    l.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500');
                });
                
                // Add selection to clicked label
                label.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
                radio.checked = true;
            });
        });
    });
</script>
@endpush
@endsection
