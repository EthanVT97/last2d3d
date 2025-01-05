@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('မိတ်ဆက်ကုဒ်နှင့် ညွှန်းဆိုမှုများ') }}</div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">စုစုပေါင်း ညွှန်းဆိုမှုများ</h5>
                                    <h2 class="mb-0">{{ $referrals->total() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">တက်ကြွသော ညွှန်းဆိုမှုများ</h5>
                                    <h2 class="mb-0">{{ $referrals->filter(fn($ref) => $ref->transactions->isNotEmpty())->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">စုစုပေါင်း ကော်မရှင်</h5>
                                    <h2 class="mb-0">{{ number_format(auth()->user()->commission_balance) }} MMK</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h5 class="alert-heading">သင်၏ မိတ်ဆက်ကုဒ်</h5>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="{{ auth()->user()->referral_code }}" id="referralCode" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('referralCode')">
                                <i class="fas fa-copy"></i> ကူးယူရန်
                            </button>
                        </div>
                        <p class="mb-0">
                            ဤကုဒ်ကို သင့်မိတ်ဆွေများအား မျှဝေပါ။ သူတို့ အကောင့်ဖွင့်ချိန်တွင် အသုံးပြုနိုင်ပါသည်။
                        </p>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>အမည်</th>
                                    <th>ဖုန်းနံပါတ်</th>
                                    <th>ငွေသွင်းမှုများ</th>
                                    <th>အကောင့်ဖွင့်ချိန်</th>
                                    <th>နောက်ဆုံးလှုပ်ရှားမှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referrals as $referral)
                                    <tr>
                                        <td>{{ $referral->name }}</td>
                                        <td>{{ $referral->phone }}</td>
                                        <td>{{ number_format($referral->transactions->sum('amount')) }} MMK</td>
                                        <td>{{ $referral->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $referral->last_activity_at ? $referral->last_activity_at->diffForHumans() : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">မိတ်ဆက်ထားသော အသုံးပြုသူမရှိသေးပါ</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $referrals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(elementId) {
    var copyText = document.getElementById(elementId);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    
    // Show a tooltip or alert
    alert("မိတ်ဆက်ကုဒ် ကူးယူပြီးပါပြီ");
}
</script>
@endpush
