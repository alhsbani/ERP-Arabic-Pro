@extends('layouts.app')

@section('title', __('audit.audit_logs'))

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">{{ __('audit.audit_logs') }}</h1>

    <div class="card">
        <div class="card-header">
            <h5>{{ __('audit.system_activity') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('audit.user') }}</th>
                            <th>{{ __('audit.action') }}</th>
                            <th>{{ __('audit.model_type') }}</th>
                            <th>{{ __('audit.ip_address') }}</th>
                            <th>{{ __('audit.timestamp') }}</th>
                            <th>{{ __('audit.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td><code>{{ $log->action }}</code></td>
                                <td>{{ $log->model_type }}</td>
                                <td><code>{{ $log->ip_address }}</code></td>
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('audit.show', $log) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
