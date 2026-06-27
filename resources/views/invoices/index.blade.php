@extends('layouts.app')

@section('title', __('invoices.invoices'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('invoices.invoices') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('invoices.create_invoice') }}
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
                            <th>{{ __('invoices.invoice_number') }}</th>
                            <th>{{ __('invoices.customer') }}</th>
                            <th>{{ __('invoices.invoice_date') }}</th>
                            <th>{{ __('invoices.total') }}</th>
                            <th>{{ __('invoices.paid') }}</th>
                            <th>{{ __('invoices.balance') }}</th>
                            <th>{{ __('invoices.status') }}</th>
                            <th>{{ __('invoices.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                <td>{{ $invoice->customer->name }}</td>
                                <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                <td>{{ number_format($invoice->total, 2) }}</td>
                                <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                                <td>{{ number_format($invoice->remaining_balance, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->is_overdue ? 'danger' : 'warning') }}">
                                        {{ __('invoices.status_' . $invoice->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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

            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection
