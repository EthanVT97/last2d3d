@extends('layouts.app')

@section('title', 'ပေါက်ဂဏန်းများ')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">ပေါက်ဂဏန်းများ</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- 2D Results -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">မြန်မာ 2D</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-600 mb-2">မွန်းတည့် ၁၂:၀၁</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $results['2d']->number ?? '- -' }}</p>
                    <p class="text-sm text-gray-600">{{ optional($results['2d'])->drawn_at?->format('Y-m-d H:i:s') ?? '-' }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('lottery.2d') }}" class="text-blue-600 hover:text-blue-800">2D ထိုးရန် →</a>
            </div>
        </div>

        <!-- 3D Results -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">မြန်မာ 3D</h2>
            <div>
                <p class="text-3xl font-bold text-blue-600">{{ $results['3d']->number ?? '- - -' }}</p>
                <p class="text-sm text-gray-600">{{ optional($results['3d'])->drawn_at?->format('Y-m-d H:i:s') ?? '-' }}</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('lottery.3d') }}" class="text-blue-600 hover:text-blue-800">3D ထိုးရန် →</a>
            </div>
        </div>

        <!-- Thai Lottery Results -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">ထိုင်း လော့ထရီ</h2>
            <div>
                <p class="text-3xl font-bold text-blue-600">{{ optional($results['thai'])->metadata['first_prize'] ?? '- - - - - -' }}</p>
                <p class="text-sm text-gray-600">{{ optional($results['thai'])->drawn_at?->format('Y-m-d H:i:s') ?? '-' }}</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('lottery.thai') }}" class="text-blue-600 hover:text-blue-800">ထိုင်း ထိုးရန် →</a>
            </div>
        </div>

        <!-- Laos Lottery Results -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">လာအို လော့ထရီ</h2>
            <div>
                <p class="text-3xl font-bold text-blue-600">{{ $results['laos']->number ?? '- - - - -' }}</p>
                <p class="text-sm text-gray-600">{{ optional($results['laos'])->drawn_at?->format('Y-m-d H:i:s') ?? '-' }}</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('lottery.laos') }}" class="text-blue-600 hover:text-blue-800">လာအို ထိုးရန် →</a>
            </div>
        </div>
    </div>
</div>
@endsection
