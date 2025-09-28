
@extends('layouts.admin-master')

@section('title', 'Client Dashboard')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">Client Dashboard</h1>
</div>

<div class="row">
    @if(isset($client))
    <div class="mb-4 col-lg-6">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
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
                <div class="text-right">
                    {{-- @dd($routePrefix) --}}
                    @if(isset($routePrefix))
                    <a href="{{route($routePrefix.'clients.edit',$client->id)}}" class="btn btn-primary btn-sm">Edit Profile</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4 col-lg-6">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($couples as $couple)
                                    <tr>
                                        <td>{{ $couple->groom_name }}</td>
                                        <td>{{ $couple->bride_name }}</td>
                                        <td>{{ $couple->wedding_date }}</td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm">View</a>
                                            <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>You haven't registered any weddings yet.</p>
                    <div class="text-center">
                        <a href="{{ route('create-order.step1')}}" class="btn btn-primary">Create Your First Wedding</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="mb-4 col-lg-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-3">
                        <a href="#" class="btn btn-primary btn-block">
                            <i class="mr-2 fas fa-heart"></i>Add Couple
                        </a>
                    </div>
                    <div class="mb-3 col-md-3">
                        <a href="#" class="btn btn-success btn-block">
                            <i class="mr-2 fas fa-calendar-alt"></i>Add Event
                        </a>
                    </div>
                    <div class="mb-3 col-md-3">
                        <a href="#" class="btn btn-info btn-block">
                            <i class="mr-2 fas fa-images"></i>Manage Gallery
                        </a>
                    </div>
                    <div class="mb-3 col-md-3">
                        <a href="#" class="btn btn-warning btn-block">
                            <i class="mr-2 fas fa-history"></i>Add Timeline
                        </a>
                    </div>
                </div>
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
@endsection