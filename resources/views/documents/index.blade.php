@extends('layouts.app')

@section('title', __('documents.documents'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ __('documents.documents') }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('documents.create') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> {{ __('documents.upload_document') }}
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
                            <th>{{ __('documents.name') }}</th>
                            <th>{{ __('documents.category') }}</th>
                            <th>{{ __('documents.uploaded_by') }}</th>
                            <th>{{ __('documents.file_size') }}</th>
                            <th>{{ __('documents.uploaded_date') }}</th>
                            <th>{{ __('documents.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td>{{ $document->name }}</td>
                                <td><span class="badge bg-info">{{ $document->category }}</span></td>
                                <td>{{ $document->uploader->name }}</td>
                                <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('documents.download', $document) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('documents.show', $document) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
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

            {{ $documents->links() }}
        </div>
    </div>
</div>
@endsection
