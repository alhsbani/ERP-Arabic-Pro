@extends('layouts.app')

@section('title', __('payroll.payroll'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('payroll.payroll') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('payroll.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('payroll.create_payroll') }}
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
                            <th>{{ __('payroll.employee') }}</th>
                            <th>{{ __('payroll.payroll_date') }}</th>
                            <th>{{ __('payroll.salary') }}</th>
                            <th>{{ __('payroll.allowances') }}</th>
                            <th>{{ __('payroll.deductions') }}</th>
                            <th>{{ __('payroll.net_salary') }}</th>
                            <th>{{ __('payroll.status') }}</th>
                            <th>{{ __('payroll.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->employee->full_name }}</td>
                                <td>{{ $payroll->payroll_date->format('Y-m-d') }}</td>
                                <td>{{ number_format($payroll->salary, 2) }}</td>
                                <td>{{ number_format($payroll->total_allowances, 2) }}</td>
                                <td>{{ number_format($payroll->total_deductions, 2) }}</td>
                                <td><strong>{{ number_format($payroll->net_salary, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $payroll->status == 'paid' ? 'success' : 'warning' }}">
                                        {{ __('payroll.status_' . $payroll->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">{{ __('messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $payrolls->links() }}
        </div>
    </div>
</div>
@endsection
