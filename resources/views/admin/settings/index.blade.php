@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Time Settings Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock me-2"></i>ထီထိုးချိန် သတ်မှတ်ချက်များ
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.time.update') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <!-- 2D Morning -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="mb-3">2D မနက်ပိုင်း</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ထီပိတ်ချိန်</label>
                                        <input type="time" class="form-control @error('2d_morning_close_time') is-invalid @enderror" 
                                            name="2d_morning_close_time" value="{{ $timeSettings['2d_morning_close_time'] ?? '11:30' }}" required>
                                        @error('2d_morning_close_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ထီပေါက်ချိန်</label>
                                        <input type="time" class="form-control @error('2d_morning_result_time') is-invalid @enderror" 
                                            name="2d_morning_result_time" value="{{ $timeSettings['2d_morning_result_time'] ?? '12:00' }}" required>
                                        @error('2d_morning_result_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 2D Evening -->
                            <div class="col-md-6">
                                <h6 class="mb-3">2D ညနေပိုင်း</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ထီပိတ်ချိန်</label>
                                        <input type="time" class="form-control @error('2d_evening_close_time') is-invalid @enderror" 
                                            name="2d_evening_close_time" value="{{ $timeSettings['2d_evening_close_time'] ?? '16:30' }}" required>
                                        @error('2d_evening_close_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ထီပေါက်ချိန်</label>
                                        <input type="time" class="form-control @error('2d_evening_result_time') is-invalid @enderror" 
                                            name="2d_evening_result_time" value="{{ $timeSettings['2d_evening_result_time'] ?? '16:45' }}" required>
                                        @error('2d_evening_result_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3D -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="mb-3">3D</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ထီပိတ်ချိန်</label>
                                        <input type="time" class="form-control @error('3d_close_time') is-invalid @enderror" 
                                            name="3d_close_time" value="{{ $timeSettings['3d_close_time'] ?? '16:30' }}" required>
                                        @error('3d_close_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ထီပေါက်ချိန်</label>
                                        <input type="time" class="form-control @error('3d_result_time') is-invalid @enderror" 
                                            name="3d_result_time" value="{{ $timeSettings['3d_result_time'] ?? '16:45' }}" required>
                                        @error('3d_result_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Thai -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Thai</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ထီပိတ်ချိန်</label>
                                        <input type="time" class="form-control @error('thai_close_time') is-invalid @enderror" 
                                            name="thai_close_time" value="{{ $timeSettings['thai_close_time'] ?? '16:30' }}" required>
                                        @error('thai_close_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ထီပေါက်ချိန်</label>
                                        <input type="time" class="form-control @error('thai_result_time') is-invalid @enderror" 
                                            name="thai_result_time" value="{{ $timeSettings['thai_result_time'] ?? '16:45' }}" required>
                                        @error('thai_result_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>သိမ်းမည်
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lottery Settings Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-2"></i>ထီအမျိုးအစားအလိုက် သတ်မှတ်ချက်များ
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>အမျိုးအစား</th>
                                    <th>အနည်းဆုံး</th>
                                    <th>အများဆုံး</th>
                                    <th>အခြေအနေ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['2d', '3d', 'thai'] as $type)
                                    <tr>
                                        <td>{{ strtoupper($type) }}</td>
                                        <td>{{ number_format($lotterySettings[$type]['min_amount'] ?? 100) }} ကျပ်</td>
                                        <td>{{ number_format($lotterySettings[$type]['max_amount'] ?? 50000) }} ကျပ်</td>
                                        <td>
                                            @if($lotterySettings[$type]['is_active'] ?? true)
                                                <span class="badge bg-success">ဖွင့်ထား</span>
                                            @else
                                                <span class="badge bg-danger">ပိတ်ထား</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.settings.edit', $type) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit me-1"></i>ပြင်မည်
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
