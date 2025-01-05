@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Edit Agent Account</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-edit me-1"></i>
                    Edit Agent Details
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.agents.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="mb-3">
                            <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                            <input type="number" class="form-control @error('commission_rate') is-invalid @enderror" 
                                   id="commission_rate" name="commission_rate" 
                                   value="{{ old('commission_rate', $user->commission_rate) }}" 
                                   min="0" max="100" step="0.1" required>
                            @error('commission_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.agents.show', $user) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Details
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Agent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Current Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Current Status
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold">Status:</label>
                        <p class="mb-2">
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </p>
                        <form action="{{ route('admin.agents.toggle-status', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-{{ $user->status === 'active' ? 'warning' : 'success' }} btn-sm" 
                                    onclick="return confirm(`Are you sure you want to ${document.querySelector('button').classList.contains('btn-warning') ? 'deactivate' : 'activate'} this agent?`)">
                                <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }} me-1"></i>
                                {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }} Agent
                            </button>
                        </form>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Current Commission Balance:</label>
                        <p class="mb-0">${{ number_format($user->commission_balance, 2) }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="fw-bold">Member Since:</label>
                        <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Danger Zone
                </div>
                <div class="card-body">
                    <p class="text-danger mb-3">Deleting this agent account will:</p>
                    <ul class="text-danger mb-3">
                        <li>Remove all agent access</li>
                        <li>Keep referral relationships</li>
                        <li>Maintain commission history</li>
                    </ul>
                    <form action="{{ route('admin.agents.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to delete this agent? This action cannot be undone.')">
                            <i class="fas fa-trash me-1"></i>
                            Delete Agent Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
