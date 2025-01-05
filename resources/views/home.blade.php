@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 rounded-2xl shadow-lg mb-8 overflow-hidden">
            <div class="relative px-6 py-8 md:px-8 md:py-12">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
                    <div class="text-white mb-6 md:mb-0">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">မင်္ဂလာပါ {{ Auth::user()->name }}</h2>
                        <p class="text-xl mb-6">လက်ကျန်ငွေ: <span class="font-bold">{{ number_format(Auth::user()->balance ?? 0) }} ကျပ်</span></p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('deposit.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                                <i class="fas fa-plus-circle mr-2"></i>
                                ငွေသွင်းမည်
                            </a>
                            <a href="{{ route('withdraw.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-transparent text-white border-2 border-white rounded-lg font-semibold hover:bg-white hover:text-gray-700 transition-colors">
                                <i class="fas fa-minus-circle mr-2"></i>
                                ငွေထုတ်မည်
                            </a>
                        </div>
                    </div>
                    <div class="hidden md:block text-white opacity-75 text-9xl">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
                <div class="absolute top-0 right-0 p-6 text-white opacity-10">
                    <i class="fas fa-coins text-9xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- Quick Actions -->
            <div class="md:col-span-3">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold mb-6 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        အမြန် လုပ်ဆောင်ချက်များ
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('lottery.2d') }}" 
                           class="flex items-center p-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-dice mr-3"></i>
                            2D ထိုးမည်
                        </a>
                        <a href="{{ route('lottery.3d') }}" 
                           class="flex items-center p-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-cube mr-3"></i>
                            3D ထိုးမည်
                        </a>
                        <a href="{{ route('profile.referrals') }}" 
                           class="flex items-center p-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-users mr-3"></i>
                            မိတ်ဆက်များ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Results -->
            <div class="md:col-span-5">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold mb-6 flex items-center">
                        <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                        နောက်ဆုံးထွက် ထီဂဏန်းများ
                    </h3>
                    @if($recentResults->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentResults as $result)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <span class="text-sm text-gray-500">{{ $result->draw_time->format('Y-m-d H:i') }}</span>
                                        <p class="font-semibold mt-1">{{ $result->number }}</p>
                                    </div>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ $result->type }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">ရလဒ်များ မရှိသေးပါ</p>
                    @endif
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="md:col-span-4">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold mb-6 flex items-center">
                        <i class="fas fa-history text-green-500 mr-2"></i>
                        နောက်ဆုံး ငွေလွှဲမှုများ
                    </h3>
                    @if($recentTransactions->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentTransactions as $transaction)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-semibold">{{ number_format($transaction->amount) }} ကျပ်</p>
                                        <span class="text-sm text-gray-500">{{ $transaction->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-sm
                                        @if($transaction->type == 'deposit')
                                            bg-green-100 text-green-800
                                        @elseif($transaction->type == 'withdrawal')
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $transaction->type }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">ငွေလွှဲမှုများ မရှိသေးပါ</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
