@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Users</div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Your Points Balance: {{ number_format(auth()->user()->points, 2) }} MMK</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Current Points</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ number_format($user->points, 2) }} MMK</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#transferModal{{ $user->id }}">
                                            Transfer Points
                                        </button>

                                        <!-- Transfer Modal -->
                                        <div class="modal fade" id="transferModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="transferModalLabel{{ $user->id }}">Transfer Points to {{ $user->name }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('agent.transfer.points') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                            <div class="form-group">
                                                                <label for="points{{ $user->id }}">Points Amount</label>
                                                                <input type="number" class="form-control" id="points{{ $user->id }}" name="points" 
                                                                    min="1" max="{{ auth()->user()->points }}" step="0.01" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="note{{ $user->id }}">Note (Optional)</label>
                                                                <input type="text" class="form-control" id="note{{ $user->id }}" name="note">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Transfer Points</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
