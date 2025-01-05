@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Agent Points - {{ $user->name }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Current Points Balance</h5>
                                    <h2>{{ number_format($user->points, 2) }} MMK</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Add Points</h5>
                                    <form action="{{ route('admin.agents.points.add', $user) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="points">Points Amount</label>
                                            <input type="number" name="points" id="points" class="form-control" min="0" step="0.01" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="note">Note (Optional)</label>
                                            <input type="text" name="note" id="note" class="form-control">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Points</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4>Points Transaction History</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pointTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                                    <td>{{ number_format($transaction->amount, 2) }} MMK</td>
                                    <td>{{ $transaction->note }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $pointTransactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
