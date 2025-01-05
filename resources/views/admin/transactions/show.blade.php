@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Transaction Details</h1>
        <div>
            @if($transaction->status === 'pending')
                <form action="{{ route('admin.transactions.approve', $transaction) }}" method="POST" class="d-inline">
                    @csrf
                    <div class="mb-3">
                        <label for="note" class="form-label">Admin Note</label>
                        <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this transaction?')">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </form>
                <form action="{{ route('admin.transactions.reject', $transaction) }}" method="POST" class="d-inline">
                    @csrf
                    <div class="mb-3">
                        <label for="note" class="form-label">Admin Note</label>
                        <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this transaction?')">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-1"></i>
                    Transaction Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <input type="text" class="form-control" value="{{ ucfirst($transaction->type) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="text" class="form-control" value="{{ number_format($transaction->amount) }} MMK" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control" value="{{ ucfirst($transaction->status) }}" readonly>
                    </div>
                    @if($transaction->payment_method)
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <input type="text" class="form-control" value="{{ strtoupper($transaction->payment_method) }}" readonly>
                    </div>
                    @endif
                    @if($transaction->depositAccount)
                    <div class="mb-3">
                        <label class="form-label">Deposit Account</label>
                        <input type="text" class="form-control" value="{{ $transaction->depositAccount->account_name }} ({{ $transaction->depositAccount->account_number }})" readonly>
                    </div>
                    @endif
                    @if($transaction->metadata)
                    <div class="mb-3">
                        <label class="form-label">Additional Information</label>
                        <dl class="row mb-0">
                            @foreach($transaction->metadata as $key => $value)
                            <dt class="col-sm-4 text-truncate">{{ ucwords(str_replace('_', ' ', $key)) }}</dt>
                            <dd class="col-sm-8">{{ $value }}</dd>
                            @endforeach
                        </dl>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Created At</label>
                        <input type="text" class="form-control" value="{{ $transaction->created_at->format('M d, Y H:i:s') }}" readonly>
                    </div>
                    @if($transaction->approved_at)
                    <div class="mb-3">
                        <label class="form-label">Approved At</label>
                        <input type="text" class="form-control" value="{{ $transaction->approved_at->format('M d, Y H:i:s') }}" readonly>
                    </div>
                    @endif
                    @if($transaction->rejected_at)
                    <div class="mb-3">
                        <label class="form-label">Rejected At</label>
                        <input type="text" class="form-control" value="{{ $transaction->rejected_at->format('M d, Y H:i:s') }}" readonly>
                    </div>
                    @endif
                    @if($transaction->admin_note)
                    <div class="mb-3">
                        <label class="form-label">Admin Note</label>
                        <textarea class="form-control" rows="3" readonly>{{ $transaction->admin_note }}</textarea>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    User Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="{{ $transaction->user->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="{{ $transaction->user->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" value="{{ $transaction->user->phone }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Balance</label>
                        <input type="text" class="form-control" value="{{ number_format($transaction->user->balance) }} MMK" readonly>
                    </div>
                    <a href="{{ route('admin.users.show', $transaction->user) }}" class="btn btn-info">
                        <i class="fas fa-user"></i> View User Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
