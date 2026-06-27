@extends('layouts.app')

@section('title', __('customers.customers'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('customers.customers') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('customers.add_customer') }}
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
                            <th>{{ __('customers.name') }}</th>
                            <th>{{ __('customers.email') }}</th>
                            <th>{{ __('customers.phone') }}</th>
                            <th>{{ __('customers.city') }}</th>
                            <th>{{ __('customers.balance') }}</th>
                            <th>{{ __('auth.status') }}</th>
                            <th>{{ __('customers.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->city }}</td>
                                <td>{{ number_format($customer->total_balance, 2) }}</td>
                                <td>
                                    <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $customer->is_active ? __('auth.active') : __('auth.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
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
                                <td colspan="7" class="text-center text-muted">{{ __('messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
