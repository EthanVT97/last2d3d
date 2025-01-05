@extends('layouts.lottery-layout')

@section('title', 'ထီပေါက်စဉ်')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Results List -->
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                ထီပေါက်စဉ်များ
            </h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($results as $result)
                <div class="p-4 sm:px-6 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                @switch($result->type)
                                    @case('2d')
                                        <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center">
                                            <span class="text-lg font-bold">2D</span>
                                        </div>
                                        @break
                                    @case('3d')
                                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                                            <span class="text-lg font-bold">3D</span>
                                        </div>
                                        @break
                                    @case('thai')
                                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-lg font-bold">TH</span>
                                        </div>
                                        @break
                                    @case('laos')
                                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                            <span class="text-lg font-bold">LA</span>
                                        </div>
                                        @break
                                @endswitch
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    @switch($result->type)
                                        @case('2d')
                                            2D ထီ
                                            @break
                                        @case('3d')
                                            3D ထီ
                                            @break
                                        @case('thai')
                                            ထိုင်းထီ
                                            @break
                                        @case('laos')
                                            လာအိုထီ
                                            @break
                                    @endswitch
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ Carbon\Carbon::parse($result->draw_time)->format('Y-m-d H:i:s') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900">
                                @if($result->type === 'thai')
                                    <div class="space-y-1">
                                        <div>ပထမဆု: {{ $result->numbers['first_prize'] }}</div>
                                        <div class="text-sm">နောက်ဆုံး ၂ လုံး: {{ $result->numbers['last_two'] }}</div>
                                        <div class="text-sm">ရှေ့ ၃ လုံး: {{ $result->numbers['first_three'] }}</div>
                                        <div class="text-sm">နောက် ၃ လုံး: {{ $result->numbers['last_three'] }}</div>
                                    </div>
                                @else
                                    {{ implode(' ', $result->numbers) }}
                                @endif
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                ဆုငွေ: {{ number_format($result->prize_amount) }} ကျပ်
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 sm:px-6 text-center text-gray-500">
                    ထီပေါက်စဉ်မရှိသေးပါ။
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $results->links() }}
        </div>
    </div>
</div>
@endsection
