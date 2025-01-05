@extends('layouts.lottery-layout')

@section('title', 'ထီထိုးရန်')

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

    <!-- Lottery Types Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($lotteryTypes as $type => $lottery)
            <a href="{{ route('lottery.show', $type) }}" 
               class="relative group bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-200">
                <div class="h-full p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-{{ $lottery['color'] }}-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-{{ $lottery['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $lottery['icon'] }}"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $lottery['name'] }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $lottery['description'] }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
