@extends('layouts.admin')

@section('title', $title ?? 'Invitations')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection



@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Invitations' }}</h1>
    <div class="flex-wrap gap-3 d-flex align-items-center">
        @if(isset($createRoute))
        <a href="{{ $createRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Add New Invitation</span>
        </a>
        @endif
        <!-- WhatsApp Broadcast Button -->
        <a href="#" class="shadow-sm d-sm-inline-block btn btn-sm btn-success btn-icon-split" data-toggle="modal" data-target="#whatsappBroadcastModal">
            <span class="icon text-white-50">
                <i class="fab fa-whatsapp"></i>
            </span>
            <span class="text">Broadcast WhatsApp</span>
        </a>
    </div>
</div>

<!-- WhatsApp Broadcast Modal -->
<div class="modal fade" id="whatsappBroadcastModal" tabindex="-1" role="dialog" aria-labelledby="whatsappBroadcastModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="whatsappBroadcastModalLabel">Broadcast WhatsApp Invitation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="weddingEventSelect">Select Wedding Event</label>
                    <select class="form-control" id="weddingEventSelect" name="wedding_event_id">
                        <option value="">-- Select Event --</option>
                        @foreach($weddingEvents as $event)
                            <option value="{{ $event->id }}">{{ $event->event_name }} - {{ $event->couple->groom_name }} & {{ $event->couple->bride_name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="form-group">
                    <label for="whatsappMessage">WhatsApp Message</label>
                    <textarea class="form-control" id="whatsappMessage" rows="4" placeholder="Enter your invitation message...">{{ old('whatsapp_message') }}</textarea>
                    <small class="form-text text-muted">Note: Guest name and invitation link will be automatically added to the message.</small>
                </div> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-success" id="startBroadcastBtn">Start Broadcast</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Invitations' }} List</h6>
            </div>
            <div class="card-body">
                @if ($invitations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Wedding Event</th>
                                    <th>Invitation Code</th>
                                    <th>Is Attending</th>
                                    <th>Count of Persons</th>
                                    <th>Responded At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invitations as $invitation)
                                    <tr>
                                        <td>{{ $invitation->guest->name ?? 'N/A' }}</td>
                                        <td>{{ $invitation->weddingEvent->event_name ?? 'N/A' }}</td>
                                        <td>{{ $invitation->invitation_code }}</td>
                                        <td>
                                            @if ($invitation->is_attending === null)
                                                <span class="badge badge-secondary">Not Responded</span>
                                            @elseif ($invitation->is_attending)
                                                <span class="badge badge-success">Attending</span>
                                            @else
                                                <span class="badge badge-danger">Not Attending</span>
                                            @endif
                                        </td>
                                        <td>{{ $invitation->count }}</td>
                                        <td>{{ $invitation->responded_at ? $invitation->responded_at->format('d M Y, H:i') : 'N/A' }}</td>
                                        <td>
                                            @if(isset($showRoute))
                                            <a href="{{ route($showRoute, $invitation) }}" class="shadow-sm btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif
                                            @if(isset($editRoute))
                                            <a href="{{ route($editRoute, $invitation) }}" class="shadow-sm btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            <!-- Send Invitation Button -->
                                            <button type="button" 
                                                    class="shadow-sm btn btn-sm btn-success send-invitation-btn" 
                                                    data-invitation-id="{{ $invitation->id }}"
                                                    data-guest-name="{{ $invitation->guest->name ?? 'Guest' }}"
                                                    data-csrf-token="{{ csrf_token() }}"
                                                    title="Send invitation via WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                            @if(isset($deleteRoute))
                                            <form action="{{ route($deleteRoute, $invitation) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this invitation?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="shadow-sm btn btn-sm btn-danger">
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
                            {{ $invitations->links() }}
                        </div>
                    </div>
                @else
                    <p class="text-center">No invitations found.</p>
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

<script>
// Add CSRF token to jQuery AJAX setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Function to get CSRF token from multiple possible locations
    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr('content') || 
               document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
               document.querySelector('[name="csrf-token"]')?.getAttribute('content') ||
               document.querySelector('[name="_token"]')?.value;
    }
    // Handle send invitation button clicks
    document.querySelectorAll('.send-invitation-btn').forEach(button => {
        button.addEventListener('click', function() {
            const invitationId = this.getAttribute('data-invitation-id');
            const guestName = this.getAttribute('data-guest-name');
            const csrfToken = this.getAttribute('data-csrf-token');
            
            // Confirm with user before sending
            if (confirm(`Are you sure you want to send invitation to ${guestName} via WhatsApp?`)) {
                // Show loading state
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;
                
                // Make AJAX request
                fetch(`/invitations/${invitationId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || getCsrfToken()
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Restore button
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                    
                    if (data.success) {
                        // Show success message
                        alert(data.message);
                    } else {
                        // Show error message
                        alert('Error: ' + (data.message || 'Failed to send invitation'));
                    }
                })
                .catch(error => {
                    // Restore button
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                    
                    console.error('Error:', error);
                    alert('An error occurred while sending the invitation.');
                });
            }
        });
    });
    
    // Handle WhatsApp broadcast button click
    document.getElementById('startBroadcastBtn').addEventListener('click', function() {
        const eventId = document.getElementById('weddingEventSelect').value;
        
        if (!eventId) {
            alert('Please select a wedding event.');
            return;
        }
        
       
        
        // Confirm with user before broadcasting
        if (confirm('Are you sure you want to broadcast WhatsApp invitations to all guests for this event? This will send messages with random delays between 15 seconds to 1 minute.')) {
            // Show loading state
            const originalText = this.textContent;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Broadcasting...';
            this.disabled = true;
            
            // Make AJAX request
            fetch('{{ request()->routeIs("my-invitations.*") ? route("my-invitations.broadcast-whatsapp") : route("invitations.broadcast-whatsapp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({
                    wedding_event_id: eventId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Restore button
                this.textContent = originalText;
                this.disabled = false;
                
                if (data.success) {
                    // Show success message
                    alert(data.message);
                    // Close the modal
                    $('#whatsappBroadcastModal').modal('hide');
                    // Clear the form
                } else {
                    // Show error message
                    alert('Error: ' + (data.message || 'Failed to broadcast invitations'));
                }
            })
            .catch(error => {
                // Restore button
                this.textContent = originalText;
                this.disabled = false;
                
                console.error('Error:', error);
                alert('An error occurred while broadcasting the invitations.');
            });
        }
    });
});
</script>
@endsection

@section('styles')
<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection