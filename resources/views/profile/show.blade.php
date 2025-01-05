@extends('layouts.app')

@section('title', 'ပရိုဖိုင်')

@section('content')
<div class="mt-16 container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-blue-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-user-circle mr-2"></i>ပရိုဖိုင်
                </h1>
            </div>

            <div class="p-6">
                @if (session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Profile Picture -->
                <div class="text-center mb-8">
                    @if ($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" 
                             alt="Profile Picture" 
                             class="mx-auto h-32 w-32 rounded-full object-cover border-4 border-blue-100">
                    @else
                        <div class="mx-auto h-32 w-32 rounded-full bg-blue-100 flex items-center justify-center border-4 border-blue-50">
                            <span class="text-4xl font-bold text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <h2 class="mt-4 text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500">Member since {{ $user->created_at->format('F Y') }}</p>
                </div>

                <!-- Profile Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">အီးမေးလ်</label>
                            <div class="mt-1 flex items-center">
                                <span class="text-gray-900">{{ $user->email }}</span>
                                @if($user->email_verified_at)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>အတည်ပြုပြီး
                                    </span>
                                @else
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>အတည်မပြုရသေး
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">ဖုန်းနံပါတ်</label>
                            <div class="mt-1 text-gray-900">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>{{ $user->phone }}
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">မွေးသက္ကရာဇ်</label>
                            <div class="mt-1 text-gray-900">
                                <i class="fas fa-birthday-cake mr-2 text-gray-400"></i>
                                {{ $user->date_of_birth ? $user->date_of_birth->format('F j, Y') : 'မရှိသေးပါ' }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">လိပ်စာ</label>
                            <div class="mt-1 text-gray-900">
                                <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                {{ $user->address ?: 'မရှိသေးပါ' }}
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">လက်ကျန်ငွေ</label>
                            <div class="mt-1 text-gray-900">
                                <i class="fas fa-wallet mr-2 text-gray-400"></i>
                                {{ number_format($user->balance) }} ကျပ်
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">မိတ်ဆက်ကုဒ်</label>
                            <div class="mt-1 text-gray-900">
                                <i class="fas fa-users mr-2 text-gray-400"></i>
                                {{ $user->referral_code ?: 'မရှိသေးပါ' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-edit mr-2"></i>
                        ပရိုဖိုင်ပြင်ဆင်ရန်
                    </a>
                    <a href="{{ route('profile.password') }}" 
                       class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-key mr-2"></i>
                        စကားဝှက်ပြောင်းရန်
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
