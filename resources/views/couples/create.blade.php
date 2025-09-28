@extends('layouts.admin')

@section('title', $title ?? 'Create Couple')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Create Couple' }}</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Create Couple' }}</h6>
            </div>
            <div class="card-body">
                @if(isset($storeRoute))
                <form method="POST" action="{{ $storeRoute  }}">
                    @csrf
                    
                    <div class="row">
                        <!-- Only show client selection for admin users -->
                        @if(auth()->user()->role === 'admin')
                        <div class="mb-3 col-md-6">
                            <label for="client_id">Client</label>
                            <select class="form-control" id="client_id" name="client_id" required>
                                <option value="">Select a client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
                        @else
                        <!-- For client users, we'll pass the client_id automatically -->
                        <input type="hidden" name="client_id" value="{{ auth()->user()->client_id }}">
                        @endif
                        
                        <div class="mb-3 col-md-6">
                            <label for="package_id">Package</label>
                            <select class="form-control" id="package_id" name="package_id" required>
                                <option value="">Select a package</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('package_id'))
                                <div class="mt-2 text-danger">
                                    {{ $errors->first('package_id') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="groom_name">Groom Name</label>
                            <input type="text" class="form-control" id="groom_name" name="groom_name" 
                                value="{{ old('groom_name') }}" required>
                            @if ($errors->has('groom_name'))
                                <div class="mt-2 text-danger">
                                    {{ $errors->first('groom_name') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="bride_name">Bride Name</label>
                            <input type="text" class="form-control" id="bride_name" name="bride_name" 
                                value="{{ old('bride_name') }}" required>
                            @if ($errors->has('bride_name'))
                                <div class="mt-2 text-danger">
                                    {{ $errors->first('bride_name') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="wedding_date">Wedding Date</label>
                            <input type="date" class="form-control" id="wedding_date" name="wedding_date" 
                                value="{{ old('wedding_date') }}" required>
                            @if ($errors->has('wedding_date'))
                                <div class="mt-2 text-danger">
                                    {{ $errors->first('wedding_date') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save</button>
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