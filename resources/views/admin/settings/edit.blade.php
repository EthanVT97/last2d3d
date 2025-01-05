@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-2"></i>{{ strtoupper($type) }} ထီ သတ်မှတ်ချက်များ
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update.lottery', $type) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">အနည်းဆုံး ထိုးကြေး</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="value[min_amount]" 
                                    value="{{ $settings->value['min_amount'] ?? 100 }}" required>
                                <span class="input-group-text">ကျပ်</span>
                            </div>
                            @error('value.min_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">အများဆုံး ထိုးကြေး</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="value[max_amount]" 
                                    value="{{ $settings->value['max_amount'] ?? 50000 }}" required>
                                <span class="input-group-text">ကျပ်</span>
                            </div>
                            @error('value.max_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="value[is_active]" 
                                    value="1" {{ ($settings->value['is_active'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label">ထီထိုးခွင့်ပြုမည်</label>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>မလုပ်တော့ပါ
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>သိမ်းမည်
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
