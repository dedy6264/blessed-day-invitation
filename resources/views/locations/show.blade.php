@extends('layouts.admin')

@section('title', $title ?? 'View Location')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'View Location' }}</h1>
    <div>
        @if(isset($editRoute))
        <a href="{{ $editRoute }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-warning">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit
        </a>
        @endif
        @if(isset($indexRoute))
        <a href="{{ $indexRoute }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Locations
        </a>
        @endif
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Location Details' }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <th>ID</th>
                            <td>{{ $location->id }}</td>
                        </tr>
                        <tr>
                            <th>Wedding Event</th>
                            <td>{{ $location->weddingEvent->event_name }} - {{ $location->weddingEvent->couple->groom_name }} & {{ $location->weddingEvent->couple->bride_name }}</td>
                        </tr>
                        <tr>
                            <th>Venue Name</th>
                            <td>{{ $location->venue_name }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $location->address }}</td>
                        </tr>
                        <tr>
                            <th>Map Embed URL</th>
                            <td>{{ $location->map_embed_url ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $location->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $location->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
                
                @if($location->map_embed_url)
                <h5 class="mt-4">Map Location</h5>
                <div class="embed-responsive embed-responsive-16by9">
                    {!! $location->map_embed_url !!}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection