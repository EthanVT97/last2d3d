@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exchange-alt me-2"></i>ငွေလွှဲမှုများ
                    </h6>
                </div>
                <div class="card-body">
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
                                    <th>လုပ်ဆောင်ချက်</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->type_text }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $transaction->user_id) }}" class="text-decoration-none">
                                            {{ $transaction->user->name }}
                                        </a>
                                    </td>
                                    <td>{{ number_format($transaction->amount) }} ကျပ်</td>
                                    <td>{!! $transaction->status_badge !!}</td>
                                    <td>
                                        <small class="text-muted">{{ $transaction->approval_level_text }}</small>
                                    </td>
                                    <td>
                                        @if($transaction->status === 'completed')
                                            <small class="text-success">
                                                {{ $transaction->approvedBy->name ?? 'Unknown' }}<br>
                                                <span class="text-muted">{{ $transaction->approved_at?->format('d-M-Y H:i') }}</span>
                                            </small>
                                        @elseif($transaction->status === 'rejected')
                                            <small class="text-danger">
                                                {{ $transaction->rejectedBy->name ?? 'Unknown' }}<br>
                                                <span class="text-muted">{{ $transaction->rejected_at?->format('d-M-Y H:i') }}</span>
                                            </small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->type === 'deposit')
                                            <div class="small">
                                                <div class="fw-medium">{{ $transaction->payment_method }}</div>
                                                @if($transaction->metadata)
                                                    <div class="text-muted">
                                                        လွှဲပို့သူ: {{ $transaction->metadata['sender_phone'] }}<br>
                                                        အကောင့်: {{ $transaction->metadata['account_name'] }}<br>
                                                        နံပါတ်: {{ $transaction->metadata['account_number'] }}<br>
                                                        Ref: {{ $transaction->reference_id }}
                                                    </div>
                                                    @if(isset($transaction->metadata['screenshot']))
                                                        <a href="{{ Storage::url($transaction->metadata['screenshot']) }}" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-outline-primary mt-1">
                                                            <i class="fas fa-image me-1"></i>ပြေစာကြည့်ရန်
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <small class="text-muted">{{ $transaction->admin_note ?: '-' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $transaction->created_at->format('d-M-Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($transaction->status === 'pending')
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal{{ $transaction->id }}">
                                                    <i class="fas fa-check me-1"></i>အတည်ပြု
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $transaction->id }}">
                                                    <i class="fas fa-times me-1"></i>ငြင်းပယ်
                                                </button>
                                            </div>

                                            <!-- Approve Modal -->
                                            <div class="modal fade" id="approveModal{{ $transaction->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.transactions.approve', ['transaction' => $transaction->id]) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">ငွေလွှဲမှုအတည်ပြုခြင်း</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label small text-muted">မှတ်ချက်</label>
                                                                    <textarea name="note" class="form-control" rows="2"></textarea>
                                                                </div>
                                                                <p class="mb-0">
                                                                    ဤငွေလွှဲမှုကို အတည်ပြုမှာ သေချာပါသလား?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    မလုပ်တော့ပါ
                                                                </button>
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fas fa-check me-1"></i>အတည်ပြုမည်
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $transaction->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.transactions.reject', ['transaction' => $transaction->id]) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">ငွေလွှဲမှုငြင်းပယ်ခြင်း</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label small text-muted">ငြင်းပယ်ရသည့် အကြောင်းရင်း</label>
                                                                    <textarea name="note" class="form-control" rows="2" required></textarea>
                                                                </div>
                                                                <p class="mb-0">
                                                                    ဤငွေလွှဲမှုကို ငြင်းပယ်မှာ သေချာပါသလား?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    မလုပ်တော့ပါ
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-times me-1"></i>ငြင်းပယ်မည်
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
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        <i class="fas fa-exchange-alt mb-2 opacity-50" style="font-size: 2rem;"></i>
                                        <p class="mb-0">ငွေလွှဲမှုများ မရှိသေးပါ</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
