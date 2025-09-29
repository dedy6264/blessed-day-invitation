@extends('orders.layout')

@section('title', 'Create Order - Step 1: Select Package')

@section('wizard-content')
<form method="POST" action="{{ route('create-order.process-step1') }}">
    @csrf
    
    <div class="row">
        <div class="col-12">
            <h5>Select a Package</h5>
            <p>Please select a package to begin creating the order.</p>
            
            @if($packages->isEmpty())
                <div class="alert alert-warning">
                    No packages available. Please create a package first.
                </div>
                <a href="{{ route('packages.create') }}" class="btn btn-primary">Create Package</a>
            @else
                <div class="row">
                    @foreach($packages as $package)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $package->name }}</h5>
                                    <p class="card-text">{{ $package->description }}</p>
                                    <ul class="list-unstyled">
                                        <li><strong>Price:</strong> Rp{{ number_format($package->price, 0, ',', '.') }}</li>
                                        <li><strong>Duration:</strong> {{ $package->period }} days</li>
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="package_id" 
                                               id="package_{{ $package->id }}" value="{{ $package->id }}"
                                               {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label" for="package_{{ $package->id }}">
                                            Select this package
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Client selection for admin users -->
                @if($user->role === 'admin')
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Select a Client</h5>
                            <p>Please select the client for this order.</p>
                            
                            @if($clients->isEmpty())
                                <div class="alert alert-warning">
                                    No clients available. Please create a client first.
                                </div>
                                <a href="{{ route('clients.create') }}" class="btn btn-primary">Create Client</a>
                            @else
                                <div class="form-group">
                                    <label for="client_id">Client</label>
                                    <select name="client_id" id="client_id" class="form-control" required>
                                        <option value="">Select a client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Continue</button>
                    <a href="{{ route('packages.index') }}" class="btn btn-secondary">Manage Packages</a>
                </div>
            @endif
        </div>
    </div>
</form>
@endsection

@section('styles')
<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endsection