@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Client Details</h1>
            <div>
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back to Clients</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>ID</th>
                        <td>{{ $client->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $client->client_name }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $client->address }}</td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td>{{ $client->nik }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $client->phone }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $client->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $client->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
                
                <h3>Couples</h3>
                @if($client->couples->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Groom Name</th>
                                <th>Bride Name</th>
                                <th>Wedding Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->couples as $couple)
                            <tr>
                                <td>{{ $couple->id }}</td>
                                <td>{{ $couple->groom_name }}</td>
                                <td>{{ $couple->bride_name }}</td>
                                <td>{{ $couple->wedding_date->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('couples.show', $couple) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p>No couples found for this client.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection