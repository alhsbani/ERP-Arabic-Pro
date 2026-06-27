@extends('layouts.app')

@section('title', __('leave.leave_requests'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('leave.leave_requests') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('leave.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('leave.request_leave') }}
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('leave.employee') }}</th>
                            <th>{{ __('leave.leave_type') }}</th>
                            <th>{{ __('leave.start_date') }}</th>
                            <th>{{ __('leave.end_date') }}</th>
                            <th>{{ __('leave.days') }}</th>
                            <th>{{ __('leave.status') }}</th>
                            <th>{{ __('leave.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $request)
                            <tr>
                                <td>{{ $request->employee->full_name }}</td>
                                <td>{{ $request->leaveType->name }}</td>
                                <td>{{ $request->start_date->format('Y-m-d') }}</td>
                                <td>{{ $request->end_date->format('Y-m-d') }}</td>
                                <td>{{ $request->days_count }}</td>
                                <td>
                                    <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ __('leave.status_' . $request->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($request->status == 'pending')
                                        <form action="{{ route('leave.approve', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                {{ __('leave.approve') }}
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                            {{ __('leave.reject') }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">{{ __('messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $leaveRequests->links() }}
        </div>
    </div>
</div>
@endsection
