@extends('layouts.admin')

@section('title', Auth::user()->isAdmin() ? 'Admin Dashboard' : 'Client Dashboard')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ Auth::user()->isAdmin() ? 'Admin Dashboard' : 'Client Dashboard' }}</h1>
</div>

@if (Auth::user()->isAdmin())
    <!-- Admin Dashboard Content -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Clients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $clientCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Couples</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $coupleCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Events</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $eventCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Guests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $guestCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Clients</h6>
                </div>
                <div class="card-body">
                    @if(isset($recentClients) && $recentClients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentClients as $client)
                                        <tr>
                                            <td>{{ $client->client_name }}</td>
                                            <td>{{ $client->phone ?? 'No phone provided' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">No clients found</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Events</h6>
                </div>
                <div class="card-body">
                    @if(isset($recentEvents) && $recentEvents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Event Name</th>
                                        <th>Couple</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEvents as $event)
                                        <tr>
                                            <td>{{ $event->event_name }}</td>
                                            <td>{{ $event->couple->groom_name ?? '' }} & {{ $event->couple->bride_name ?? '' }}</td>
                                            <td>{{ $event->event_date->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">No events found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Client Dashboard Content -->
    <div class="row">
        @if(isset($client))
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Profile</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $client->client_name }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $client->address ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $client->phone ?? 'Not provided' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Weddings</h6>
                </div>
                <div class="card-body">
                    @if(isset($couples) && $couples->count() > 0)
                        <p>You have {{ $couples->count() }} wedding(s) registered.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Groom</th>
                                        <th>Bride</th>
                                        <th>Wedding Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($couples as $couple)
                                        <tr>
                                            <td>{{ $couple->groom_name }}</td>
                                            <td>{{ $couple->bride_name }}</td>
                                            <td>{{ $couple->wedding_date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>You haven't registered any weddings yet.</p>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Your client profile is not set up yet. Please contact support.
            </div>
        </div>
        @endif
    </div>
@endif
@endsection