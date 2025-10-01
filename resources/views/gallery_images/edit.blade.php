@extends('layouts.admin')

@section('title', $title ?? 'Edit Gallery Image')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Edit Gallery Image' }}</h1>
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
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Edit Gallery Image' }}</h6>
            </div>
            <div class="card-body">
                @if(isset($updateRoute))
                <form method="POST" action="{{ $updateRoute}}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3 form-group">
                        <label for="wedding_event_id" class="form-label">Wedding Event <span class="text-danger">*</span></label>
                        <select name="wedding_event_id" id="wedding_event_id" class="form-control" required>
                            <option value="">Select Wedding Event</option>
                            @forelse($weddingEvents as $weddingEvent)
                                <option value="{{ $weddingEvent->id }}" {{ (old('wedding_event_id', $record->wedding_event_id) == $weddingEvent->id) ? 'selected' : '' }}>
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
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Upload a new image (JPG, PNG, GIF) - Max 2MB</small>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                        </div>
                        @if($record->image_url)
                        <div class="mt-2">
                            <label>Current Image:</label><br>
                            <img src="{{ asset($record->image_url) }}" alt="Current Image" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                        </div>
                        @endif
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $record->description) }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $record->sort_order) }}" min="0">
                        @error('sort_order')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_background" id="is_background" value="Y" {{ old('is_background', $record->is_background) == 'Y' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_background">Is Background Image?</label>
                        </div>
                        @error('is_background')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Gallery Image
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
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
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