@extends('layouts.admin')

@section('title', $title ?? 'View Invitation')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'View Invitation' }}</h1>
    @if(isset($indexRoute))
    <a href="{{ $indexRoute }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Invitations
    </a>
    @endif
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'View Invitation' }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Guest</th>
                                <td>{{ $invitation->guest->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Wedding Event</th>
                                <td>{{ $invitation->weddingEvent->event_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Invitation Code</th>
                                <td>{{ $invitation->invitation_code }}</td>
                            </tr>
                            <tr>
                                <th>Is Attending</th>
                                <td>
                                    @if ($invitation->is_attending === null)
                                        <span class="badge badge-secondary">Not Responded</span>
                                    @elseif ($invitation->is_attending)
                                        <span class="badge badge-success">Attending</span>
                                    @else
                                        <span class="badge badge-danger">Not Attending</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Responded At</th>
                                <td>{{ $invitation->responded_at ? $invitation->responded_at->format('d M Y, H:i') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $invitation->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $invitation->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if(isset($editRoute))
                <a href="{{ route($editRoute, $invitation) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Invitation</a>
                    @endif
                @if(isset($indexRoute))
                <a href="{{ $indexRoute }}" class="btn btn-secondary">Back to List</a>
                @endif
                @if(isset($deleteRoute))
                <form action="{{ route($deleteRoute, $invitation) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this invitation?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Invitation
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Invitation Card Section -->
<div class="mt-4 row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Invitation Card</h6>
            </div>
            <div class="card-body">
                @if($invitation->qrCode)
                    <div class="invitation-card-detail">
                        <div class="row">
                            <div class="text-center col-md-5">
                                <div class="qr-section">
                                    <h6 class="mb-3 text-center">QR Code</h6>
                                    @if($invitation->qrCode->qr_image_url)
                                        @if(pathinfo($invitation->qrCode->qr_image_url, PATHINFO_EXTENSION) === 'svg')
                                            <!-- Display SVG directly -->
                                            <div class="qr-svg-container">
                                                {!! file_get_contents(public_path($invitation->qrCode->qr_image_url)) !!}
                                            </div>
                                            <div class="mt-3">
                                                <a href="{{ asset($invitation->qrCode->qr_image_url) }}" download class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download"></i> Download QR Code
                                                </a>
                                            </div>
                                        @else
                                            <!-- Display other image formats -->
                                            <img src="{{ asset($invitation->qrCode->qr_image_url) }}" alt="QR Code" class="img-fluid qr-code-image">
                                            <div class="mt-3">
                                                <a href="{{ asset($invitation->qrCode->qr_image_url) }}" download class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download"></i> Download QR Code
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="p-3 text-center border rounded bg-light">
                                            <span>No QR Code</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="details-section">
                                    <h6 class="mb-3 text-center">Invitation Details</h6>
                                    <div class="mb-2 detail-item">
                                        <strong>Guest:</strong> 
                                        <span>{{ $invitation->guest->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2 detail-item">
                                        <strong>Guest Email:</strong> 
                                        <span>{{ $invitation->guest->email ?? 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2 detail-item">
                                        <strong>Guest Phone:</strong> 
                                        <span>{{ $invitation->guest->phone ?? 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2 detail-item">
                                        <strong>Couple:</strong> 
                                        <span>{{ $invitation->weddingEvent->couple->groom_name ?? '' }} & {{ $invitation->weddingEvent->couple->bride_name ?? '' }}</span>
                                    </div>
                                    <div class="mb-2 detail-item">
                                        <strong>Event:</strong> 
                                        <span>{{ $invitation->weddingEvent->event_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2 detail-item">
                                        <strong>Date:</strong> 
                                        <span>{{ $invitation->weddingEvent->event_date ? $invitation->weddingEvent->event_date->format('d M Y') : 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2 detail-item">
                                        <strong>Time:</strong> 
                                        <span>{{ $invitation->weddingEvent->event_time ?? 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2 detail-item">
                                        <strong>End Time:</strong> 
                                        <span>{{ $invitation->weddingEvent->end_time ?? 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2 detail-item">
                                        <strong>Location:</strong> 
                                        <span>{{ $invitation->weddingEvent->location->venue_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <strong>Address:</strong> 
                                        <span>{{ $invitation->weddingEvent->location->address ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <style>
                    .invitation-card-detail {
                        padding: 1rem;
                    }
                    
                    .qr-section {
                        padding: 1.5rem;
                        background-color: #f8f9fc;
                        border-radius: 0.35rem;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                    }
                    
                    .qr-code-image {
                        max-width: 200px;
                        height: auto;
                        border: 1px solid #e3e6f0;
                        border-radius: 0.35rem;
                        padding: 0.5rem;
                        background-color: white;
                    }
                    
                    .qr-svg-container {
                        max-width: 200px;
                        height: auto;
                        border: 1px solid #e3e6f0;
                        border-radius: 0.35rem;
                        padding: 0.5rem;
                        background-color: white;
                    }
                    
                    .qr-svg-container svg {
                        width: 100%;
                        height: auto;
                    }
                    
                    .details-section {
                        padding: 1rem;
                    }
                    
                    .detail-item {
                        display: flex;
                        justify-content: space-between;
                        border-bottom: 1px solid #e3e6f0;
                        padding: 0.5rem 0;
                    }
                    
                    .detail-item:last-child {
                        border-bottom: none;
                    }
                    
                    .detail-item strong {
                        min-width: 120px;
                        font-weight: 600;
                    }
                    </style>
                @else
                    <p class="text-center text-muted">No QR code generated for this invitation.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection