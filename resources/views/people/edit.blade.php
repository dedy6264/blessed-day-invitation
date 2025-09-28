@extends('layouts.admin')

@section('title', 'Edit Person')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">Edit Person</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Person Details</h6>
            </div>
            <div class="card-body">
                @if(isset($updateRoute))
                <form method="POST" action="{{ route($updateRoute, $record->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3 form-group">
                        <label for="person_index">Person Index</label>
                        <input type="text" name="person_index" id="person_index" class="form-control" value="{{ old('person_index', $record->person_index) }}" readonly>
                        <small class="form-text text-muted">Auto-generated from client ID and role</small>
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="couple_id">Couple</label>
                        <select name="couple_id" id="couple_id" class="form-control" required>
                            <option value="">Select Couple</option>
                            @foreach($couples as $couple)
                                <option value="{{ $couple->id }}" {{ (old('couple_id', $record->couple_id) == $couple->id) ? 'selected' : '' }}>
                                    {{ $couple->groom_name }} & {{ $couple->bride_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('couple_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="groom" {{ (old('role', $record->role) == 'groom') ? 'selected' : '' }}>Groom</option>
                            <option value="bride" {{ (old('role', $record->role) == 'bride') ? 'selected' : '' }}>Bride</option>
                        </select>
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name', $record->full_name) }}" required>
                        @error('full_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="image">Photo</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Upload a photo (JPG, PNG, GIF) - Max 2MB</small>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px;">
                        </div>
                        @if($record->image_url)
                        <div class="mt-2">
                            <label>Current Photo:</label><br>
                            <img src="{{ asset($record->image_url) }}" alt="Current Photo" style="max-width: 200px; max-height: 200px;">
                        </div>
                        @endif
                    </div>

                    <!-- Hidden field for image_url (kept for backward compatibility) -->
                    <input type="hidden" name="image_url" id="image_url" value="{{ old('image_url', $record->image_url) }}">

                    <div class="mb-3 form-group">
                        <label for="additional_info">Additional Info</label>
                        <textarea name="additional_info" id="additional_info" class="form-control" rows="3">{{ old('additional_info', $record->additional_info) }}</textarea>
                        @error('additional_info')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Person Parent Information -->
                    <hr>
                    <h6 class="mb-3 font-weight-bold text-primary">Parent Information</h6>
                    
                    @php
                        $personParent = $record->personParent ?? new \App\Models\PersonParent();
                    @endphp
                    
                    <div class="mb-3 form-group">
                        <label for="father_name">Father's Name</label>
                        <input type="text" name="father_name" id="father_name" class="form-control" value="{{ old('father_name', $personParent->father_name) }}">
                        @error('father_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="father_status">Father's Status</label>
                        <select name="father_status" id="father_status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="alive" {{ (old('father_status', $personParent->father_status) == 'alive') ? 'selected' : '' }}>Alive</option>
                            <option value="deceased" {{ (old('father_status', $personParent->father_status) == 'deceased') ? 'selected' : '' }}>Deceased</option>
                        </select>
                        @error('father_status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="mother_name">Mother's Name</label>
                        <input type="text" name="mother_name" id="mother_name" class="form-control" value="{{ old('mother_name', $personParent->mother_name) }}">
                        @error('mother_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="mother_status">Mother's Status</label>
                        <select name="mother_status" id="mother_status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="alive" {{ (old('mother_status', $personParent->mother_status) == 'alive') ? 'selected' : '' }}>Alive</option>
                            <option value="deceased" {{ (old('mother_status', $personParent->mother_status) == 'deceased') ? 'selected' : '' }}>Deceased</option>
                        </select>
                        @error('mother_status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Person
                    </button>
                    @if(isset($indexRoute))
                    <a href="{{ $indexRoute }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
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
</script>
@endsection