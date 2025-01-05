@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3 bg-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-0 font-weight-bold text-primary">ထွက်ဂဏန်း ပြင်ဆင်ရန်</h6>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>နောက်သို့
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.results.update', $result) }}" method="POST" id="resultForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label small text-muted">ထီအမျိုးအစား</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">ရွေးချယ်ပါ</option>
                                    <option value="2d" {{ $result->type === '2d' ? 'selected' : '' }}>2D</option>
                                    <option value="3d" {{ $result->type === '3d' ? 'selected' : '' }}>3D</option>
                                    <option value="thai" {{ $result->type === 'thai' ? 'selected' : '' }}>Thai Lottery</option>
                                    <option value="laos" {{ $result->type === 'laos' ? 'selected' : '' }}>Laos Lottery</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="draw_time" class="form-label small text-muted">ထွက်မည့်အချိန်</label>
                                <input type="datetime-local" class="form-control @error('draw_time') is-invalid @enderror" 
                                    id="draw_time" name="draw_time" value="{{ $result->draw_time->format('Y-m-d\TH:i') }}" required>
                                @error('draw_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-muted d-flex justify-content-between align-items-center">
                                <span>ထွက်ဂဏန်းများ</span>
                                <button type="button" class="btn btn-outline-success btn-sm add-number">
                                    <i class="fas fa-plus me-1"></i>နောက်ထပ်ထည့်ရန်
                                </button>
                            </label>
                            <div id="numbersContainer">
                                @foreach($result->numbers as $index => $number)
                                    <div class="number-field mb-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('numbers.'.$index) is-invalid @enderror" 
                                                name="numbers[]" value="{{ $number }}" required>
                                            <button type="button" class="btn btn-outline-danger remove-number" {{ $index === 0 && count($result->numbers) === 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        @error('numbers.'.$index)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="prize_amount" class="form-label small text-muted">ဆုငွေပမာဏ</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('prize_amount') is-invalid @enderror" 
                                    id="prize_amount" name="prize_amount" min="0" step="1" value="{{ $result->prize_amount }}" required>
                                <span class="input-group-text">Ks</span>
                            </div>
                            @error('prize_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                <i class="fas fa-times me-1"></i>မလုပ်တော့ပါ
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>သိမ်းမည်
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">လမ်းညွှန်ချက်များ</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">ထီအမျိုးအစားအလိုက် ဂဏန်းပုံစံများ</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>အမျိုးအစား</th>
                                        <th>ပုံစံ</th>
                                        <th>ဥပမာ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2D</td>
                                        <td>00-99</td>
                                        <td>25, 87</td>
                                    </tr>
                                    <tr>
                                        <td>3D</td>
                                        <td>000-999</td>
                                        <td>123, 456</td>
                                    </tr>
                                    <tr>
                                        <td>Thai</td>
                                        <td>ဂဏန်း ၆ လုံး</td>
                                        <td>123456</td>
                                    </tr>
                                    <tr>
                                        <td>Laos</td>
                                        <td>ဂဏန်း ၆ လုံး</td>
                                        <td>123456</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <h6 class="font-weight-bold">မှတ်ချက်များ</h6>
                        <ul class="small text-muted ps-3">
                            <li>ထွက်မည့်အချိန်ကို မှန်ကန်စွာ ရွေးချယ်ပါ။</li>
                            <li>ဂဏန်းများကို သတ်မှတ်ထားသည့် ပုံစံအတိုင်း ထည့်သွင်းပါ။</li>
                            <li>ဆုငွေပမာဏကို ကျပ်ဖြင့် ထည့်သွင်းပါ။</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.number-field .input-group-text {
    background: none;
    border-left: 0;
}

.number-field .form-control:focus + .input-group-text {
    border-color: #86b7fe;
}

.number-field .btn-outline-danger {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.number-field .form-control {
    border-right: 0;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('resultForm');
    const numbersContainer = document.getElementById('numbersContainer');

    // Add number field
    document.querySelector('.add-number').addEventListener('click', function() {
        const newField = document.createElement('div');
        newField.className = 'number-field mb-2';
        newField.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control" name="numbers[]" required>
                <button type="button" class="btn btn-outline-danger remove-number">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        numbersContainer.appendChild(newField);
        updateRemoveButtons();
    });

    // Remove number field
    numbersContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-number')) {
            e.target.closest('.number-field').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const fields = numbersContainer.querySelectorAll('.number-field');
        fields.forEach((field, index) => {
            const btn = field.querySelector('.remove-number');
            btn.disabled = index === 0 && fields.length === 1;
        });
    }

    // Update number fields based on lottery type
    document.getElementById('type').addEventListener('change', function() {
        const fields = numbersContainer.querySelectorAll('input');
        
        // Remove all fields except the first one
        const firstField = fields[0];
        while (numbersContainer.children.length > 1) {
            numbersContainer.lastChild.remove();
        }

        // Reset the first field
        firstField.value = '';

        // Update placeholder and pattern based on type
        switch (this.value) {
            case '2d':
                firstField.placeholder = '2 ဂဏန်း (00-99)';
                firstField.pattern = '[0-9]{2}';
                break;
            case '3d':
                firstField.placeholder = '3 ဂဏန်း (000-999)';
                firstField.pattern = '[0-9]{3}';
                break;
            case 'thai':
            case 'laos':
                firstField.placeholder = 'ဂဏန်း ၆ လုံး';
                firstField.pattern = '[0-9]{6}';
                break;
            default:
                firstField.placeholder = '';
                firstField.pattern = '';
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        const type = document.getElementById('type').value;
        const numbers = Array.from(document.querySelectorAll('input[name="numbers[]"]')).map(input => input.value);
        
        let isValid = true;
        let errorMessage = '';

        switch (type) {
            case '2d':
                isValid = numbers.every(num => /^[0-9]{2}$/.test(num));
                errorMessage = 'ဂဏန်း ၂ လုံးသာ ထည့်သွင်းပါ (00-99)';
                break;
            case '3d':
                isValid = numbers.every(num => /^[0-9]{3}$/.test(num));
                errorMessage = 'ဂဏန်း ၃ လုံးသာ ထည့်သွင်းပါ (000-999)';
                break;
            case 'thai':
            case 'laos':
                isValid = numbers.every(num => /^[0-9]{6}$/.test(num));
                errorMessage = 'ဂဏန်း ၆ လုံး ထည့်သွင်းပါ';
                break;
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
        }
    });
});
</script>
@endpush
@endsection
