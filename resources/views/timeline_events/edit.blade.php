@extends('layouts.admin')

@section('title', $title ?? 'Edit Timeline Event')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Edit Timeline Event' }}</h1>
    @if(isset($indexRoute))
    <a href="{{ $indexRoute }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Timeline Events
    </a>
    @endif
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Edit Timeline Event' }}</h6>
            </div>
            <div class="card-body">
                @if(isset($updateRoute))
                <form method="POST" action="{{ $updateRoute  }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3 form-group">
                        <label for="couple_id" class="form-label">Couple <span class="text-danger">*</span></label>
                        <select name="couple_id" id="couple_id" class="form-control" required>
                            <option value="">Select Couple</option>
                            @forelse($couples as $couple)
                                <option value="{{ $couple->id }}" {{ (old('couple_id', $record->couple_id) == $couple->id) ? 'selected' : '' }}>
                                    {{ $couple->groom_name }} & {{ $couple->bride_name }} - {{ $couple->wedding_date->format('d M Y') }}
                                </option>
                            @empty
                                <option value="" disabled>No couples available. Please <a href="{{ route('couples.create') }}">create a couple</a> first.</option>
                            @endforelse
                        </select>
                        @error('couple_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $record->title) }}">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="event_date" class="form-label">Event Date</label>
                        <input type="date" name="event_date" id="event_date" class="form-control" value="{{ old('event_date', $record->event_date ? $record->event_date->format('Y-m-d') : '') }}">
                        @error('event_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $record->description) }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="image" class="form-label">Image</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Allowed types: jpeg, png, jpg, gif. Max size: 2MB</small>
                                @if($record->image_url)
                                    <div class="mt-2">
                                        <small class="form-text text-muted">Current image:</small>
                                        <img src="{{ asset($record->image_url) }}" alt="Current Image" class="img-fluid" style="max-height: 100px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div id="imagePreviewContainer" style="display: none;">
                                    <p class="mb-1"><strong>New Image Preview:</strong></p>
                                    <img id="imagePreview" src="#" alt="Image Preview" class="img-fluid" style="max-height: 200px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $record->sort_order) }}" min="0">
                        @error('sort_order')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="is_inverted" class="form-label">Is Inverted</label>
                        <select name="is_inverted" id="is_inverted" class="form-control">
                            <option value="0" {{ old('is_inverted', $record->is_inverted) ? '' : 'selected' }}>No</option>
                            <option value="1" {{ old('is_inverted', $record->is_inverted) ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('is_inverted')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Timeline Event
                    </button>
                    @if(isset($indexRoute))
                    <a href="{{ $indexRoute }}" class="btn btn-secondary">Cancel</a>
                    @endif
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImage = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>
@endsection