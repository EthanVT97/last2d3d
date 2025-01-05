@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">ငွေစာရင်း အသေးစိတ်</h2>
                <a href="{{ route('transactions.index') }}" class="text-primary-600 hover:text-primary-900">
                    နောက်သို့
                </a>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                အမျိုးအစား
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @switch($transaction->type)
                                    @case('deposit')
                                        <span class="text-green-600">ငွေသွင်း</span>
                                        @break
                                    @case('withdrawal')
                                        <span class="text-red-600">ငွေထုတ်</span>
                                        @break
                                    @case('win')
                                        <span class="text-green-600">ထီပေါက်</span>
                                        @break
                                    @case('loss')
                                        <span class="text-red-600">ထီမပေါက်</span>
                                        @break
                                    @default
                                        {{ $transaction->type }}
                                @endswitch
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                ပမာဏ
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ number_format($transaction->amount) }} ကျပ်
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                အခြေအနေ
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @switch($transaction->status)
                                        @case('pending')
                                            bg-yellow-100 text-yellow-800
                                            @break
                                        @case('approved')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('rejected')
                                            bg-red-100 text-red-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch">
                                    @switch($transaction->status)
                                        @case('pending')
                                            စောင့်ဆိုင်းဆဲ
                                            @break
                                        @case('approved')
                                            အတည်ပြုပြီး
                                            @break
                                        @case('rejected')
                                            ငြင်းပယ်ခဲ့သည်
                                            @break
                                        @default
                                            {{ $transaction->status }}
                                    @endswitch
                                </span>
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                နေ့စွဲ
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $transaction->created_at->format('Y-m-d H:i:s') }}
                            </dd>
                        </div>

                        @if($transaction->admin_note)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    မှတ်ချက်
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $transaction->admin_note }}
                                </dd>
                            </div>
                        @endif

                        @if($transaction->metadata)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    အခြားအချက်အလက်များ
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <pre class="whitespace-pre-wrap">{{ json_encode($transaction->metadata, JSON_PRETTY_PRINT) }}</pre>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
