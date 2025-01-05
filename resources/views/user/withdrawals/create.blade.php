@extends('layouts.user')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-money-bill-wave me-2"></i>ငွေထုတ်ယူရန်
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('user.withdrawals.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">ပမာဏ (အနည်းဆုံး 1,000 ကျပ်)</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                    name="amount" value="{{ old('amount') }}" min="1000" step="100" required>
                                <span class="input-group-text">MMK</span>
                            </div>
                            @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ငွေလက်ခံမည့် ဘဏ်/ဝန်ဆောင်မှု</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                name="payment_method" required>
                                <option value="">ရွေးချယ်ပါ</option>
                                <option value="kpay" {{ old('payment_method') === 'kpay' ? 'selected' : '' }}>KBZ Pay</option>
                                <option value="wavepay" {{ old('payment_method') === 'wavepay' ? 'selected' : '' }}>Wave Pay</option>
                                <option value="cbpay" {{ old('payment_method') === 'cbpay' ? 'selected' : '' }}>CB Pay</option>
                                <option value="ayapay" {{ old('payment_method') === 'ayapay' ? 'selected' : '' }}>AYA Pay</option>
                            </select>
                            @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">အကောင့်အမည်</label>
                            <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                name="account_name" value="{{ old('account_name') }}" required>
                            @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">အကောင့်နံပါတ်</label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                name="account_number" value="{{ old('account_number') }}" required>
                            @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            သင့်လက်ကျန်ငွေ: <strong>{{ number_format(auth()->user()->balance) }} MMK</strong>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>တောင်းဆိုရန်
                        </button>
                        <a href="{{ route('user.transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>နောက်သို့
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
