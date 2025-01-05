@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">ထီထိုးမှုများ</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                ထီထိုးမှုများ
            </div>
            <div>
                <form action="{{ route('admin.plays.approve-all') }}" method="POST" id="bulkApproveForm">
                    @csrf
                    <input type="hidden" name="play_ids" id="selectedPlayIds">
                    <button type="submit" class="btn btn-success" id="bulkApproveBtn" disabled>
                        <i class="fas fa-check"></i> ရွေးချယ်ထားသည်များကို အတည်ပြုမည်
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>အမည်</th>
                            <th>အမျိုးအစား</th>
                            <th>နံပါတ်</th>
                            <th>ငွေပမာဏ</th>
                            <th>အခြေအနေ</th>
                            <th>ထိုးသည့်အချိန်</th>
                            <th>လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plays as $play)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input play-checkbox" value="{{ $play->id }}" 
                                    {{ $play->status !== 'pending' ? 'disabled' : '' }}>
                            </td>
                            <td>{{ $play->user->name }}</td>
                            <td>{{ strtoupper($play->type) }}</td>
                            <td>{{ $play->number }}</td>
                            <td>{{ number_format($play->amount) }}</td>
                            <td>
                                <span class="badge bg-{{ $play->status_color }}">
                                    {{ $play->status_text }}
                                </span>
                            </td>
                            <td>{{ $play->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if($play->status === 'pending')
                                <form action="{{ route('admin.plays.update', $play) }}" method="POST" class="d-inline approve-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.plays.update', $play) }}" method="POST" class="d-inline reject-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $plays->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const playCheckboxes = document.querySelectorAll('.play-checkbox:not(:disabled)');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const selectedPlayIds = document.getElementById('selectedPlayIds');
    const bulkApproveForm = document.getElementById('bulkApproveForm');

    // Handle select all checkbox
    selectAll.addEventListener('change', function() {
        playCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkApproveButton();
    });

    // Handle individual checkboxes
    playCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkApproveButton();
            
            // Update select all checkbox
            const allChecked = [...playCheckboxes].every(cb => cb.checked);
            selectAll.checked = allChecked;
        });
    });

    // Update bulk approve button state and selected IDs
    function updateBulkApproveButton() {
        const selectedCheckboxes = document.querySelectorAll('.play-checkbox:checked');
        bulkApproveBtn.disabled = selectedCheckboxes.length === 0;
        
        // Convert to array and stringify
        const ids = [...selectedCheckboxes].map(cb => cb.value);
        selectedPlayIds.value = JSON.stringify(ids);
    }

    // Confirm bulk approve
    bulkApproveForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('ရွေးချယ်ထားသော ထီထိုးမှုများအားလုံးကို အတည်ပြုမှာ သေချာပါသလား?')) {
            this.submit();
        }
    });

    // Handle approve/reject forms
    document.querySelectorAll('.approve-form, .reject-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const action = this.classList.contains('approve-form') ? 'အတည်ပြု' : 'ငြင်းပယ်';
            if (confirm(`ဤထီထိုးမှုကို ${action}မှာ သေချာပါသလား?`)) {
                this.submit();
            }
        });
    });
});
</script>
@endpush
