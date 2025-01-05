@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">အစီရင်ခံစာများ</h1>

    <div class="row">
        <!-- Revenue Report -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ဝင်ငွေအစီရင်ခံစာ</h5>
                    <a href="{{ route('admin.reports.revenue') }}" class="btn btn-sm btn-primary">အပြည့်အစုံကြည့်ရန်</a>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Lottery Report -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ထီထိုးမှုအစီရင်ခံစာ</h5>
                    <a href="{{ route('admin.reports.lottery') }}" class="btn btn-sm btn-primary">အပြည့်အစုံကြည့်ရန်</a>
                </div>
                <div class="card-body">
                    <canvas id="lotteryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- User Report -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">အသုံးပြုသူအစီရင်ခံစာ</h5>
                    <a href="{{ route('admin.reports.users') }}" class="btn btn-sm btn-primary">အပြည့်အစုံကြည့်ရန်</a>
                </div>
                <div class="card-body">
                    <canvas id="userChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Plays Report -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ကစားမှုများ</h5>
                    <a href="{{ route('admin.reports.plays') }}" class="btn btn-sm btn-primary">အပြည့်အစုံကြည့်ရန်</a>
                </div>
                <div class="card-body">
                    <canvas id="playChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: {!! Js::from($revenueChart['labels']) !!},
            datasets: [{
                label: 'ဝင်ငွေ',
                data: {!! Js::from($revenueChart['data']) !!},
                borderColor: 'rgb(75, 192, 192)',
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

    // Lottery Chart
    new Chart(document.getElementById('lotteryChart'), {
        type: 'bar',
        data: {
            labels: {!! Js::from($lotteryChart['labels']) !!},
            datasets: [{
                label: 'ထွက်ရှိမှု',
                data: {!! Js::from($lotteryChart['data']) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
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

    // User Chart
    new Chart(document.getElementById('userChart'), {
        type: 'line',
        data: {
            labels: {!! Js::from($userChart['labels']) !!},
            datasets: [{
                label: 'အသုံးပြုသူများ',
                data: {!! Js::from($userChart['data']) !!},
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

    // Play Chart
    new Chart(document.getElementById('playChart'), {
        type: 'bar',
        data: {
            labels: {!! Js::from($playChart['labels']) !!},
            datasets: [{
                label: 'ကစားမှုများ',
                data: {!! Js::from($playChart['data']) !!},
                backgroundColor: 'rgba(153, 102, 255, 0.5)',
                borderColor: 'rgb(153, 102, 255)',
                borderWidth: 1
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
