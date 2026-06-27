@extends('layouts.app')

@section('title', __('dashboard.dashboard'))

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">{{ __('dashboard.dashboard') }}</h1>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.total_revenue') }}</h5>
                    <h2>{{ number_format($totalRevenue, 2) }}</h2>
                    <small>{{ __('dashboard.this_month') }}</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.total_invoices') }}</h5>
                    <h2>{{ $totalInvoices }}</h2>
                    <small>{{ __('dashboard.this_month') }}</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.pending_invoices') }}</h5>
                    <h2>{{ $pendingInvoices }}</h2>
                    <small>{{ __('dashboard.awaiting_payment') }}</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.active_employees') }}</h5>
                    <h2>{{ $activeEmployees }} / {{ $totalEmployees }}</h2>
                    <small>{{ __('dashboard.staff_count') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Metrics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.total_customers') }}</h5>
                    <h2>{{ $totalCustomers }}</h2>
                    <small class="text-muted">{{ $activeCustomers }} {{ __('dashboard.active') }}</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.monthly_payroll') }}</h5>
                    <h2>{{ number_format($monthlyPayroll, 2) }}</h2>
                    <small class="text-muted">{{ __('dashboard.salary_expenses') }}</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('dashboard.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('invoices.create') }}" class="btn btn-sm btn-primary me-2">
                        <i class="fas fa-file-invoice"></i> {{ __('dashboard.new_invoice') }}
                    </a>
                    <a href="{{ route('customers.create') }}" class="btn btn-sm btn-success me-2">
                        <i class="fas fa-user-plus"></i> {{ __('dashboard.new_customer') }}
                    </a>
                    <a href="{{ route('employees.create') }}" class="btn btn-sm btn-info me-2">
                        <i class="fas fa-user-tie"></i> {{ __('dashboard.new_employee') }}
                    </a>
                    <a href="{{ route('products.create') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-box"></i> {{ __('dashboard.new_product') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Invoices -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('dashboard.recent_invoices') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('dashboard.invoice_number') }}</th>
                                    <th>{{ __('dashboard.customer') }}</th>
                                    <th>{{ __('dashboard.date') }}</th>
                                    <th>{{ __('dashboard.amount') }}</th>
                                    <th>{{ __('dashboard.status') }}</th>
                                    <th>{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $invoice)
                                    <tr>
                                        <td><code>{{ $invoice->invoice_number }}</code></td>
                                        <td>{{ $invoice->customer->name }}</td>
                                        <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                        <td>{{ number_format($invoice->total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'warning' }}">
                                                {{ __('invoices.status_' . $invoice->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
