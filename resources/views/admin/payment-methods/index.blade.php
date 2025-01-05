@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0 text-gray-800">
                    <i class="fas fa-money-bill-wave me-2"></i>ငွေပေးချေမှုနည်းလမ်းများ စီမံခန့်ခွဲခြင်း
                </h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">
                    <i class="fas fa-plus me-1"></i>အသစ်ထည့်ရန်
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>အမည်</th>
                                    <th>အမျိုးအစား</th>
                                    <th>ဖုန်းနံပါတ်</th>
                                    <th>အကောင့်အမည်</th>
                                    <th>အခြေအနေ</th>
                                    <th>လုပ်ဆောင်ချက်</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($methods as $method)
                                    <tr>
                                        <td class="fw-medium">{{ $method->name }}</td>
                                        <td>
                                            @php
                                                $typeIcons = [
                                                    'kpay' => 'fas fa-university',
                                                    'wavepay' => 'fas fa-mobile-alt',
                                                    'cbpay' => 'fab fa-bitcoin',
                                                    'ayapay' => 'fas fa-money-bill'
                                                ];
                                                $typeLabels = [
                                                    'kpay' => 'KBZ Pay',
                                                    'wavepay' => 'Wave Pay',
                                                    'cbpay' => 'CB Pay',
                                                    'ayapay' => 'AYA Pay'
                                                ];
                                            @endphp
                                            <i class="{{ $typeIcons[$method->type] ?? 'fas fa-money-bill' }} me-1"></i>
                                            {{ $typeLabels[$method->type] ?? $method->type }}
                                        </td>
                                        <td>{{ $method->phone }}</td>
                                        <td>{{ $method->account_name }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="statusSwitch{{ $method->id }}"
                                                    {{ $method->is_active ? 'checked' : '' }}
                                                    onchange="updateStatus({{ $method->id }}, this.checked)">
                                                <label class="form-check-label small" for="statusSwitch{{ $method->id }}">
                                                    {{ $method->is_active ? 'အသုံးပြုနိုင်သည်' : 'အသုံးပြု၍မရပါ' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-outline-primary btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editMethodModal{{ $method->id }}"
                                                    title="ပြင်ဆင်ရန်">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.payment-methods.destroy', $method) }}" 
                                                    method="POST" 
                                                    class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        title="ဖျက်ရန်">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle me-1"></i>ငွေပေးချေမှုနည်းလမ်း မရှိသေးပါ
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $methods->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Method Modal -->
<div class="modal fade" id="addMethodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-1"></i>ငွေပေးချေမှုနည်းလမ်းအသစ်ထည့်ရန်
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.payment-methods.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label small text-muted">အမည်</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">အမည်ထည့်သွင်းပါ</div>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label small text-muted">အမျိုးအစား</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">ရွေးချယ်ပါ</option>
                            <option value="kpay">KBZ Pay</option>
                            <option value="wavepay">Wave Pay</option>
                            <option value="cbpay">CB Pay</option>
                            <option value="ayapay">AYA Pay</option>
                        </select>
                        <div class="invalid-feedback">အမျိုးအစားရွေးချယ်ပါ</div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label small text-muted">ဖုန်းနံပါတ်</label>
                        <div class="input-group">
                            <span class="input-group-text">+95</span>
                            <input type="text" class="form-control" id="phone" 
                                name="phone" pattern="^9\d{8,9}$" required
                                placeholder="9xxxxxxxxx">
                        </div>
                        <div class="form-text">ဥပမာ - 9123456789</div>
                        <div class="invalid-feedback">မှန်ကန်သောဖုန်းနံပါတ်ထည့်သွင်းပါ</div>
                    </div>
                    <div class="mb-3">
                        <label for="account_name" class="form-label small text-muted">အကောင့်အမည်</label>
                        <input type="text" class="form-control" id="account_name" 
                            name="account_name" required>
                        <div class="invalid-feedback">အကောင့်အမည်ထည့်သွင်းပါ</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">အသုံးပြုနိုင်သည်</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>ပိတ်မည်
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>သိမ်းမည်
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($methods as $method)
    <!-- Edit Method Modal -->
    <div class="modal fade" id="editMethodModal{{ $method->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-1"></i>ငွေပေးချေမှုနည်းလမ်းပြင်ဆင်ရန်
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.payment-methods.update', $method) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name{{ $method->id }}" class="form-label small text-muted">အမည်</label>
                            <input type="text" class="form-control" id="name{{ $method->id }}" name="name" value="{{ $method->name }}" required>
                            <div class="invalid-feedback">အမည်ထည့်သွင်းပါ</div>
                        </div>
                        <div class="mb-3">
                            <label for="type{{ $method->id }}" class="form-label small text-muted">အမျိုးအစား</label>
                            <select class="form-select" id="type{{ $method->id }}" name="type" required>
                                <option value="">ရွေးချယ်ပါ</option>
                                <option value="kpay" {{ $method->type === 'kpay' ? 'selected' : '' }}>KBZ Pay</option>
                                <option value="wavepay" {{ $method->type === 'wavepay' ? 'selected' : '' }}>Wave Pay</option>
                                <option value="cbpay" {{ $method->type === 'cbpay' ? 'selected' : '' }}>CB Pay</option>
                                <option value="ayapay" {{ $method->type === 'ayapay' ? 'selected' : '' }}>AYA Pay</option>
                            </select>
                            <div class="invalid-feedback">အမျိုးအစားရွေးချယ်ပါ</div>
                        </div>
                        <div class="mb-3">
                            <label for="phone{{ $method->id }}" class="form-label small text-muted">ဖုန်းနံပါတ်</label>
                            <div class="input-group">
                                <span class="input-group-text">+95</span>
                                <input type="text" class="form-control" id="phone{{ $method->id }}" 
                                    name="phone" value="{{ $method->phone }}" 
                                    pattern="^9\d{8,9}$" required
                                    placeholder="9xxxxxxxxx">
                            </div>
                            <div class="form-text">ဥပမာ - 9123456789</div>
                            <div class="invalid-feedback">မှန်ကန်သောဖုန်းနံပါတ်ထည့်သွင်းပါ</div>
                        </div>
                        <div class="mb-3">
                            <label for="account_name{{ $method->id }}" class="form-label small text-muted">အကောင့်အမည်</label>
                            <input type="text" class="form-control" id="account_name{{ $method->id }}" 
                                name="account_name" value="{{ $method->account_name }}" required>
                            <div class="invalid-feedback">အကောင့်အမည်ထည့်သွင်းပါ</div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active{{ $method->id }}" 
                                    name="is_active" {{ $method->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active{{ $method->id }}">အသုံးပြုနိုင်သည်</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>ပိတ်မည်
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>သိမ်းမည်
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@push('styles')
<style>
.table td {
    vertical-align: middle;
}
.delete-form button {
    transition: all 0.2s;
}
.delete-form button:hover {
    transform: scale(1.05);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Phone number validation
    const phoneInputs = document.querySelectorAll('input[name="phone"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            e.target.value = value;
        });
    });

    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'သေချာပါသလား?',
                text: "ဖျက်လိုက်ပါက ပြန်ရနိုင်မည်မဟုတ်ပါ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ဖျက်မည်',
                cancelButtonText: 'မဖျက်တော့ပါ'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

function updateStatus(id, status) {
    // TODO: Implement status update via AJAX
    const label = document.querySelector(`label[for="statusSwitch${id}"]`);
    label.textContent = status ? 'အသုံးပြုနိုင်သည်' : 'အသုံးပြု၍မရပါ';
}
</script>
@endpush
@endsection
