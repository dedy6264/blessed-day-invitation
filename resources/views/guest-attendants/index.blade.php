@extends('layouts.admin')

@section('title', $title ?? 'Guest Attendants')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Guest Attendants' }}</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Guest Attendants List</h6>
            </div>
            <div class="card-body">
                @if ($guestAttendants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Guest Name</th>
                                    <th>Guest ID</th>
                                    <th>Wedding Event</th>
                                    <th>Checked In At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guestAttendants as $guestAttendant)
                                    <tr>
                                        <td>{{ $guestAttendant->guest_name }}</td>
                                        <td>{{ $guestAttendant->guest->id ?? 'N/A' }}</td>
                                        <td>{{ $guestAttendant->weddingEvent ? $guestAttendant->weddingEvent->event_name : 'N/A' }}</td>
                                        <td>{{ $guestAttendant->checked_in_at ? $guestAttendant->checked_in_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                        <td>
                                            @if(isset($deleteRoute))
                                            <form action="{{ route($deleteRoute, $guestAttendant) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this guest attendant record?');">
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
                            {{ $guestAttendants->links() }}
                        </div>
                    </div>
                @else
                    <p class="text-center">No guest attendants found.</p>
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