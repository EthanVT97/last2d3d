@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ $user->name }} ၏ ထိုးထားသည်များ</h1>
            <p class="text-muted">{{ $user->email }}</p>
        </div>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>နောက်သို့
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">စုစုပေါင်း ထိုးထားသည်</h5>
                    <h2>{{ number_format($stats['total_plays']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">အနိုင်ရသည်</h5>
                    <h2>{{ number_format($stats['total_wins']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">အရှုံးရသည်</h5>
                    <h2>{{ number_format($stats['total_losses']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">စောင့်ဆိုင်းဆဲ</h5>
                    <h2>{{ number_format($stats['total_pending']) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form class="d-flex gap-2">
                <select name="type" class="form-select w-auto">
                    <option value="">အားလုံး</option>
                    <option value="2d" {{ request('type') === '2d' ? 'selected' : '' }}>2D</option>
                    <option value="3d" {{ request('type') === '3d' ? 'selected' : '' }}>3D</option>
                    <option value="thai" {{ request('type') === 'thai' ? 'selected' : '' }}>Thai</option>
                    <option value="laos" {{ request('type') === 'laos' ? 'selected' : '' }}>Laos</option>
                </select>
                <select name="status" class="form-select w-auto">
                    <option value="">အခြေအနေအားလုံး</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>စောင့်ဆိုင်းဆဲ</option>
                    <option value="won" {{ request('status') === 'won' ? 'selected' : '' }}>အနိုင်ရ</option>
                    <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>အရှုံး</option>
                </select>
                <input type="date" name="date" class="form-control w-auto" value="{{ request('date') }}">
                <button type="submit" class="btn btn-primary">စစ်ထုတ်မည်</button>
                <a href="{{ route('admin.users.plays', $user->id) }}" class="btn btn-secondary">ပြန်လည်စတင်မည်</a>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ရက်စွဲ</th>
                            <th>အမျိုးအစား</th>
                            <th>နံပါတ်</th>
                            <th>ငွေပမာဏ</th>
                            <th>အခြေအနေ</th>
                            <th>ဆုငွေ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plays as $play)
                        <tr>
                            <td>{{ $play->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ strtoupper($play->type) }}</td>
                            <td>{{ $play->numbers }}</td>
                            <td>{{ number_format($play->amount) }} ကျပ်</td>
                            <td>
                                <span class="badge {{ $play->status === 'won' ? 'bg-success' : ($play->status === 'lost' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ $play->status === 'won' ? 'အနိုင်' : ($play->status === 'lost' ? 'အရှုံး' : 'စောင့်ဆိုင်းဆဲ') }}
                                </span>
                            </td>
                            <td>{{ $play->prize_amount ? number_format($play->prize_amount) . ' ကျပ်' : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $plays->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
