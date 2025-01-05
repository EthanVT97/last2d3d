@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Dashboard</h1>

    <!-- Stats Overview -->
    <div class="row">
        <!-- Users Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4">{{ number_format($stats['total_users']) }}</div>
                            <div>Total Users</div>
                        </div>
                        <div class="align-self-center">
                            @if($stats['growth']['users']['percentage'] >= 0)
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                            {{ abs($stats['growth']['users']['percentage']) }}%
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.users.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Plays Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4">{{ number_format($stats['total_plays']) }}</div>
                            <div>Total Plays</div>
                        </div>
                        <div class="align-self-center">
                            @if($stats['growth']['plays']['percentage'] >= 0)
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                            {{ abs($stats['growth']['plays']['percentage']) }}%
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.plays.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Deposits Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4">${{ number_format($stats['total_deposits']) }}</div>
                            <div>Total Deposits</div>
                        </div>
                        <div class="align-self-center">
                            @if($stats['growth']['deposits']['percentage'] >= 0)
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                            {{ abs($stats['growth']['deposits']['percentage']) }}%
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.transactions.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Withdrawals Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4">${{ number_format($stats['total_withdrawals']) }}</div>
                            <div>Total Withdrawals</div>
                        </div>
                        <div class="align-self-center">
                            @if($stats['growth']['withdrawals']['percentage'] >= 0)
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                            {{ abs($stats['growth']['withdrawals']['percentage']) }}%
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.transactions.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Activity -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Today's Activity
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td>New Users</td>
                                <td>{{ number_format($stats['today']['new_users']) }}</td>
                            </tr>
                            <tr>
                                <td>Total Plays</td>
                                <td>{{ number_format($stats['today']['total_plays']) }}</td>
                            </tr>
                            <tr>
                                <td>Total Deposits</td>
                                <td>${{ number_format($stats['today']['total_deposits']) }}</td>
                            </tr>
                            <tr>
                                <td>Total Withdrawals</td>
                                <td>${{ number_format($stats['today']['total_withdrawals']) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Actions -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-clock me-1"></i>
                    Pending Actions
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td>Pending Deposits</td>
                                <td>
                                    <span class="badge bg-warning">{{ number_format($stats['pending']['deposits']) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Pending Withdrawals</td>
                                <td>
                                    <span class="badge bg-warning">{{ number_format($stats['pending']['withdrawals']) }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <!-- Recent Users -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-users me-1"></i>
                    Recent Users
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_users'] as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
                                    </td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Plays -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-gamepad me-1"></i>
                    Recent Plays
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_plays'] as $play)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.show', $play->user) }}">{{ $play->user->name }}</a>
                                    </td>
                                    <td>${{ number_format($play->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-1"></i>
                    Recent Transactions
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_transactions'] as $transaction)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.show', $transaction->user) }}">{{ $transaction->user->name }}</a>
                                    </td>
                                    <td>{{ ucfirst($transaction->type) }}</td>
                                    <td>${{ number_format($transaction->amount, 2) }}</td>
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
