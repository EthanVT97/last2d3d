@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Agent Details</h1>
        <div>
            <a href="{{ route('admin.agents.edit', $user) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit Agent
            </a>
            <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Agent Info -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Agent Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold">Name:</label>
                        <p class="mb-0">{{ $user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Phone:</label>
                        <p class="mb-0">{{ $user->phone }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Status:</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Commission Rate:</label>
                        <p class="mb-0">{{ $user->commission_rate }}%</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Commission Balance:</label>
                        <p class="mb-0">${{ number_format($user->commission_balance, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Joined Date:</label>
                        <p class="mb-0">{{ $user->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-xl-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <h4 class="mb-2">{{ number_format($user->referrals_count) }}</h4>
                            <div>Total Referrals</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <h4 class="mb-2">{{ number_format($user->plays_count) }}</h4>
                            <div>Total Plays</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">
                            <h4 class="mb-2">${{ number_format($user->total_deposits ?? 0, 2) }}</h4>
                            <div>Total Deposits</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <h4 class="mb-2">${{ number_format($user->total_withdrawals ?? 0, 2) }}</h4>
                            <div>Total Withdrawals</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#referrals">Recent Referrals</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#plays">Recent Plays</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#transactions">Recent Transactions</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Recent Referrals -->
                        <div class="tab-pane fade show active" id="referrals">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Phone</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentReferrals as $referral)
                                        <tr>
                                            <td>{{ $referral->name }}</td>
                                            <td>{{ $referral->phone }}</td>
                                            <td>{{ $referral->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No recent referrals</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Recent Plays -->
                        <div class="tab-pane fade" id="plays">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>Commission</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPlays as $play)
                                        <tr>
                                            <td>{{ $play->user->name }}</td>
                                            <td>${{ number_format($play->amount, 2) }}</td>
                                            <td>${{ number_format($play->commission_amount, 2) }}</td>
                                            <td>{{ $play->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No recent plays</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Recent Transactions -->
                        <div class="tab-pane fade" id="transactions">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentTransactions as $transaction)
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $transaction->type === 'deposit' ? 'success' : 'info' }}">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td>${{ number_format($transaction->amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $transaction->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No recent transactions</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
