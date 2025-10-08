@extends('layouts.admin')

@section('title', $title ?? 'View Gift')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'View Gift' }}</h1>
    <div>
        @if(isset($editRoute))
        <a href="{{ route($editRoute, $gift) }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-warning">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit
        </a>
        @endif
        @if(isset($indexRoute))
        <a href="{{ $indexRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Gifts
        </a>
        @endif
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Gift Details' }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <th>ID</th>
                            <td>{{ $gift->id }}</td>
                        </tr>
                        <tr>
                            <th>Wedding Event</th>
                            <td>{{ $gift->weddingEvent->event_name }} - {{ $gift->weddingEvent->couple->groom_name }} & {{ $gift->weddingEvent->couple->bride_name }}</td>
                        </tr>
                        <tr>
                            <th>Bank Name</th>
                            <td>{{ $gift->bank_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Account Number</th>
                            <td>{{ $gift->account_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Account Holder Name</th>
                            <td>{{ $gift->account_holder_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Gift Type</th>
                            <td>
                                <span class="badge badge-info">{{ $gift->gift_type }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Gift Description</th>
                            <td>{{ $gift->gift_description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $gift->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $gift->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection