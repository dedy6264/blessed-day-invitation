@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h1>Edit Client</h1>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back to Clients</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('clients.update', $client) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="client_name" class="form-label">Client Name *</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" value="{{ old('client_name', $client->client_name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $client->address) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $client->nik) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $client->phone) }}">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Client</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection