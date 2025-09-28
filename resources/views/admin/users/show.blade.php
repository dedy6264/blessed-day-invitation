@extends('layouts.admin')

@section('title', $title ?? 'View User')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $title ?? 'View User' }}</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'View User' }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($columns as $column)
                        @if ($column !== 'id' && $column !== 'password')
                            <div class="col-md-6 mb-3">
                                <label><strong>{{ ucfirst(str_replace('_', ' ', $column)) }}</strong></label>
                                <p>{{ $record->$column ?? 'N/A' }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Back to Users</a>
            </div>
        </div>
    </div>
</div>
@endsection