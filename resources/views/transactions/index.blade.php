@extends('layouts.lottery-layout')

@section('title', 'ငွေစာရင်း')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Balance Card -->
    <div class="mb-8 bg-gradient-to-br from-primary-600 to-primary-700 rounded-2xl shadow-lg overflow-hidden">
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

    <!-- Transactions List -->
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                ငွေစာရင်းများ
            </h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($transactions as $transaction)
                <div class="p-4 sm:px-6 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                @switch($transaction->type)
                                    @case('deposit')
                                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </div>
                                        @break
                                    @case('withdrawal')
                                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </div>
                                        @break
                                    @case('bet')
                                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                        </div>
                                        @break
                                    @case('win')
                                        <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        @break
                                    @default
                                        <div class="w-10 h-10 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                @endswitch
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    @switch($transaction->type)
                                        @case('deposit')
                                            ငွေသွင်း
                                            @break
                                        @case('withdrawal')
                                            ငွေထုတ်
                                            @break
                                        @case('bet')
                                            ထီထိုး
                                            @break
                                        @case('win')
                                            ထီပေါက်
                                            @break
                                        @default
                                            {{ $transaction->type }}
                                    @endswitch
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $transaction->created_at->format('Y-m-d H:i:s') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium {{ in_array($transaction->type, ['deposit', 'win']) ? 'text-green-600' : 'text-red-600' }}">
                                {{ in_array($transaction->type, ['deposit', 'win']) ? '+' : '-' }}{{ number_format($transaction->amount) }} ကျပ်
                            </p>
                            <p class="text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->status_color === 'green' ? 'bg-green-100 text-green-800' : ($transaction->status_color === 'yellow' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $transaction->status_text }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 sm:px-6 text-center text-gray-500">
                    ငွေစာရင်းမရှိသေးပါ။
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
