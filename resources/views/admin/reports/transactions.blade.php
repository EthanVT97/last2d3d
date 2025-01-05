@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chart-bar me-2"></i>ငွေလွှဲမှုများအစီရင်ခံစာ
            </h6>
        </div>
        <div class="card-body">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        စုစုပေါင်းငွေလွှဲမှုအရေအတွက်
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totals['total_count']) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        စုစုပေါင်းပမာဏ
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totals['total_amount']) }} MMK</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        ငွေသွင်းမှုများ
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($totals['by_type']['deposit']['count']) }} ({{ number_format($totals['by_type']['deposit']['amount']) }} MMK)
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        ငွေထုတ်မှုများ
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($totals['by_type']['withdrawal']['count']) }} ({{ number_format($totals['by_type']['withdrawal']['amount']) }} MMK)
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        စိစစ်နေဆဲ
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($totals['by_status']['pending']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        အတည်ပြုပြီး
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($totals['by_status']['completed']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        ငြင်းပယ်ထား
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($totals['by_status']['rejected']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <form action="{{ route('admin.reports.transactions') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">နေ့စွဲ အစ</label>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">နေ့စွဲ အဆုံး</label>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">အမျိုးအစား</label>
                        <select class="form-select" name="type">
                            <option value="">အားလုံး</option>
                            <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>ငွေသွင်း</option>
                            <option value="withdrawal" {{ request('type') === 'withdrawal' ? 'selected' : '' }}>ငွေထုတ်</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">အခြေအနေ</label>
                        <select class="form-select" name="status">
                            <option value="">အားလုံး</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>စိစစ်နေဆဲ</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>အတည်ပြုပြီး</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>ငြင်းပယ်ထား</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">အဆင့်</label>
                        <select class="form-select" name="approval_level">
                            <option value="">အားလုံး</option>
                            <option value="admin" {{ request('approval_level') === 'admin' ? 'selected' : '' }}>အက်ဒမင်</option>
                            <option value="agent" {{ request('approval_level') === 'agent' ? 'selected' : '' }}>အေးဂျင့်</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">အသုံးပြုသူ ရှာရန်</label>
                        <input type="text" class="form-control" name="user_search" value="{{ request('user_search') }}" placeholder="အမည် သို့မဟုတ် ဖုန်းနံပါတ်">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">ပမာဏ အနည်းဆုံး</label>
                        <input type="number" class="form-control" name="min_amount" value="{{ request('min_amount') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">ပမာဏ အများဆုံး</label>
                        <input type="number" class="form-control" name="max_amount" value="{{ request('max_amount') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>ရှာဖွေရန်
                        </button>
                        <a href="{{ route('admin.reports.transactions') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>ပြန်စရန်
                        </a>
                    </div>
                </div>
            </form>

            <!-- Transactions Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>အမျိုးအစား</th>
                            <th>အသုံးပြုသူ</th>
                            <th>ပမာဏ</th>
                            <th>အခြေအနေ</th>
                            <th>အဆင့်</th>
                            <th>စစ်ဆေးသူ</th>
                            <th>မှတ်ချက်</th>
                            <th>နေ့စွဲ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->type_text }}</td>
                            <td>
                                {{ $transaction->user->name }}<br>
                                <small class="text-muted">{{ $transaction->user->phone }}</small>
                            </td>
                            <td>{{ number_format($transaction->amount) }} MMK</td>
                            <td>{!! $transaction->status_badge !!}</td>
                            <td>{{ $transaction->approval_level_text }}</td>
                            <td>
                                @if($transaction->status === 'completed')
                                    {{ $transaction->approvedBy->name ?? '-' }}<br>
                                    <small class="text-muted">{{ $transaction->approved_at?->format('Y-m-d H:i:s') }}</small>
                                @elseif($transaction->status === 'rejected')
                                    {{ $transaction->rejectedBy->name ?? '-' }}<br>
                                    <small class="text-muted">{{ $transaction->rejected_at?->format('Y-m-d H:i:s') }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $transaction->admin_note ?? '-' }}</td>
                            <td>
                                {{ $transaction->created_at->format('Y-m-d') }}<br>
                                <small class="text-muted">{{ $transaction->created_at->format('H:i:s') }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-exchange-alt mb-2 opacity-50" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">ငွေလွှဲမှုများ မရှိသေးပါ</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
