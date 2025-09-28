@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h1>Couple Details</h1>
            <div>
                @if(isset($editRoute))
                <a href="{{ route($editRoute, $couple) }}" class="btn btn-warning">Edit</a>
                @endif
                @if(isset($indexRoute))
                <a href="{{ $indexRoute }}" class="btn btn-secondary">Back to Couples</a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>ID</th>
                        <td>{{ $couple->id }}</td>
                    </tr>
                    <tr>
                        <th>Client</th>
                        <td>{{ $couple->client->client_name }}</td>
                    </tr>
                    <tr>
                        <th>Groom Name</th>
                        <td>{{ $couple->groom_name }}</td>
                    </tr>
                    <tr>
                        <th>Bride Name</th>
                        <td>{{ $couple->bride_name }}</td>
                    </tr>
                    <tr>
                        <th>Wedding Date</th>
                        <td>{{ $couple->wedding_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $couple->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $couple->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
                
                <h3>People</h3>
                @if($couple->persons->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Role</th>
                                <th>Full Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($couple->persons as $person)
                            <tr>
                                <td>{{ $person->id }}</td>
                                <td>{{ ucfirst($person->role) }}</td>
                                <td>{{ $person->full_name }}</td>
                                <td>
                                    <a href="{{ route('people.show', $person) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p>No people found for this couple.</p>
                @endif
                
                <h3>Wedding Events</h3>
                @if($couple->weddingEvents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Event Name</th>
                                <th>Event Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($couple->weddingEvents as $event)
                            <tr>
                                <td>{{ $event->id }}</td>
                                <td>{{ $event->event_name }}</td>
                                <td>{{ $event->event_date->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('wedding-events.show', $event) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p>No wedding events found for this couple.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection