@extends('layouts.admin-master')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">Admin Dashboard</h1>
    <a href="{{ route('create-order.step1') }}" class="shadow-sm d-xs-inline-block btn btn-sm btn-primary">
        <i class="fas fa-shopping-cart fa-sm text-white-50"></i> Create New Order
    </a>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="mb-4 col-xl-3 col-md-6">
        <div class="py-2 shadow card border-left-primary h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="mr-2 col">
                        <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">
                            Clients</div>
                        <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $clientCount ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="text-gray-300 fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="mb-4 col-xl-3 col-md-6">
        <div class="py-2 shadow card border-left-success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="mr-2 col">
                        <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">
                            Couples</div>
                        <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $coupleCount ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="text-gray-300 fas fa-heart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="mb-4 col-xl-3 col-md-6">
        <div class="py-2 shadow card border-left-info h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="mr-2 col">
                        <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">Events
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="mb-0 mr-3 text-gray-800 h5 font-weight-bold">{{ $eventCount ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="text-gray-300 fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="mb-4 col-xl-3 col-md-6">
        <div class="py-2 shadow card border-left-warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="mr-2 col">
                        <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">
                            Guests</div>
                        <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $guestCount ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="text-gray-300 fas fa-user-friends fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->

<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="mb-4 shadow card">
            <!-- Card Header - Dropdown -->
            <div
                class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="text-gray-400 fas fa-ellipsis-v fa-sm fa-fw"></i>
                    </a>
                    <div class="shadow dropdown-menu dropdown-menu-right animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Actions:</div>
                        <a class="dropdown-item" href="#">View All</a>
                        <a class="dropdown-item" href="#">Export Data</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="mb-4 shadow card">
            <!-- Card Header - Dropdown -->
            <div
                class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Distribution</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="text-gray-400 fas fa-ellipsis-v fa-sm fa-fw"></i>
                    </a>
                    <div class="shadow dropdown-menu dropdown-menu-right animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Actions:</div>
                        <a class="dropdown-item" href="#">View Details</a>
                        <a class="dropdown-item" href="#">Export Data</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="pt-4 pb-2 chart-pie">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Clients
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Couples
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Events
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Content Column -->
    <div class="mb-4 col-lg-6">

        <!-- Project Card Example -->
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
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

        <!-- Color System -->
        <div class="row">
            <div class="mb-4 col-lg-6">
                <div class="text-white shadow card bg-primary">
                    <div class="card-body">
                        Clients
                        <div class="text-white-50 small">{{ $clientCount ?? 0 }} registered</div>
                    </div>
                </div>
            </div>
            <div class="mb-4 col-lg-6">
                <div class="text-white shadow card bg-success">
                    <div class="card-body">
                        Couples
                        <div class="text-white-50 small">{{ $coupleCount ?? 0 }} registered</div>
                    </div>
                </div>
            </div>
            <div class="mb-4 col-lg-6">
                <div class="text-white shadow card bg-info">
                    <div class="card-body">
                        Events
                        <div class="text-white-50 small">{{ $eventCount ?? 0 }} created</div>
                    </div>
                </div>
            </div>
            <div class="mb-4 col-lg-6">
                <div class="text-white shadow card bg-warning">
                    <div class="card-body">
                        Guests
                        <div class="text-white-50 small">{{ $guestCount ?? 0 }} registered</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mb-4 col-lg-6">

        <!-- Illustrations -->
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
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

        <!-- Approach -->
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">System Status</h6>
            </div>
            <div class="card-body">
                <p>All systems are operational. Database connection is stable and all services are running normally.</p>
                <p class="mb-0">Last updated: {{ now()->format('M d, Y H:i:s') }}</p>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<!-- Page level plugins -->
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
@endsection