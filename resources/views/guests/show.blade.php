@extends('layouts.admin')

@section('title', $title ?? 'View Guest')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'View Guest' }}</h1>
    @if(isset($indexRoute))
    <a href="{{ $indexRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Guests
    </a>
    @endif
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'View Guest' }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Name</th>
                                <td>{{ $guest->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $guest->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $guest->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Couple</th>
                                <td>{{ $guest->couple ? $guest->couple->groom_name . ' & ' . $guest->couple->bride_name : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Guest Index</th>
                                <td>{{ $guest->guest_index ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $guest->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $guest->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if(isset($editRoute))
                <a href="{{ route($editRoute, $guest) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Guest</a>
                @endif
                @if(isset($indexRoute))
                <a href="{{ $indexRoute }}" class="btn btn-secondary">Back to List</a>
                @endif
                @if(isset($deleteRoute))
                <form action="{{ route($deleteRoute, $guest) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this guest?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Guest
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection