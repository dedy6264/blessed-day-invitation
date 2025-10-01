@extends('layouts.admin')

@section('title', $title ?? 'Gallery Images')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Gallery Images' }}</h1>
    @if(isset($createRoute))
    <a href="{{ route($createRoute) }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-primary">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Gallery Image
    </a>
    @endif
</div>

<!-- Filter Form -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Filter Gallery Images</h6>
            </div>
            <div class="card-body">
                @if(isset($indexRoute))
                <form method="GET" action="{{ $indexRoute }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wedding_event_id">Wedding Event</label>
                                <select name="wedding_event_id" id="wedding_event_id" class="form-control">
                                    <option value="">All Wedding Events</option>
                                    @foreach($weddingEvents as $weddingEvent)
                                        <option value="{{ $weddingEvent->id }}" {{ (isset($selectedWeddingEventId) && $selectedWeddingEventId == $weddingEvent->id) ? 'selected' : '' }}>
                                            {{ $weddingEvent->event_name }} - {{ $weddingEvent->couple->groom_name }} & {{ $weddingEvent->couple->bride_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group d-flex align-items-end h-100">
                                <div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ $indexRoute }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Gallery Images' }} List</h6>
                @if(isset($selectedWeddingEventId) && $selectedWeddingEventId)
                    @php
                        $currentEvent = $weddingEvents->firstWhere('id', $selectedWeddingEventId);
                    @endphp
                    @if($currentEvent)
                        <span class="badge badge-info">Filtered by: {{ $currentEvent->event_name }} - {{ $currentEvent->couple->groom_name }} & {{ $currentEvent->couple->bride_name }}</span>
                    @endif
                @endif
            </div>
            <div class="card-body">
                @if ($galleryImages->count() > 0)
                    <div class="row">
                        @foreach ($galleryImages as $galleryImage)
                            <div class="mb-4 col-md-3">
                                <div class="card h-100">
                                    <div class="gallery-item" style="height: 200px; overflow: hidden;">
                                        @if($galleryImage->image_url)
                                            <img src="{{ asset($galleryImage->image_url) }}" alt="{{ $galleryImage->description ?? 'Gallery Image' }}" class="card-img-top" style="height: 100%; object-fit: cover; cursor: pointer;" onclick="openModal('{{ asset($galleryImage->image_url) }}')">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                                <span>No image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($galleryImage->description ?? 'No description', 30) }}</h6>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                {{ $galleryImage->weddingEvent->event_name }}<br>
                                                {{ $galleryImage->weddingEvent->couple->groom_name }} & {{ $galleryImage->weddingEvent->couple->bride_name }}
                                            </small>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Sort: {{ $galleryImage->sort_order }}
                                                @if($galleryImage->is_background === 'Y')
                                                    <span class="badge badge-primary">Background</span>
                                                @else
                                                    <span class="badge badge-secondary">Regular</span>
                                                @endif
                                            </small>
                                            <div>
                                                @if(isset($showRoute))
                                                <a href="{{ route($showRoute, $galleryImage) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @endif
                                                @if(isset($editRoute))
                                                <a href="{{ route($editRoute, $galleryImage) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif
                                                @if(isset($deleteRoute))
                                                <form action="{{ route($deleteRoute, $galleryImage) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this gallery image?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        {{ $galleryImages->links() }}
                    </div>
                @else
                    <p class="text-center">No gallery images found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for image gallery -->
<div id="galleryModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="text-center modal-body">
                <img id="modalImage" src="" alt="Gallery Image" class="img-fluid">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .gallery-item {
        position: relative;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .gallery-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .modal-body {
        padding: 0;
    }
    
    #modalImage {
        max-height: 80vh;
        object-fit: contain;
    }
</style>
@endsection

@section('scripts')
<script>
    function openModal(src) {
        document.getElementById('modalImage').src = src;
        $('#galleryModal').modal('show');
    }
</script>
@endsection