@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Profit Report</h1>
        <div>
            <form action="{{ route('admin.reports.profit') }}" method="GET" class="d-flex gap-2">
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Income</h5>
                    <h2>{{ number_format($totalStats['total_income'], 2) }} MMK</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Expenses</h5>
                    <h2>{{ number_format($totalStats['total_expenses'], 2) }} MMK</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card {{ $totalStats['total_profit'] >= 0 ? 'bg-primary' : 'bg-danger' }} text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Net Profit</h5>
                    <h2>{{ number_format($totalStats['total_profit'], 2) }} MMK</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit Chart -->
    <div class="card mb-4">
        <div class="card-body">
            <canvas id="profitChart"></canvas>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Daily Profit Breakdown
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Income</th>
                            <th>Expenses</th>
                            <th>Net Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($profitStats as $stat)
                        <tr>
                            <td>{{ $stat->date }}</td>
                            <td>{{ number_format($stat->income, 2) }} MMK</td>
                            <td>{{ number_format($stat->expenses, 2) }} MMK</td>
                            <td class="{{ $stat->net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($stat->net_profit, 2) }} MMK
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('profitChart').getContext('2d');
    
    // Parse PHP variables
    const labels = JSON.parse('@json($profitStats->pluck("date"))');
    const incomeData = JSON.parse('@json($profitStats->pluck("income"))');
    const expenseData = JSON.parse('@json($profitStats->pluck("expenses"))');
    const profitData = JSON.parse('@json($profitStats->pluck("net_profit"))');

    const chartData = {
        labels: labels,
        datasets: [{
            label: 'Income',
            data: incomeData,
            borderColor: 'rgb(40, 167, 69)',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            fill: true
        }, {
            label: 'Expenses',
            data: expenseData,
            borderColor: 'rgb(220, 53, 69)',
            backgroundColor: 'rgba(220, 53, 69, 0.1)',
            fill: true
        }, {
            label: 'Net Profit',
            data: profitData,
            borderColor: 'rgb(0, 123, 255)',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            fill: true
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection
