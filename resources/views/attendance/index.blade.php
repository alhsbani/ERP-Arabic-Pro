@extends('layouts.app')

@section('title', __('attendance.attendance'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('attendance.attendance') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('attendance.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('attendance.record_attendance') }}
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
                            <th>{{ __('attendance.employee') }}</th>
                            <th>{{ __('attendance.date') }}</th>
                            <th>{{ __('attendance.check_in') }}</th>
                            <th>{{ __('attendance.check_out') }}</th>
                            <th>{{ __('attendance.status') }}</th>
                            <th>{{ __('attendance.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->employee->full_name }}</td>
                                <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                <td>{{ $attendance->check_in_time }}</td>
                                <td>{{ $attendance->check_out_time ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : 'warning' }}">
                                        {{ __('attendance.status_' . $attendance->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $attendance->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
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

            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
