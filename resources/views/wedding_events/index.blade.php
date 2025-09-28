@extends('layouts.admin-master')

@section('title', 'Wedding Events')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">Wedding Events</h1>
    @if (isset($createRoute))
        <a href="{{ $createRoute }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-primary">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Wedding Event
        </a>
        @endif
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Wedding Events List</h6>
            </div>
            <div class="card-body">
                @if ($weddingEvents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Couple</th>
                                    <th>Event Name</th>
                                    <th>Event Date</th>
                                    <th>Event Time</th>
                                    <th>End Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($weddingEvents as $weddingEvent)
                                    <tr>
                                        <td>{{ $weddingEvent->couple->groom_name }} & {{ $weddingEvent->couple->bride_name }}</td>
                                        <td>{{ $weddingEvent->event_name }}</td>
                                        <td>{{ $weddingEvent->event_date ? $weddingEvent->event_date->format('d M Y') : '-' }}</td>
                                        <td>{{ $weddingEvent->event_time ?? 'N/A' }}</td>
                                        <td>{{ $weddingEvent->end_time ?? 'N/A' }}</td>
                                        <td>
                                             @if (isset($showRoute))
                                            <a href="{{ route($showRoute, $weddingEvent) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif
                                             @if (isset($editRoute))
                                            <a href="{{ route($editRoute, $weddingEvent) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                             @if (isset($deleteRoute))
                                            <form action="{{ route($deleteRoute, $weddingEvent) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this wedding event?');">
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
                            {{ $weddingEvents->links() }}
                        </div>
                    </div>
                @else
                    <p class="text-center">No wedding events found.</p>
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