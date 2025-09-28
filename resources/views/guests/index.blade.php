@extends('layouts.admin')

@section('title', $title ?? 'Guests')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Guests' }}</h1>
    @if(isset($createRoute))
    <a href="{{ $createRoute}}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-primary">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Guest
    </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Guests' }} List</h6>
            </div>
            <div class="card-body">
                @if ($guests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Couple</th>
                                    <th>Guest Index</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guests as $guest)
                                    <tr>
                                        <td>{{ $guest->name }}</td>
                                        <td>{{ $guest->email ?? 'N/A' }}</td>
                                        <td>{{ $guest->phone ?? 'N/A' }}</td>
                                        <td>{{ $guest->couple ? $guest->couple->groom_name . ' & ' . $guest->couple->bride_name : 'N/A' }}</td>
                                        <td>{{ $guest->guest_index ?? 'N/A' }}</td>
                                        <td>
                                            @if(isset($showRoute))
                                            <a href="{{ route($showRoute, $guest) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif
                                            @if(isset($editRoute))
                                            <a href="{{ route($editRoute, $guest) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if(isset($deleteRoute))
                                            <form action="{{ route($deleteRoute, $guest) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this guest?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $guests->links() }}
                        </div>
                    </div>
                @else
                    <p class="text-center">No guests found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Page level plugins -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
@endsection

@section('styles')
<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection