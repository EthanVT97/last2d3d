@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-auto">
            <h1 class="h3 mb-0 text-gray-800">ထွက်ဂဏန်းများ စီမံခန့်ခွဲခြင်း</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.results.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>အသစ်ထည့်ရန်
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header py-3 bg-white">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <select class="form-select form-select-sm" id="typeFilter">
                        <option value="">အားလုံး</option>
                        <option value="2d">2D</option>
                        <option value="3d">3D</option>
                        <option value="thai">Thai</option>
                        <option value="laos">Laos</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">အခြေအနေအားလုံး</option>
                        <option value="pending">စောင့်ဆိုင်းဆဲ</option>
                        <option value="completed">ပြီးဆုံး</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="searchInput" placeholder="ရှာဖွေရန်...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">ရက်စွဲ</th>
                            <th>အမျိုးအစား</th>
                            <th>ထွက်ဂဏန်းများ</th>
                            <th>ဆုငွေပမာဏ</th>
                            <th>အခြေအနေ</th>
                            <th>ထည့်သွင်းသူ</th>
                            <th class="text-end px-4">လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $result)
                            <tr>
                                <td class="px-4">
                                    <div class="small text-muted">{{ $result->draw_time->format('Y-m-d') }}</div>
                                    <div class="fw-bold">{{ $result->draw_time->format('H:i') }}</div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill 
                                        @if($result->type === '2d') bg-primary
                                        @elseif($result->type === '3d') bg-success
                                        @elseif($result->type === 'thai') bg-info
                                        @else bg-warning
                                        @endif">
                                        {{ strtoupper($result->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ implode(', ', $result->numbers) }}</span>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">{{ number_format($result->prize_amount) }} Ks</span>
                                </td>
                                <td>
                                    @if($result->status === 'pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>စောင့်ဆိုင်းဆဲ
                                        </span>
                                    @elseif($result->status === 'completed')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>ပြီးဆုံး
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $result->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <div class="avatar-title rounded-circle bg-primary">
                                                {{ substr($result->creator->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small fw-bold">{{ $result->creator->name }}</div>
                                            <div class="small text-muted">{{ $result->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end px-4">
                                    <a href="{{ route('admin.results.edit', $result) }}" 
                                        class="btn btn-sm btn-outline-primary me-2" 
                                        data-bs-toggle="tooltip" 
                                        title="ပြင်ဆင်ရန်">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.results.destroy', $result) }}" 
                                        method="POST" 
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="tooltip" 
                                            title="ဖျက်ရန်">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p class="mb-0">ထွက်ဂဏန်း မရှိသေးပါ</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($results->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-center">
                    {{ $results->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.avatar {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm {
    width: 24px;
    height: 24px;
    font-size: 12px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

.delete-form button:hover {
    transform: scale(1.05);
    transition: transform 0.2s;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('သေချာပါသလား?')) {
                this.submit();
            }
        });
    });

    // Filter functionality
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const tbody = document.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');

    function filterTable() {
        const type = typeFilter.value.toLowerCase();
        const status = statusFilter.value.toLowerCase();
        const search = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const typeCell = row.querySelector('td:nth-child(2)');
            const statusCell = row.querySelector('td:nth-child(5)');
            const searchableContent = row.textContent.toLowerCase();

            const typeMatch = !type || (typeCell && typeCell.textContent.toLowerCase().includes(type));
            const statusMatch = !status || (statusCell && statusCell.textContent.toLowerCase().includes(status));
            const searchMatch = !search || searchableContent.includes(search);

            row.style.display = typeMatch && statusMatch && searchMatch ? '' : 'none';
        });
    }

    typeFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
    searchInput.addEventListener('input', filterTable);
});
</script>
@endpush
@endsection
