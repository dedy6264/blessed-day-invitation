@extends('layouts.admin')

@section('title', $title ?? 'Edit Couple')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Edit Couple' }}</h1>
</div>
<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Edit Couple' }}</h6>
            </div>
            <div class="card-body">
                @if(isset($updateRoute))
                <form method="POST" action="{{ $updateRoute }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        @if(auth()->user()->role==="client")
                        <input type="text" id="client_id" name="client_id" value="{{ auth()->user()->client_id }}" hidden>
                        @else
                        <div class="mb-3 col-md-6">
                            <label for="client_id">Client</label>
                            <select class="form-control" id="client_id" name="client_id" required>
                                <option value="">Select a client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ (old('client_id', $record->client_id ?? '') == $client->id) ? 'selected' : '' }}>
                                        {{ $client->client_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('client_id'))
                                <div class="mt-2 text-danger">
                                    {{ $errors->first('client_id') }}
                                </div>
                            @endif
                        </div>
                        @endif
                        <div class="mb-3 col-md-6">
                            <label for="groom_name">Groom Name</label>
                            <input type="text" class="form-control" id="groom_name" name="groom_name" 
                                value="{{ old('groom_name', $record->groom_name ?? '') }}" required>
                            @if ($errors->has('groom_name'))
                                <div class="mt-2 text-danger">
                                    {{ $errors->first('groom_name') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="bride_name">Bride Name</label>
                            <input type="text" class="form-control" id="bride_name" name="bride_name" 
                                value="{{ old('bride_name', $record->bride_name ?? '') }}" required>
                            @if ($errors->has('bride_name'))
                                <div class="mt-2 text-danger">
                                    {{ $errors->first('bride_name') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="wedding_date">Wedding Date</label>
                            <input type="date" class="form-control" id="wedding_date" name="wedding_date" 
                                value="{{ old('wedding_date', $record->wedding_date ? $record->wedding_date->format('Y-m-d') : '') }}" required>
                            @if ($errors->has('wedding_date'))
                                <div class="mt-2 text-danger">
                                    {{ $errors->first('wedding_date') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update</button>
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