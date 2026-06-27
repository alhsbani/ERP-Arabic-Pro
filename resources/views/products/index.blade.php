@extends('layouts.app')

@section('title', __('products.products'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('products.products') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('products.add_product') }}
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
                            <th>{{ __('products.name') }}</th>
                            <th>{{ __('products.sku') }}</th>
                            <th>{{ __('products.price') }}</th>
                            <th>{{ __('products.quantity') }}</th>
                            <th>{{ __('products.category') }}</th>
                            <th>{{ __('auth.status') }}</th>
                            <th>{{ __('products.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>
                                    <span class="badge {{ $product->quantity > 0 ? 'bg-success' : 'bg-warning' }}">
                                        {{ $product->quantity }}
                                    </span>
                                </td>
                                <td>{{ $product->category?->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->is_active ? __('auth.active') : __('auth.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
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

            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
