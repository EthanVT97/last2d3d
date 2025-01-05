@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Create Agent Account</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-plus me-1"></i>
                    Agent Details
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.agents.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="mb-3">
                            <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                            <input type="number" class="form-control @error('commission_rate') is-invalid @enderror" 
                                   id="commission_rate" name="commission_rate" 
                                   value="{{ old('commission_rate', 10) }}" min="0" max="100" step="0.1" required>
                            @error('commission_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">Initial Points</label>
                            <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                   id="points" name="points" value="{{ old('points', 0) }}" 
                                   min="0" step="0.01" required>
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Agent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Information
                </div>
                <div class="card-body">
                    <p class="mb-2">Agent accounts have the following features:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i> Can refer new users</li>
                        <li><i class="fas fa-check text-success me-2"></i> Earn commission on referral plays</li>
                        <li><i class="fas fa-check text-success me-2"></i> View their referral statistics</li>
                        <li><i class="fas fa-check text-success me-2"></i> Withdraw earned commissions</li>
                    </ul>
                    <hr>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Make sure to set an appropriate commission rate for the agent.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
