@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Expense Report</h1>
        <div>
            <form action="{{ route('admin.reports.expense') }}" method="GET" class="d-flex gap-2">
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Expenses</h5>
                    <h2>{{ number_format($expenseStats['total_expenses'], 2) }} MMK</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Withdrawals</h5>
                    <h2>{{ number_format($expenseStats['total_withdrawals'], 2) }} MMK</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Winning Payouts</h5>
                    <h2>{{ number_format($expenseStats['total_winning_payouts'], 2) }} MMK</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Withdrawal Transactions
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $withdrawal)
                        <tr>
                            <td>{{ $withdrawal->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $withdrawal->user->name }}</td>
                            <td>{{ number_format($withdrawal->amount, 2) }} MMK</td>
                            <td>{{ ucfirst($withdrawal->status) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $withdrawals->links() }}
        </div>
    </div>

    <!-- Winning Plays Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Winning Plays
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Game Type</th>
                            <th>Numbers</th>
                            <th>Bet Amount</th>
                            <th>Winning Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($winningPlays as $play)
                        <tr>
                            <td>{{ $play->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $play->user->name }}</td>
                            <td>{{ strtoupper($play->type) }}</td>
                            <td>{{ $play->numbers }}</td>
                            <td>{{ number_format($play->amount, 2) }} MMK</td>
                            <td>{{ number_format($play->amount * ($play->type == '2d' ? 85 : 500), 2) }} MMK</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $winningPlays->links() }}
        </div>
    </div>
</div>
@endsection
