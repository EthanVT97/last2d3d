@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>ဝင်ငွေအစီရင်ခံစာ</h1>
        <div>
            <form action="{{ route('admin.reports.revenue') }}" method="GET" class="d-flex gap-2">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                <button type="submit" class="btn btn-primary">ကြည့်ရှုမည်</button>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">စုစုပေါင်းဝင်ငွေ</h5>
                    <h2>{{ number_format($stats['total_revenue']) }} MMK</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">အသားတင်အမြတ်</h5>
                    <h2>{{ number_format($stats['net_profit']) }} MMK</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">စုစုပေါင်းထီထိုးငွေ</h5>
                    <h2>{{ number_format($stats['total_bets']) }} MMK</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">ဆုကြေးပေးငွေ</h5>
                    <h2>{{ number_format($stats['total_payouts']) }} MMK</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <canvas id="revenueDetailChart"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ရက်စွဲ</th>
                            <th>ဝင်ငွေ</th>
                            <th>ထွက်ငွေ</th>
                            <th>အသားတင်အမြတ်</th>
                            <th>ထီထိုးအရေအတွက်</th>
                            <th>အနိုင်ရအရေအတွက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daily_stats as $stat)
                            <tr>
                                <td>{{ $stat->date }}</td>
                                <td>{{ number_format($stat->revenue) }} MMK</td>
                                <td>{{ number_format($stat->expenses) }} MMK</td>
                                <td>{{ number_format($stat->profit) }} MMK</td>
                                <td>{{ number_format($stat->total_plays) }}</td>
                                <td>{{ number_format($stat->total_wins) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $daily_stats->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('revenueDetailChart'), {
        type: 'line',
        data: {
            labels: {!! Js::from($chart['labels']) !!},
            datasets: [{
                label: 'ဝင်ငွေ',
                data: {!! Js::from($chart['revenue']) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'အသားတင်အမြတ်',
                data: {!! Js::from($chart['profit']) !!},
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' Ks';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toLocaleString() + ' Ks';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
