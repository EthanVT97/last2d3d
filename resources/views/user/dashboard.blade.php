@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Section -->
        <div class="bg-blue-600 text-white rounded-2xl shadow-lg mb-8 p-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-2">မင်္ဂလာပါ {{ auth()->user()->name }}</h2>
                    <p class="text-blue-100">သင့်ရဲ့ ထီကံထားချက်များကို ဒီနေရာမှာ စီမံနိုင်ပါတယ်။</p>
                </div>
                <div class="text-4xl mt-4 md:mt-0">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Balance Card -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase mb-2">လက်ကျန်ငွေ</p>
                        <h3 class="text-2xl font-bold">{{ number_format($stats['balance']) }} Ks</h3>
                    </div>
                    <div class="bg-blue-600 text-white p-3 rounded-lg">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Deposits Card -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase mb-2">စုစုပေါင်း ငွေသွင်း</p>
                        <h3 class="text-2xl font-bold">{{ number_format($stats['total_deposits']) }} Ks</h3>
                    </div>
                    <div class="bg-green-500 text-white p-3 rounded-lg">
                        <i class="fas fa-arrow-down text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Withdrawals Card -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase mb-2">စုစုပေါင်း ငွေထုတ်</p>
                        <h3 class="text-2xl font-bold">{{ number_format($stats['total_withdrawals']) }} Ks</h3>
                    </div>
                    <div class="bg-red-500 text-white p-3 rounded-lg">
                        <i class="fas fa-arrow-up text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Plays Card -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase mb-2">စုစုပေါင်း ထိုးထားသည့်ပွဲ</p>
                        <h3 class="text-2xl font-bold">{{ number_format($stats['total_plays']) }}</h3>
                    </div>
                    <div class="bg-indigo-500 text-white p-3 rounded-lg">
                        <i class="fas fa-ticket-alt text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Wins Card -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase mb-2">စုစုပေါင်း ပေါက်ပွဲ</p>
                        <h3 class="text-2xl font-bold">{{ number_format($stats['total_wins']) }}</h3>
                    </div>
                    <div class="bg-yellow-500 text-white p-3 rounded-lg">
                        <i class="fas fa-trophy text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h5 class="text-lg font-bold mb-6">အမြန် လုပ်ဆောင်ချက်များ</h5>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('lottery.2d') }}" 
                   class="flex flex-col items-center p-4 rounded-xl border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all text-gray-700 hover:text-blue-600">
                    <i class="fas fa-dice text-2xl mb-2"></i>
                    <span>2D ထိုးရန်</span>
                </a>
                <a href="{{ route('deposit.create') }}" 
                   class="flex flex-col items-center p-4 rounded-xl border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all text-gray-700 hover:text-blue-600">
                    <i class="fas fa-money-bill-wave text-2xl mb-2"></i>
                    <span>ငွေသွင်းရန်</span>
                </a>
                <a href="{{ route('withdraw.create') }}" 
                   class="flex flex-col items-center p-4 rounded-xl border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all text-gray-700 hover:text-blue-600">
                    <i class="fas fa-money-bill-transfer text-2xl mb-2"></i>
                    <span>ငွေထုတ်ရန်</span>
                </a>
                <a href="{{ route('profile.show') }}" 
                   class="flex flex-col items-center p-4 rounded-xl border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all text-gray-700 hover:text-blue-600">
                    <i class="fas fa-user text-2xl mb-2"></i>
                    <span>ပရိုဖိုင်</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
