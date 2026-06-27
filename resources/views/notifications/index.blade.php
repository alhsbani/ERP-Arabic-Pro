@extends('layouts.app')

@section('title', __('notifications.notifications'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('notifications.notifications') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('notifications.mark-all-as-read') }}" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('mark-all-form').submit();">
                <i class="fas fa-check-double"></i> {{ __('notifications.mark_all_as_read') }}
            </a>
            <form id="mark-all-form" action="{{ route('notifications.mark-all-as-read') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="list-group">
                @forelse($notifications as $notification)
                    <a href="{{ $notification->action_url ?? '#' }}" class="list-group-item list-group-item-action {{ $notification->isRead() ? '' : 'active' }}">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                <p class="mb-1">{{ $notification->message }}</p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="ms-auto">
                                @if(!$notification->isRead())
                                    <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            {{ __('notifications.mark_as_read') }}
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="alert alert-info">
                        {{ __('messages.no_notifications') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{ $notifications->links() }}
</div>
@endsection
