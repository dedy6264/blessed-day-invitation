@extends('layouts.admin')

@section('title', $title ?? 'Import Guests')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Import Guests' }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ $indexRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-primary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Guests
        </a>
        <a href="{{ request()->routeIs('my-guests.*') ? route('my-guests.download-sample') : route('guests.download-sample') }}" 
           class="shadow-sm d-sm-inline-block btn btn-sm btn-info">
            <i class="fas fa-download fa-sm text-white-50"></i> Download Sample
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Import Guests from Excel</h6>
            </div>
            <div class="card-body">
                <form action="{{ request()->routeIs('my-guests.*') ? route('my-guests.import.post') : route('guests.import.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="couple_id">Select Couple</label>
                        <select name="couple_id" id="couple_id" class="form-control @error('couple_id') is-invalid @enderror" required>
                            <option value="">-- Select Couple --</option>
                            @foreach($couples as $couple)
                                <option value="{{ $couple->id }}" {{ old('couple_id') == $couple->id ? 'selected' : '' }}>
                                    {{ $couple->groom_name }} & {{ $couple->bride_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('couple_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="wedding_event_id">Select Wedding Event</label>
                        <select name="wedding_event_id" id="wedding_event_id" class="form-control @error('wedding_event_id') is-invalid @enderror" required>
                            <option value="">-- Select Event --</option>
                        </select>
                        @error('wedding_event_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="file">Excel File</label>
                        <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">Only .xlsx, .xls, and .csv files are allowed.</small>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="redirect_to_invitations" id="redirect_to_invitations" value="1">
                            <label class="form-check-label" for="redirect_to_invitations">
                                Redirect to invitations page after import
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Import Guests</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const coupleSelect = document.getElementById('couple_id');
    const eventSelect = document.getElementById('wedding_event_id');
    
    // Initial population of events if couple is already selected
    populateEvents();
    
    coupleSelect.addEventListener('change', function() {
        populateEvents();
    });
    
    function populateEvents() {
        const selectedCoupleId = coupleSelect.value;
        eventSelect.innerHTML = '<option value="">-- Select Event --</option>';
        
        if (selectedCoupleId) {
            // Get the selected couple's events from the page data
            const couplesData = @json($couples);
            const selectedCouple = couplesData.find(couple => couple.id == selectedCoupleId);
            
            if (selectedCouple && selectedCouple.wedding_events) {
                selectedCouple.wedding_events.forEach(function(event) {
                    const option = document.createElement('option');
                    option.value = event.id;
                    option.textContent = event.event_name + ' (' + new Date(event.event_date).toLocaleDateString() + ')';
                    if (event.id == '{{ old("wedding_event_id") }}') {
                        option.selected = true;
                    }
                    eventSelect.appendChild(option);
                });
            }
        }
    }
});
</script>
@endsection