@extends('layouts.admin')

@section('title', $title ?? 'View Gallery Image')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'View Gallery Image' }}</h1>
    <div>
        @if(isset($editRoute))
        <a href="{{ route($editRoute, $galleryImage) }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-warning">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit
        </a>
        @endif
        @if(isset($indexRoute))
        <a href="{{ route($indexRoute) }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Gallery Images
        </a>
        @endif
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Gallery Image Details' }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $galleryImage->id }}</td>
                                </tr>
                                <tr>
                                    <th>Wedding Event</th>
                                    <td>{{ $galleryImage->weddingEvent->event_name }} - {{ $galleryImage->weddingEvent->couple->groom_name }} & {{ $galleryImage->weddingEvent->couple->bride_name }}</td>
                                </tr>
                                <tr>
                                    <th>Image URL</th>
                                    <td>{{ $galleryImage->image_url }}</td>
                                </tr>
                                <tr>
                                    <th>Thumbnail URL</th>
                                    <td>{{ $galleryImage->thumbnail_url ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $galleryImage->description ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Sort Order</th>
                                    <td>{{ $galleryImage->sort_order }}</td>
                                </tr>
                                <tr>
                                    <th>Is Background</th>
                                    <td>
                                        @if($galleryImage->is_background === 'Y')
                                            <span class="badge badge-primary">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $galleryImage->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $galleryImage->updated_at->format('d M Y, H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Image Gallery</h5>
                        @if($galleryImage->image_url)
                            <div class="gallery-container">
                                <img src="{{ asset($galleryImage->image_url) }}" alt="{{ $galleryImage->description ?? 'Gallery Image' }}" class="img-fluid gallery-image" style="cursor: pointer;" onclick="openModal(this.src)">
                            </div>
                        @else
                            <p>No image available</p>
                        @endif
                    </div>
                </div>
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
    .gallery-image {
        transition: transform 0.2s;
        border-radius: 5px;
    }
    
    .gallery-image:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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