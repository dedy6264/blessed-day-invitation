<div class="invitation-card-detail">
    <div class="row">
        <div class="col-md-5 text-center">
            <div class="qr-section">
                <h6 class="text-center mb-3">QR Code</h6>
                @if($qrCode->qr_image_url)
                    @if(pathinfo($qrCode->qr_image_url, PATHINFO_EXTENSION) === 'svg')
                        <!-- Display SVG directly -->
                        <div class="qr-svg-container">
                            {!! file_get_contents(public_path($qrCode->qr_image_url)) !!}
                        </div>
                        <div class="mt-3">
                            <a href="{{ asset($qrCode->qr_image_url) }}" download class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Download QR Code
                            </a>
                        </div>
                    @else
                        <!-- Display other image formats -->
                        <img src="{{ asset($qrCode->qr_image_url) }}" alt="QR Code" class="img-fluid qr-code-image">
                        <div class="mt-3">
                            <a href="{{ asset($qrCode->qr_image_url) }}" download class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Download QR Code
                            </a>
                        </div>
                    @endif
                @else
                    <div class="bg-light border rounded p-3 text-center">
                        <span>No QR Code</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-7">
            <div class="details-section">
                <h6 class="text-center mb-3">Invitation Details</h6>
                @if($qrCode->invitation)
                    <div class="detail-item mb-2">
                        <strong>Guest:</strong> 
                        <span>{{ $qrCode->invitation->guest->name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Guest Email:</strong> 
                        <span>{{ $qrCode->invitation->guest->email ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Guest Phone:</strong> 
                        <span>{{ $qrCode->invitation->guest->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Couple:</strong> 
                        <span>{{ $qrCode->invitation->weddingEvent->couple->groom_name ?? '' }} & {{ $qrCode->invitation->weddingEvent->couple->bride_name ?? '' }}</span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Event:</strong> 
                        <span>{{ $qrCode->invitation->weddingEvent->event_name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Date:</strong> 
                        <span>{{ $qrCode->invitation->weddingEvent->event_date ? $qrCode->invitation->weddingEvent->event_date->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Time:</strong> 
                        <span>{{ $qrCode->invitation->weddingEvent->event_time ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>End Time:</strong> 
                        <span>{{ $qrCode->invitation->weddingEvent->end_time ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Location:</strong> 
                        <span>{{ $qrCode->invitation->weddingEvent->location->venue_name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Address:</strong> 
                        <span>{{ $qrCode->invitation->weddingEvent->location->address ?? 'N/A' }}</span>
                    </div>
                @else
                    <p class="text-muted">No invitation data available</p>
                @endif
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