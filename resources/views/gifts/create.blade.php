@extends('layouts.admin')

@section('title', $title ?? 'Create Gift')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Create Gift' }}</h1>
    @if(isset($indexRoute))
    <a href="{{ $indexRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Gifts
    </a>
    @endif
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Create Gift' }}</h6>
            </div>
            <div class="card-body">
                @if(isset($storeRoute))
                <form method="POST" action="{{ $storeRoute  }}">
                    @csrf
                    
                    <div class="mb-3 form-group">
                        <label for="wedding_event_id" class="form-label">Wedding Event <span class="text-danger">*</span></label>
                        <select name="wedding_event_id" id="wedding_event_id" class="form-control" required>
                            <option value="">Select Wedding Event</option>
                            @forelse($weddingEvents as $weddingEvent)
                                <option value="{{ $weddingEvent->id }}" {{ old('wedding_event_id') == $weddingEvent->id ? 'selected' : '' }}>
                                    {{ $weddingEvent->event_name }} - {{ $weddingEvent->groom_name }} & {{ $weddingEvent->bride_name }}
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
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name') }}">
                        <small class="form-text text-muted">Optional - Leave blank for non-financial gifts.</small>
                        @error('bank_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" name="account_number" id="account_number" class="form-control" value="{{ old('account_number') }}">
                        <small class="form-text text-muted">Optional - Leave blank for non-financial gifts.</small>
                        @error('account_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="account_holder_name" class="form-label">Account Holder Name</label>
                        <input type="text" name="account_holder_name" id="account_holder_name" class="form-control" value="{{ old('account_holder_name') }}">
                        <small class="form-text text-muted">Optional - Leave blank for non-financial gifts.</small>
                        @error('account_holder_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="gift_type" class="form-label">Gift Type <span class="text-danger">*</span></label>
                        <select name="gift_type" id="gift_type" class="form-control" required>
                            <option value="gift" {{ old('gift_type') === 'gift' ? 'selected' : '' }}>Gift</option>
                            <option value="support" {{ old('gift_type') === 'support' ? 'selected' : '' }}>Support</option>
                        </select>
                        <small class="form-text text-muted">Choose 'Gift' for regular wedding gifts or 'Support' for wedding support services.</small>
                        @error('gift_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="gift_description" class="form-label">Gift Description</label>
                        <textarea name="gift_description" id="gift_description" class="form-control" rows="3">{{ old('gift_description') }}</textarea>
                        <small class="form-text text-muted">Optional - Describe the gift or support being offered.</small>
                        @error('gift_description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Gift
                    </button>
                    @if(isset($indexRoute))
                    <a href="{{ $indexRoute}}" class="btn btn-secondary">Cancel</a>
                    @endif
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection