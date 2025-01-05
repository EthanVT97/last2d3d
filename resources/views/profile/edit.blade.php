@extends('layouts.lottery-layout')

@section('title', 'ပရိုဖိုင်ပြင်ဆင်ရန်')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                ပရိုဖိုင်ပြင်ဆင်ရန်
            </h3>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Picture -->
                <div class="text-center mb-8">
                    @if ($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile Picture" class="mx-auto h-32 w-32 rounded-full object-cover">
                    @else
                        <div class="mx-auto h-32 w-32 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-4xl font-medium text-primary-600">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <div class="mt-4">
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700">
                            ပရိုဖိုင်ဓာတ်ပုံ
                        </label>
                        <div class="mt-1">
                            <input type="file" name="profile_picture" id="profile_picture"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                   accept="image/*">
                        </div>
                        @error('profile_picture')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">အမည်</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">ဖုန်းနံပါတ်</label>
                        <div class="mt-1">
                            <input type="tel" name="phone" id="phone"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   value="{{ old('phone', $user->phone) }}" required>
                        </div>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">အီးမေးလ်</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">လိပ်စာ</label>
                        <div class="mt-1">
                            <textarea name="address" id="address" rows="3"
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('address', $user->address) }}</textarea>
                        </div>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">မွေးသက္ကရာဇ်</label>
                        <div class="mt-1">
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                        </div>
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-5">
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                သိမ်းဆည်းမည်
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Password Change Form -->
            <div class="mt-10 pt-10 border-t border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">စကားဝှက်ပြောင်းလဲရန်</h3>
                
                <form method="POST" action="{{ route('profile.password.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">လက်ရှိစကားဝှက်</label>
                        <div class="mt-1">
                            <input type="password" name="current_password" id="current_password"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   required>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">စကားဝှက်အသစ်</label>
                        <div class="mt-1">
                            <input type="password" name="password" id="password"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   required>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">စကားဝှက်အသစ် အတည်ပြုရန်</label>
                        <div class="mt-1">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-5">
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                စကားဝှက်ပြောင်းလဲမည်
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
