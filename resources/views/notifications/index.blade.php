@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('အကြောင်းကြားစာများ') }}</span>
                    @if($notifications->where('read_at', null)->count() > 0)
                        <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary">
                                {{ __('အားလုံးဖတ်ရှုပြီးအဖြစ်သတ်မှတ်ရန်') }}
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    @forelse($notifications as $notification)
                        <div class="notification-item mb-3 p-3 border rounded {{ $notification->read_at ? 'bg-light' : 'bg-white border-primary' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1">{{ $notification->title }}</h5>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                    <small class="text-muted">
                                        {{ $notification->created_at->diffForHumans() }}
                                        @if($notification->read_at)
                                            · <span class="text-success">ဖတ်ရှုပြီး</span>
                                        @endif
                                    </small>
                                </div>
                                <div class="d-flex">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification) }}" method="POST" class="me-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                {{ __('ဖတ်ရှုပြီး') }}
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('သေချာပါသလား?')">
                                            {{ __('ဖျက်ရန်') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($notification->data)
                                <div class="mt-2 p-2 bg-light rounded">
                                    <pre class="mb-0"><code>{{ json_encode($notification->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="mb-0">{{ __('အကြောင်းကြားစာ မရှိသေးပါ') }}</p>
                        </div>
                    @endforelse

                    <div class="d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
