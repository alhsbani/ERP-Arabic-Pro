@extends('layouts.app')

@section('title', __('dashboard.dashboard'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>{{ __('dashboard.dashboard') }}</h1>
            <p class="text-muted">{{ __('messages.welcome_to_erp', ['name' => Auth::user()->name]) }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.total_users') }}</h5>
                    <h2 class="card-text">{{ App\Models\User::count() }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.active_roles') }}</h5>
                    <h2 class="card-text">{{ App\Models\Role::where('is_active', true)->count() }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.total_companies') }}</h5>
                    <h2 class="card-text">{{ App\Models\Company::count() }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">{{ __('dashboard.active_users') }}</h5>
                    <h2 class="card-text">{{ App\Models\User::where('is_active', true)->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ __('dashboard.recent_users') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('auth.name') }}</th>
                                    <th>{{ __('auth.email') }}</th>
                                    <th>{{ __('auth.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(App\Models\User::latest()->take(5)->get() as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->is_active ? __('auth.active') : __('auth.inactive') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">{{ __('messages.no_data') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ __('dashboard.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('users.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-plus"></i> {{ __('dashboard.add_user') }}
                        </a>
                        <a href="{{ route('roles.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shield-alt"></i> {{ __('dashboard.add_role') }}
                        </a>
                        <a href="{{ route('settings.company') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog"></i> {{ __('dashboard.company_settings') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
