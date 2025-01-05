@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-money-bill-wave me-2"></i>ငွေထုတ်ယူမှုများ
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>အသုံးပြုသူ</th>
                            <th>ပမာဏ</th>
                            <th>ငွေလက်ခံမည့်သူ</th>
                            <th>အကောင့်နံပါတ်</th>
                            <th>အခြေအနေ</th>
                            <th>စစ်ဆေးသူ</th>
                            <th>မှတ်ချက်</th>
                            <th>နေ့စွဲ</th>
                            <th>လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td>{{ $withdrawal->id }}</td>
                            <td>
                                {{ $withdrawal->user->name }}<br>
                                <small class="text-muted">{{ $withdrawal->user->phone }}</small>
                            </td>
                            <td>{{ number_format($withdrawal->amount) }} MMK</td>
                            <td>
                                {{ $withdrawal->metadata['account_name'] ?? '-' }}<br>
                                <small class="text-muted">{{ strtoupper($withdrawal->payment_method) }}</small>
                            </td>
                            <td>{{ $withdrawal->metadata['account_number'] ?? '-' }}</td>
                            <td>{!! $withdrawal->status_badge !!}</td>
                            <td>
                                @if($withdrawal->status === 'completed')
                                    {{ $withdrawal->approvedBy->name ?? '-' }}<br>
                                    <small class="text-muted">{{ $withdrawal->approved_at?->format('Y-m-d H:i:s') }}</small>
                                @elseif($withdrawal->status === 'rejected')
                                    {{ $withdrawal->rejectedBy->name ?? '-' }}<br>
                                    <small class="text-muted">{{ $withdrawal->rejected_at?->format('Y-m-d H:i:s') }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $withdrawal->admin_note ?? '-' }}</td>
                            <td>
                                {{ $withdrawal->created_at->format('Y-m-d') }}<br>
                                <small class="text-muted">{{ $withdrawal->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                @if($withdrawal->status === 'pending')
                                <div class="btn-group">
                                    <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success me-2" 
                                            onclick="return confirm('ဤငွေထုတ်ယူမှုကို အတည်ပြုမှာ သေချာပါသလား?')">
                                            <i class="fas fa-check me-1"></i>အတည်ပြုရန်
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal{{ $withdrawal->id }}">
                                        <i class="fas fa-times me-1"></i>ငြင်းပယ်ရန်
                                    </button>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">ငွေထုတ်ယူမှု ငြင်းပယ်ရန်</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.withdrawals.reject', $withdrawal) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">ငြင်းပယ်ရသည့် အကြောင်းရင်း</label>
                                                        <textarea class="form-control" name="admin_note" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i>ပိတ်မည်
                                                    </button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-check me-1"></i>ငြင်းပယ်မည်
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-money-bill-wave mb-2 opacity-50" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">ငွေထုတ်ယူမှုများ မရှိသေးပါ</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $withdrawals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
