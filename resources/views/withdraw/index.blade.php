@extends('layouts.lottery-layout')

@section('title', 'ငွေထုတ်ရန်')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">ငွေထုတ်ရန်</h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <p class="font-bold mb-2">လက်ကျန်ငွေ: {{ number_format(auth()->user()->balance) }} ကျပ်</p>
            <ul class="text-sm text-gray-600 list-disc list-inside">
                <li>အနည်းဆုံး ၅,၀၀၀ ကျပ် ထုတ်ယူနိုင်ပါသည်။</li>
                <li>အများဆုံး ၁,၀၀၀,၀၀၀ ကျပ် ထုတ်ယူနိုင်ပါသည်။</li>
                <li>ငွေထုတ်ယူမှုများကို မိနစ် ၃၀ အတွင်း ဆောင်ရွက်ပေးပါမည်။</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('withdraw.store') }}">
            @csrf

            <!-- Amount Input -->
            <div class="mb-6">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">
                    ငွေပမာဏ (အနည်းဆုံး ၅,၀၀၀ ကျပ်)
                </label>
                <input type="number" 
                       name="amount" 
                       id="amount" 
                       min="5000"
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
                    ငွေလက်ခံမည့်နည်းလမ်း
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50 @error('payment_method') border-red-500 @enderror">
                        <input type="radio" name="payment_method" value="kbz" class="absolute opacity-0">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/kbzpay.png') }}" alt="KBZ Pay" class="h-12">
                        </div>
                        <p class="text-center mt-2">KBZ Pay</p>
                    </label>

                    <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="wave" class="absolute opacity-0">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/wavepay.png') }}" alt="Wave Pay" class="h-12">
                        </div>
                        <p class="text-center mt-2">Wave Pay</p>
                    </label>

                    <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="cbpay" class="absolute opacity-0">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/cbpay.png') }}" alt="CB Pay" class="h-12">
                        </div>
                        <p class="text-center mt-2">CB Pay</p>
                    </label>
                </div>
                @error('payment_method')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Account Number -->
            <div class="mb-6">
                <label for="account_number" class="block text-gray-700 text-sm font-bold mb-2">
                    အကောင့်နံပါတ်
                </label>
                <input type="text" 
                       name="account_number" 
                       id="account_number" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('account_number') border-red-500 @enderror"
                       value="{{ old('account_number') }}"
                       required>
                @error('account_number')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Account Name -->
            <div class="mb-6">
                <label for="account_name" class="block text-gray-700 text-sm font-bold mb-2">
                    အကောင့်အမည်
                </label>
                <input type="text" 
                       name="account_name" 
                       id="account_name" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('account_name') border-red-500 @enderror"
                       value="{{ old('account_name') }}"
                       required>
                @error('account_name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    ငွေထုတ်မည်
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Withdrawals -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">မကြာသေးမီက ငွေထုတ်ယူမှုများ</h2>
        @if($withdrawals->isEmpty())
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
                        @foreach($withdrawals as $withdrawal)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $withdrawal->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($withdrawal->amount) }} ကျပ်
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ strtoupper($withdrawal->payment_method) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $withdrawal->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($withdrawal->status === 'failed' ? 'bg-red-100 text-red-800' : 
                                            'bg-yellow-100 text-yellow-800') }}">
                                        {{ $withdrawal->status === 'completed' ? 'အောင်မြင်' : 
                                           ($withdrawal->status === 'failed' ? 'မအောင်မြင်' : 'စောင့်ဆိုင်းဆဲ') }}
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
    document.querySelectorAll('input[name="payment_method"]').forEach(input => {
        input.addEventListener('change', function() {
            // Remove selected class from all labels
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.closest('label').classList.remove('border-blue-500', 'bg-blue-50');
            });
            
            // Add selected class to checked label
            if (this.checked) {
                this.closest('label').classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    });
</script>
@endpush
@endsection
