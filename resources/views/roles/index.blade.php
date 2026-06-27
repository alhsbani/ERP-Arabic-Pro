@extends('layouts.app')

@section('title', __('roles.roles'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('roles.roles') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('roles.add_role') }}
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
                            <th>{{ __('roles.name') }}</th>
                            <th>{{ __('roles.name_ar') }}</th>
                            <th>{{ __('roles.description') }}</th>
                            <th>{{ __('auth.status') }}</th>
                            <th>{{ __('roles.users_count') }}</th>
                            <th>{{ __('roles.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->name_ar }}</td>
                                <td>{{ $role->description ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $role->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $role->is_active ? __('auth.active') : __('auth.inactive') }}
                                    </span>
                                </td>
                                <td>{{ $role->users->count() }}</td>
                                <td>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
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
                                <td colspan="6" class="text-center text-muted">{{ __('messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $roles->links() }}
        </div>
    </div>
</div>
@endsection
