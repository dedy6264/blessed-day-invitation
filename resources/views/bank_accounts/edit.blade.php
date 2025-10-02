@extends('layouts.admin')

@section('title', $title ?? 'Edit Bank Account')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Edit Bank Account' }}</h1>
    @if(isset($indexRoute))
    <a href="{{ $indexRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Bank Accounts
    </a>
    @endif
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Edit Bank Account' }}</h6>
            </div>
            <div class="card-body">
                @if(isset($updateRoute))
                <form method="POST" action="{{ $updateRoute  }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3 form-group">
                        <label for="wedding_event_id" class="form-label">Wedding Event <span class="text-danger">*</span></label>
                        <select name="wedding_event_id" id="wedding_event_id" class="form-control" required>
                            <option value="">Select Wedding Event</option>
                            @forelse($weddingEvents as $weddingEvent)
                                <option value="{{ $weddingEvent->id }}" {{ (old('wedding_event_id', $record->wedding_event_id) == $weddingEvent->id) ? 'selected' : '' }}>
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
                        <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name', $record->bank_name) }}" required>
                        @error('bank_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="account_number" class="form-label">Account Number <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" id="account_number" class="form-control" value="{{ old('account_number', $record->account_number) }}" required>
                        @error('account_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="account_holder_name" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                        <input type="text" name="account_holder_name" id="account_holder_name" class="form-control" value="{{ old('account_holder_name', $record->account_holder_name) }}" required>
                        @error('account_holder_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-group">
                        <label for="is_active" class="form-label">Is Active</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" {{ old('is_active', $record->is_active) ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('is_active', $record->is_active) ? '' : 'selected' }}>No</option>
                        </select>
                        @error('is_active')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Bank Account
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