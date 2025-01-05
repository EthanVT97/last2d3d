@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">အသုံးပြုသူများ</h1>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            အသုံးပြုသူများ စာရင်း
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>အမည်</th>
                            <th>အီးမေးလ်</th>
                            <th>ဖုန်းနံပါတ်</th>
                            <th>လက်ကျန်ငွေ</th>
                            <th>အခြေအနေ</th>
                            <th>စတင်အသုံးပြုသည့်ရက်</th>
                            <th>လုပ်ဆောင်ချက်များ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ number_format($user->balance) }} ကျပ်</td>
                            <td>
                                <span class="badge bg-{{ $user->is_banned ? 'danger' : 'success' }}">
                                    {{ $user->is_banned ? 'ပိတ်ပင်ထားသည်' : 'အသုံးပြုနိုင်သည်' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($user->is_banned)
                                    <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('ဤအသုံးပြုသူအား ဖျက်ရန် သေချာပါသလား?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
