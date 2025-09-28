@extends('layouts.admin')

@section('title', $title ?? 'QR Codes')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $title ?? 'QR Codes' }}</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'QR Codes' }} List</h6>
            </div>
            <div class="card-body">
                @if ($qrCodes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Couple</th>
                                    <th>Event</th>
                                    <th>Invitation Code</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($qrCodes as $qrCode)
                                    <tr>
                                        <td>{{ $qrCode->invitation->guest->name ?? 'N/A' }}</td>
                                        <td>{{ $qrCode->invitation->weddingEvent->couple->groom_name ?? '' }} & {{ $qrCode->invitation->weddingEvent->couple->bride_name ?? '' }}</td>
                                        <td>{{ $qrCode->invitation->weddingEvent->event_name ?? 'N/A' }}</td>
                                        <td>{{ $qrCode->invitation->invitation_code ?? 'N/A' }}</td>
                                        <td>{{ $qrCode->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info show-invitation-card" data-id="{{ $qrCode->id }}">
                                                <i class="fas fa-eye"></i> View Card
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $qrCodes->links() }}
                        </div>
                    </div>
                @else
                    <p class="text-center">No QR codes found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Invitation Card Modal -->
<div class="modal fade" id="invitationCardModal" tabindex="-1" role="dialog" aria-labelledby="invitationCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invitationCardModalLabel">Invitation Card</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="invitationCardModalBody">
                <!-- Modal content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

<script>
    $(document).ready(function() {
        // Handle show invitation card button click
        $('.show-invitation-card').on('click', function() {
            var qrCodeId = $(this).data('id');
            
            // Show loading indicator
            $('#invitationCardModalBody').html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
            $('#invitationCardModal').modal('show');
            
            // Fetch invitation card content
            $.ajax({
                url: '{{ url("/") }}/qr-codes/' + qrCodeId + '/invitation-card',
                type: 'GET',
                success: function(response) {
                    $('#invitationCardModalBody').html(response.html);
                },
                error: function() {
                    $('#invitationCardModalBody').html('<div class="alert alert-danger">Failed to load invitation card.</div>');
                }
            });
        });
    });
</script>
@endsection

@section('styles')
<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection