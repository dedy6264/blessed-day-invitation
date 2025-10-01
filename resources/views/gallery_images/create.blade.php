@extends('layouts.admin')

@section('title', $title ?? 'Create Gallery Image')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Create Gallery Image' }}</h1>
     @if(isset($indexRoute))
    <a href="{{ $indexRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Gallery Images
    </a>
    @endif
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Create Gallery Image' }}</h6>
            </div>
            <div class="card-body">
                @if(isset($storeRoute))
                <form method="POST" action="{{ $storeRoute }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3 form-group">
                        <label for="wedding_event_id" class="form-label">Wedding Event <span class="text-danger">*</span></label>
                        <select name="wedding_event_id" id="wedding_event_id" class="form-control" required>
                            <option value="">Select Wedding Event</option>
                            @forelse($weddingEvents as $weddingEvent)
                                <option value="{{ $weddingEvent->id }}" {{ old('wedding_event_id') == $weddingEvent->id ? 'selected' : '' }}>
                                    {{ $weddingEvent->event_name }} - {{ $weddingEvent->couple->groom_name }} & {{ $weddingEvent->couple->bride_name }}
                                </option>
                            @empty
                                <option value="" disabled>No wedding events available. Please <a href="{{ route('wedding-events.create') }}">create a wedding event</a> first.</option>
                            @endforelse
                        </select>
                        @error('wedding_event_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="images" class="form-label">Images <span class="text-danger">*</span></label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                        <small class="form-text text-muted">Select one or more images (JPG, PNG, GIF) - Max 2MB each</small>
                        @error('images')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        @error('sort_order')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_background" id="is_background" value="Y" {{ old('is_background') == 'Y' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_background">Is Background Image?</label>
                        </div>
                        @error('is_background')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Gallery Image
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
    document.getElementById('images').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('imagePreview');
        previewContainer.innerHTML = '';
        
        const files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '200px';
                    img.style.margin = '5px';
                    img.classList.add('img-thumbnail');
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
    });
    
    // Handle checkbox for is_background field
    const isBackgroundCheckbox = document.getElementById('is_background');
    // Set initial value based on current state
    if (isBackgroundCheckbox.checked) {
        isBackgroundCheckbox.value = 'Y';
    } else {
        isBackgroundCheckbox.value = 'N';
    }
    
    // Update value when state changes
    isBackgroundCheckbox.addEventListener('change', function() {
        if (this.checked) {
            this.value = 'Y';
        } else {
            this.value = 'N';
        }
    });
</script>
@endsection