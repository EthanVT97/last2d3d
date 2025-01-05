@extends('layouts.lottery-layout')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Balance Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">လက်ကျန်ငွေ</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format(auth()->user()->balance) }} ကျပ်</h3>
                </div>
                <div class="p-3 bg-primary-100 rounded-full">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex space-x-3">
                <a href="{{ route('deposit') }}" class="flex-1 text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                    ငွေသွင်းမည်
                </a>
                <a href="{{ route('withdraw') }}" class="flex-1 text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    ငွေထုတ်မည်
                </a>
            </div>
        </div>

        <!-- Winnings Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">စုစုပေါင်း အနိုင်ရငွေ</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalWinnings) }} ကျပ်</h3>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-600">
                    <span class="flex-1">အနိုင်ရအကြိမ်:</span>
                    <span class="font-medium">{{ $totalWins }} ကြိမ်</span>
                </div>
            </div>
        </div>

        <!-- Today's Plays Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">ယနေ့ ထိုးငွေ</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($todayPlays) }} ကျပ်</h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-600">
                    <span class="flex-1">ထိုးထားသော နံပါတ်:</span>
                    <span class="font-medium">{{ $todayNumbers }} လုံး</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">လှုပ်ရှားမှု မှတ်တမ်း</h2>
        </div>
        <div class="border-t border-gray-200">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($activities as $activity)
                    <li class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($activity->type === 'deposit')
                                    <div class="p-2 bg-green-100 rounded-full">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                @elseif($activity->type === 'withdraw')
                                    <div class="p-2 bg-red-100 rounded-full">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="p-2 bg-blue-100 rounded-full">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $activity->description }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->amount >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $activity->amount >= 0 ? '+' : '' }}{{ number_format($activity->amount) }} ကျပ်
                                </span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="p-6">
                        <p class="text-center text-gray-500">လှုပ်ရှားမှု မှတ်တမ်း မရှိသေးပါ</p>
                    </li>
                @endforelse
            </ul>
        </div>
        @if($activities->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
