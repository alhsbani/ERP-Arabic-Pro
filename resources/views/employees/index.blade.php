@extends('layouts.app')

@section('title', __('employees.employees'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('employees.employees') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('employees.add_employee') }}
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
                            <th>{{ __('employees.employee_code') }}</th>
                            <th>{{ __('employees.name') }}</th>
                            <th>{{ __('employees.email') }}</th>
                            <th>{{ __('employees.department') }}</th>
                            <th>{{ __('employees.position') }}</th>
                            <th>{{ __('employees.salary') }}</th>
                            <th>{{ __('auth.status') }}</th>
                            <th>{{ __('employees.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td><code>{{ $employee->employee_code }}</code></td>
                                <td>{{ $employee->full_name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->department->name }}</td>
                                <td>{{ $employee->position->name }}</td>
                                <td>{{ number_format($employee->salary, 2) }}</td>
                                <td>
                                    <span class="badge {{ $employee->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $employee->is_active ? __('auth.active') : __('auth.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
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

            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection
