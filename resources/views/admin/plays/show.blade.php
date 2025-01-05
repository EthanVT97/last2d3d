@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">ထီထိုးမှု အသေးစိတ်</h1>
        <a href="{{ route('admin.plays.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> နောက်သို့
        </a>
    </div>

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

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    အခြေခံအချက်အလက်များ
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">ထိုးသူ</th>
                            <td>
                                <a href="{{ route('admin.users.show', $play->user) }}" class="text-decoration-none">
                                    {{ $play->user->name }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>အမျိုးအစား</th>
                            <td>
                                <span class="badge bg-primary">
                                    {{ strtoupper($play->type) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>နံပါတ်</th>
                            <td class="fw-bold">{{ $play->number }}</td>
                        </tr>
                        <tr>
                            <th>ငွေပမာဏ</th>
                            <td>{{ number_format($play->amount) }} ကျပ်</td>
                        </tr>
                        <tr>
                            <th>အခြေအနေ</th>
                            <td>
                                <span class="badge bg-{{ $play->status_color }}">
                                    {{ $play->status_text }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>ထိုးသည့်အချိန်</th>
                            <td>{{ $play->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    အခြေအနေပြောင်းလဲရန်
                </div>
                <div class="card-body">
                    @if($play->status === 'pending')
                    <form action="{{ route('admin.plays.update', $play) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">အခြေအနေ</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $play->status === 'pending' ? 'selected' : '' }}>စောင့်ဆိုင်းဆဲ</option>
                                <option value="approved" {{ $play->status === 'approved' ? 'selected' : '' }}>အတည်ပြုမည်</option>
                                <option value="rejected" {{ $play->status === 'rejected' ? 'selected' : '' }}>ငြင်းပယ်မည်</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">မှတ်ချက်</label>
                            <textarea name="admin_note" class="form-control" rows="3">{{ $play->admin_note }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> သိမ်းမည်
                        </button>
                    </form>
                    @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        ဤထီထိုးမှုကို {{ $play->status_text }} ပြီးဖြစ်ပါသည်။
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
