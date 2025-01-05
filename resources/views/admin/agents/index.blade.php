@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Agent Management</h1>
        <a href="{{ route('admin.agents.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Agent
        </a>
    </div>

    <!-- Agent Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-2">{{ number_format($stats['total_agents']) }}</h4>
                    <div>Total Agents</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-2">{{ number_format($stats['active_agents']) }}</h4>
                    <div>Active Agents</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-2">{{ number_format($stats['total_referrals']) }}</h4>
                    <div>Total Referrals</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-2">{{ number_format($stats['total_commission'], 2) }} MMK</h4>
                    <div>Total Commission</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.agents.index') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or phone..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Agents Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Agent List
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Referrals</th>
                            <th>Total Plays</th>
                            <th>Commission Rate</th>
                            <th>Commission Balance</th>
                            <th>Points</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agents as $agent)
                        <tr>
                            <td>{{ $agent->name }}</td>
                            <td>{{ $agent->phone }}</td>
                            <td>
                                <span class="badge bg-{{ $agent->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($agent->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($agent->referrals_count) }}</td>
                            <td>{{ number_format($agent->plays_count) }}</td>
                            <td>{{ number_format($agent->commission_rate) }}%</td>
                            <td>{{ number_format($agent->commission_balance, 2) }} MMK</td>
                            <td>{{ number_format($agent->points, 2) }} MMK</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.agents.show', $agent) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.agents.points', $agent) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-coins"></i> Points
                                    </a>
                                    <a href="{{ route('admin.agents.edit', $agent) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.agents.toggle-status', $agent) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $agent->status === 'active' ? 'btn-warning' : 'btn-success' }}" 
                                                onclick="return confirm(`Are you sure you want to ${this.classList.contains('btn-warning') ? 'deactivate' : 'activate'} this agent?`)">
                                            <i class="fas {{ $agent->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this agent?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No agents found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $agents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
