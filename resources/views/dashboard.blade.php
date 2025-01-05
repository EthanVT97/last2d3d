@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Dashboard</h1>

    <!-- Balance Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">လက်ကျန်ငွေ</h2>
        <p class="text-4xl font-bold text-blue-600">{{ number_format($data['balance']) }} ကျပ်</p>
        <div class="mt-4 space-x-4">
            <a href="/deposit" class="inline-block bg-green-600 text-white px-4 py-2 rounded">ငွေသွင်းရန်</a>
            <a href="/withdraw" class="inline-block bg-red-600 text-white px-4 py-2 rounded">ငွေထုတ်ရန်</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Recent Plays -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">မကြာသေးမီက ထိုးထားသည်များ</h2>
            @if($data['recent_plays']->isEmpty())
                <p class="text-gray-600">မရှိသေးပါ</p>
            @else
                <div class="space-y-4">
                    @foreach($data['recent_plays'] as $play)
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold">{{ strtoupper($play->type) }} - {{ $play->numbers }}</p>
                                    <p class="text-sm text-gray-600">{{ $play->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold">{{ number_format($play->amount) }} ကျပ်</p>
                                    <p class="text-sm {{ $play->status === 'won' ? 'text-green-600' : ($play->status === 'lost' ? 'text-red-600' : 'text-yellow-600') }}">
                                        {{ $play->status === 'won' ? 'အနိုင်' : ($play->status === 'lost' ? 'အရှုံး' : 'စောင့်ဆိုင်းဆဲ') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="/history" class="text-blue-600 hover:text-blue-800">အားလုံးကြည့်ရန် →</a>
                </div>
            @endif
        </div>

        <!-- Recent Wins -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">မကြာသေးမီက အနိုင်ရမှုများ</h2>
            @if($data['recent_wins']->isEmpty())
                <p class="text-gray-600">မရှိသေးပါ</p>
            @else
                <div class="space-y-4">
                    @foreach($data['recent_wins'] as $win)
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold">{{ strtoupper($win->type) }} - {{ $win->numbers }}</p>
                                    <p class="text-sm text-gray-600">{{ $win->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">+ {{ number_format($win->won_amount) }} ကျပ်</p>
                                    <p class="text-sm text-gray-600">ထိုးငွေ: {{ number_format($win->amount) }} ကျပ်</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
        <a href="/play/2d" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
            <h3 class="font-bold mb-2">2D ထိုးရန်</h3>
            <p class="text-sm text-gray-600">နေ့စဉ် ၂ကြိမ်</p>
        </a>
        <a href="/play/3d" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
            <h3 class="font-bold mb-2">3D ထိုးရန်</h3>
            <p class="text-sm text-gray-600">လစဉ် ၂ကြိမ်</p>
        </a>
        <a href="/play/thai" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
            <h3 class="font-bold mb-2">ထိုင်း ထိုးရန်</h3>
            <p class="text-sm text-gray-600">လစဉ် ၂ကြိမ်</p>
        </a>
        <a href="/play/laos" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
            <h3 class="font-bold mb-2">လာအို ထိုးရန်</h3>
            <p class="text-sm text-gray-600">အပတ်စဉ် ၃ကြိမ်</p>
        </a>
    </div>
</div>
@endsection
