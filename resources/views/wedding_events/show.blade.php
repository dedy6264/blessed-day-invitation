@extends('layouts.admin-master')

@section('title', 'View Wedding Event')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">View Wedding Event</h1>
    <div>
        @if(isset($editRoute))
        <a href="{{ route($editRoute, $weddingEvent) }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-warning">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit
        </a>
        @endif
        @if(isset($indexRoute))
        <a href="{{ $indexRoute}}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Wedding Events
        </a>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Wedding Event Details</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <th>ID</th>
                            <td>{{ $weddingEvent->id }}</td>
                        </tr>
                        <tr>
                            <th>Couple</th>
                            <td>{{ $weddingEvent->couple->groom_name }} & {{ $weddingEvent->couple->bride_name }}</td>
                        </tr>
                        <tr>
                            <th>Event Name</th>
                            <td>{{ $weddingEvent->event_name }}</td>
                        </tr>
                        <tr>
                            <th>Event Date</th>
                            <td>{{ $weddingEvent->event_date ? $weddingEvent->event_date->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Event Time</th>
                            <td>{{ $weddingEvent->event_time ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>End Time</th>
                            <td>{{ $weddingEvent->end_time ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $weddingEvent->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $weddingEvent->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
                
                <h5 class="mt-4">Location</h5>
                @if($weddingEvent->location)
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <th>Venue Name</th>
                            <td>{{ $weddingEvent->location->venue_name }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $weddingEvent->location->address }}</td>
                        </tr>
                        <tr>
                            <th>
                                @if(isset($locationRoute))
                                <a href="{{ route($locationRoute.'.edit', $weddingEvent->location) }}" class="btn btn-sm btn-warning">Edit Location</a>
                                @endif
                            </th>
                            <td></td>
                        </tr>
                    </table>
                </div>
                @else
                <p>No location information found for this event.</p>
                    @if(isset($locationRoute))
                    <a href="{{ route($locationRoute.'.create') }}?wedding_event_id={{ $weddingEvent->id }}" class="btn btn-primary">Add Location</a>
                    @endif
                @endif
                
                <h5 class="mt-4">Gallery Images</h5>
                @if($weddingEvent->galleryImages->count() > 0)
                <div class="row">
                    @foreach($weddingEvent->galleryImages as $image)
                    <div class="mb-3 col-md-3">
                        <div class="card">
                            <img src="{{ url($image->image_url) }}" class="card-img-top" alt="{{ $image->description }}" style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <p class="card-text">{{ $image->description }}</p>
                                @if(isset($galleryImageRoute))
                                <a href="{{ route($galleryImageRoute.'.edit', $image) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p>No gallery images found for this event.</p>
                @endif
                @if(isset($galleryImageRoute))
                <a href="{{ route($galleryImageRoute.'.create') }}?wedding_event_id={{ $weddingEvent->id }}" class="btn btn-primary">Add Gallery Image</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection