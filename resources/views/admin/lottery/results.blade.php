@extends('layouts.admin')

@section('title', 'Lottery Results Management')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">ထီပေါက်စဉ် စီမံခန့်ခွဲမှု</h1>
            <p class="mt-2 text-sm text-gray-700">ထီပေါက်စဉ်များကို ထည့်သွင်းခြင်း နှင့် စီမံခန့်ခွဲခြင်း</p>
        </div>
    </div>

    <!-- Upcoming Draws -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900">လာမည့် ထီဖွင့်ပွဲများ</h2>
            
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- 2D Next Draw -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-medium text-gray-900">2D</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ $next2DDraw->draw_time->format('h:i A') }}
                        </span>
                    </div>
                    
                    @if($next2DDraw->status === 'pending')
                        <form action="{{ route('admin.lottery.record-result') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="draw_id" value="{{ $next2DDraw->id }}">
                            <input type="hidden" name="type" value="2d">
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="2d_number" class="block text-sm font-medium text-gray-700">ပေါက်ဂဏန်း</label>
                                    <div class="mt-1">
                                        <input type="text" name="number" id="2d_number" required
                                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               pattern="\d{2}" maxlength="2"
                                               placeholder="00-99">
                                    </div>
                                </div>
                                
                                <button type="submit"
                                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    ထီဖွင့်မည်
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">ထီဖွင့်ပြီးပါပြီ</p>
                            <p class="mt-1 text-2xl font-bold text-primary-600">{{ $next2DDraw->result->number }}</p>
                        </div>
                    @endif
                </div>

                <!-- 3D Next Draw -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-medium text-gray-900">3D</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ $next3DDraw->draw_time->format('M j, h:i A') }}
                        </span>
                    </div>
                    
                    @if($next3DDraw->status === 'pending')
                        <form action="{{ route('admin.lottery.record-result') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="draw_id" value="{{ $next3DDraw->id }}">
                            <input type="hidden" name="type" value="3d">
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="3d_number" class="block text-sm font-medium text-gray-700">ပေါက်ဂဏန်း</label>
                                    <div class="mt-1">
                                        <input type="text" name="number" id="3d_number" required
                                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               pattern="\d{3}" maxlength="3"
                                               placeholder="000-999">
                                    </div>
                                </div>
                                
                                <button type="submit"
                                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    ထီဖွင့်မည်
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">ထီဖွင့်ပြီးပါပြီ</p>
                            <p class="mt-1 text-2xl font-bold text-primary-600">{{ $next3DDraw->result->number }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Previous Results -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900">ယခင် ထီပေါက်စဉ်များ</h2>
            
            <div class="mt-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">အမျိုးအစား</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ရက်စွဲ/အချိန်</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ပေါက်ဂဏန်း</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ထိုးသူ</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">အနိုင်ရသူ</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ထုတ်ပေးငွေ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($previousResults as $result)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ strtoupper($result->draw->type) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $result->created_at->format('M j, h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $result->number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $result->draw->plays_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $result->draw->winning_plays_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($result->draw->total_payout) }} ကျပ်
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($previousResults->hasPages())
                    <div class="mt-6">
                        {{ $previousResults->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format 2D number input
    const input2D = document.getElementById('2d_number');
    if (input2D) {
        input2D.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) value = value.slice(0, 2);
            e.target.value = value;
        });
    }

    // Auto-format 3D number input
    const input3D = document.getElementById('3d_number');
    if (input3D) {
        input3D.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 3) value = value.slice(0, 3);
            e.target.value = value;
        });
    }
});
</script>
@endpush
