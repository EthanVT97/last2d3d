@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Make a Deposit</h5>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('deposit.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (MMK)</label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" value="{{ old('amount') }}" 
                                   min="1000" max="1000000" required>
                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Minimum: 1,000 MMK, Maximum: 1,000,000 MMK</div>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                @foreach($paymentMethods as $value => $label)
                                    <option value="{{ $value }}" {{ old('payment_method') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="transaction_id" class="form-label">Transaction ID</label>
                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" 
                                   id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}" 
                                   required>
                            @error('transaction_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Enter the transaction ID from your payment provider</div>
                        </div>

                        <div class="payment-info mb-4">
                            <h6 class="mb-3">Payment Information</h6>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">KBZ Pay</h6>
                                            <p class="mb-0 text-muted">09-123456789</p>
                                        </div>
                                        <img src="{{ asset('images/kbzpay.png') }}" alt="KBZ Pay" height="40">
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Wave Money</h6>
                                            <p class="mb-0 text-muted">09-987654321</p>
                                        </div>
                                        <img src="{{ asset('images/wave.png') }}" alt="Wave Money" height="40">
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">CB Pay</h6>
                                            <p class="mb-0 text-muted">09-456789123</p>
                                        </div>
                                        <img src="{{ asset('images/cbpay.png') }}" alt="CB Pay" height="40">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading">Important Instructions:</h6>
                            <ol class="mb-0">
                                <li>Choose your preferred payment method</li>
                                <li>Send the amount to the corresponding account number</li>
                                <li>Copy the transaction ID from your payment app</li>
                                <li>Submit the form and wait for confirmation</li>
                            </ol>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Submit Deposit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .payment-info img {
        object-fit: contain;
    }
    .list-group-item {
        transition: background-color 0.2s ease;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
