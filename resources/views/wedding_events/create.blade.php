@extends('layouts.admin-master')

@section('title', 'Create Wedding Event')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">Create Wedding Event</h1>
    @if(isset($indexRoute))
    <a href="{{ $indexRoute }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Wedding Events
    </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Wedding Event Details</h6>
            </div>
            <div class="card-body">
                @if(isset($storeRoute))
                <form method="POST" action="{{ $storeRoute  }}">

                    @csrf
                    
                    <div class="mb-3 form-group">
                        <label for="couple_id" class="form-label">Couple <span class="text-danger">*</span></label>
                        <select name="couple_id" id="couple_id" class="form-control" required>
                            <option value="">Select Couple</option>
                            @forelse($couples as $couple)
                                <option value="{{ $couple->id }}" {{ old('couple_id') == $couple->id ? 'selected' : '' }}>
                                    {{ $couple->groom_name }} & {{ $couple->bride_name }} - {{ $couple->wedding_date ? $couple->wedding_date->format('d M Y') : '' }}
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
                        <label for="event_name" class="form-label">Event Name <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" id="event_name" class="form-control" value="{{ old('event_name') }}" required>
                        @error('event_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="event_date" class="form-label">Event Date <span class="text-danger">*</span></label>
                        <input type="date" name="event_date" id="event_date" class="form-control" value="{{ old('event_date') }}" required>
                        @error('event_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="event_time" class="form-label">Event Time</label>
                        <input type="time" name="event_time" id="event_time" class="form-control" value="{{ old('event_time') }}">
                        @error('event_time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}">
                        @error('end_time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Wedding Event
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection