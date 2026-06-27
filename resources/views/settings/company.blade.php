@extends('layouts.app')

@section('title', __('settings.company_settings'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>{{ __('settings.company_settings') }}</h1>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">{{ $company->name ?? __('settings.general_info') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.company.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('company.name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $company->name ?? '') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name_ar" class="form-label">{{ __('company.name_ar') }}</label>
                            <input type="text" class="form-control @error('name_ar') is-invalid @enderror"
                                   id="name_ar" name="name_ar" value="{{ old('name_ar', $company->name_ar ?? '') }}" required>
                            @error('name_ar')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('company.email') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $company->email ?? '') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('company.phone') }}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $company->phone ?? '') }}" required>
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="address" class="form-label">{{ __('company.address') }}</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" required>{{ old('address', $company->address ?? '') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="address_ar" class="form-label">{{ __('company.address_ar') }}</label>
                            <textarea class="form-control @error('address_ar') is-invalid @enderror"
                                      id="address_ar" name="address_ar" required>{{ old('address_ar', $company->address_ar ?? '') }}</textarea>
                            @error('address_ar')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="city" class="form-label">{{ __('company.city') }}</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                   id="city" name="city" value="{{ old('city', $company->city ?? '') }}" required>
                            @error('city')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="city_ar" class="form-label">{{ __('company.city_ar') }}</label>
                            <input type="text" class="form-control @error('city_ar') is-invalid @enderror"
                                   id="city_ar" name="city_ar" value="{{ old('city_ar', $company->city_ar ?? '') }}" required>
                            @error('city_ar')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="country" class="form-label">{{ __('company.country') }}</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror"
                                   id="country" name="country" value="{{ old('country', $company->country ?? '') }}" required>
                            @error('country')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tax_number" class="form-label">{{ __('company.tax_number') }}</label>
                            <input type="text" class="form-control @error('tax_number') is-invalid @enderror"
                                   id="tax_number" name="tax_number" value="{{ old('tax_number', $company->tax_number ?? '') }}">
                            @error('tax_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="commercial_register" class="form-label">{{ __('company.commercial_register') }}</label>
                            <input type="text" class="form-control @error('commercial_register') is-invalid @enderror"
                                   id="commercial_register" name="commercial_register" value="{{ old('commercial_register', $company->commercial_register ?? '') }}">
                            @error('commercial_register')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="currency" class="form-label">{{ __('company.currency') }}</label>
                    <input type="text" class="form-control @error('currency') is-invalid @enderror"
                           id="currency" name="currency" value="{{ old('currency', $company->currency ?? '') }}" required>
                    @error('currency')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
