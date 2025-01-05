@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-8">ပရိုဖိုင်</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Profile Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold mb-6">ကိုယ်ရေးအချက်အလက်များ</h2>
            
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">အမည်</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', auth()->user()->name) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">ဖုန်းနံပါတ်</label>
                    <input type="text" name="phone" id="phone" 
                           value="{{ old('phone', auth()->user()->phone) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        အချက်အလက်များ ပြောင်းလဲရန်
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-6">စကားဝှက် ပြောင်းလဲရန်</h2>
            
            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">လက်ရှိ စကားဝှက်</label>
                    <input type="password" name="current_password" id="current_password" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">စကားဝှက် အသစ်</label>
                    <input type="password" name="password" id="password" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">စကားဝှက် အသစ် အတည်ပြုရန်</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        စကားဝှက် ပြောင်းလဲရန်
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
            <h2 class="text-lg font-semibold mb-6">အကောင့် အချက်အလက်များ</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">အကောင့် အမျိုးအစား</p>
                    <p class="font-medium">{{ auth()->user()->is_agent ? 'ကိုယ်စားလှယ်' : 'သာမန် အသုံးပြုသူ' }}</p>
                </div>

                @if(auth()->user()->is_agent)
                    <div>
                        <p class="text-sm text-gray-500">ကိုယ်စားလှယ် ကုဒ်</p>
                        <p class="font-medium">{{ auth()->user()->agent_code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">ကော်မရှင် နှုန်း</p>
                        <p class="font-medium">{{ auth()->user()->agent_commission_rate }}%</p>
                    </div>
                @endif

                <div>
                    <p class="text-sm text-gray-500">မိတ်ဆက် ကုဒ်</p>
                    <p class="font-medium">{{ auth()->user()->referral_code ?: 'မရှိသေးပါ' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">အကောင့် ဖွင့်သည့်နေ့</p>
                    <p class="font-medium">{{ auth()->user()->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
