@extends('layouts.admin')

@section('title', $title ?? 'Edit')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $title ?? 'Edit Record' }}</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Edit Record' }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $updateRoute ?? '#' }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        @foreach ($columns as $column)
                            @if ($column !== 'id' && $column !== 'created_at' && $column !== 'updated_at')
                                <div class="col-md-6 mb-3">
                                    <label for="{{ $column }}">{!! ucfirst(str_replace('_', ' ', $column)) !!}</label>
                                    
                                    @if ($column === 'client_id' && isset($clients))
                                        <select class="form-control" id="{{ $column }}" name="{{ $column }}" required>
                                            <option value="">Select a client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" {{ (old($column, $record->$column ?? '') == $client->id) ? 'selected' : '' }}>
                                                    {{ $client->client_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif ($column === 'guest_id' && isset($guests))
                                        <select class="form-control" id="{{ $column }}" name="{{ $column }}" required>
                                            <option value="">Select a guest</option>
                                            @foreach ($guests as $guest)
                                                <option value="{{ $guest->id }}" {{ (old($column, $record->$column ?? '') == $guest->id) ? 'selected' : '' }}>
                                                    {{ $guest->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif ($column === 'wedding_event_id' && isset($weddingEvents))
                                        <select class="form-control" id="{{ $column }}" name="{{ $column }}" required>
                                            <option value="">Select a wedding event</option>
                                            @foreach ($weddingEvents as $event)
                                                <option value="{{ $event->id }}" {{ (old($column, $record->$column ?? '') == $event->id) ? 'selected' : '' }}>
                                                    {{ $event->event_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif ($column === 'wedding_date' || str_contains($column, '_date'))
                                        <input type="date" class="form-control" id="{{ $column }}" name="{{ $column }}" 
                                            value="{{ old($column, $record->$column ?? '') }}" required>
                                    @elseif ($column === 'is_approved')
                                        <select class="form-control" id="{{ $column }}" name="{{ $column }}">
                                            <option value="0" {{ (old($column, $record->$column ?? '') == '0') ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ (old($column, $record->$column ?? '') == '1') ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    @else
                                        <input type="text" class="form-control" id="{{ $column }}" name="{{ $column }}" 
                                            value="{{ old($column, $record->$column ?? '') }}" required>
                                    @endif
                                    
                                    @if ($errors->has($column))
                                        <div class="text-danger mt-2">
                                            {{ $errors->first($column) }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection