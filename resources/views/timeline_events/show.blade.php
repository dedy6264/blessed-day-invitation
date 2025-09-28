@extends('layouts.admin')

@section('title', $title ?? 'View Timeline Event')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'View Timeline Event' }}</h1>
    <div>
        @if(isset($editRoute))
        <a href="{{ route($editRoute, $timelineEvent) }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-warning">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit
        </a>
        @endif
        @if(isset($indexRoute))
        <a href="{{ $indexRoute }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Timeline Events
        </a>
        @endif
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Timeline Event Details' }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $timelineEvent->id }}</td>
                                </tr>
                                <tr>
                                    <th>Couple</th>
                                    <td>{{ $timelineEvent->couple->groom_name }} & {{ $timelineEvent->couple->bride_name }}</td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td>{{ $timelineEvent->title ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Event Date</th>
                                    <td>{{ $timelineEvent->event_date ? $timelineEvent->event_date->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $timelineEvent->description ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Sort Order</th>
                                    <td>{{ $timelineEvent->sort_order }}</td>
                                </tr>
                                <tr>
                                    <th>Is Inverted</th>
                                    <td>
                                        @if($timelineEvent->is_inverted)
                                            <span class="badge badge-primary">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $timelineEvent->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $timelineEvent->updated_at->format('d M Y, H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Image Preview</h5>
                        @if($timelineEvent->image_url)
                            <img src="{{ asset($timelineEvent->image_url) }}" alt="Timeline Image" class="img-fluid">
                        @else
                            <p>No image available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection