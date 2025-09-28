@extends('orders.layout')

@section('title', 'Create Order - Step 2: Client and Wedding Details')

@section('wizard-content')
<form method="POST" action="{{ route('create-order.process-step2') }}">
    @csrf
    
    <div class="row">
        <div class="col-12">
            <h5>Client Information</h5>
            <p>Please enter your client information.</p>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="client_name">Client Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="client_name" name="client_name" 
                           value="{{ old('client_name') ?? (session('order_client_id') ? optional(\App\Models\Client::find(session('order_client_id')))->client_name : '') }}" required>
                    @if ($errors->has('client_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('client_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" 
                           value="{{ old('address') ?? (session('order_client_id') ? optional(\App\Models\Client::find(session('order_client_id')))->address : '') }}">
                    @if ($errors->has('address'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nik">NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik" 
                           value="{{ old('nik') }}">
                    @if ($errors->has('nik'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('nik') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" 
                           value="{{ old('phone') }}">
                    @if ($errors->has('phone'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('phone') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h5>Couple Information</h5>
            <p>Please enter the couple's information.</p>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="groom_name">Groom's Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="groom_name" name="groom_name" 
                           value="{{ old('groom_name') }}" required>
                    @if ($errors->has('groom_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('groom_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="bride_name">Bride's Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="bride_name" name="bride_name" 
                           value="{{ old('bride_name') }}" required>
                    @if ($errors->has('bride_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('bride_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="wedding_date">Wedding Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="wedding_date" name="wedding_date" 
                           value="{{ old('wedding_date') }}" required>
                    @if ($errors->has('wedding_date'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('wedding_date') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h5>Groom Information</h5>
            <p>Please enter the groom's full information.</p>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="groom_full_name">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="groom_full_name" name="groom_full_name" 
                           value="{{ old('groom_full_name') }}" required>
                    @if ($errors->has('groom_full_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('groom_full_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="groom_image_url">Image URL</label>
                    <input type="url" class="form-control" id="groom_image_url" name="groom_image_url" 
                           value="{{ old('groom_image_url') }}">
                    @if ($errors->has('groom_image_url'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('groom_image_url') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="groom_additional_info">Additional Information</label>
                    <textarea class="form-control" id="groom_additional_info" name="groom_additional_info">{{ old('groom_additional_info') }}</textarea>
                    @if ($errors->has('groom_additional_info'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('groom_additional_info') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="groom_father_name">Father's Name</label>
                    <input type="text" class="form-control" id="groom_father_name" name="groom_father_name" 
                           value="{{ old('groom_father_name') }}">
                    @if ($errors->has('groom_father_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('groom_father_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="groom_father_status">Father's Status</label>
                    <select class="form-control" id="groom_father_status" name="groom_father_status">
                        <option value="alive" {{ old('groom_father_status') == 'alive' ? 'selected' : '' }}>Alive</option>
                        <option value="deceased" {{ old('groom_father_status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                    @if ($errors->has('groom_father_status'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('groom_father_status') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="groom_mother_name">Mother's Name</label>
                    <input type="text" class="form-control" id="groom_mother_name" name="groom_mother_name" 
                           value="{{ old('groom_mother_name') }}">
                    @if ($errors->has('groom_mother_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('groom_mother_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="groom_mother_status">Mother's Status</label>
                    <select class="form-control" id="groom_mother_status" name="groom_mother_status">
                        <option value="alive" {{ old('groom_mother_status') == 'alive' ? 'selected' : '' }}>Alive</option>
                        <option value="deceased" {{ old('groom_mother_status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                    @if ($errors->has('groom_mother_status'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('groom_mother_status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h5>Bride Information</h5>
            <p>Please enter the bride's full information.</p>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="bride_full_name">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="bride_full_name" name="bride_full_name" 
                           value="{{ old('bride_full_name') }}" required>
                    @if ($errors->has('bride_full_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('bride_full_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="bride_image_url">Image URL</label>
                    <input type="url" class="form-control" id="bride_image_url" name="bride_image_url" 
                           value="{{ old('bride_image_url') }}">
                    @if ($errors->has('bride_image_url'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('bride_image_url') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="bride_additional_info">Additional Information</label>
                    <textarea class="form-control" id="bride_additional_info" name="bride_additional_info">{{ old('bride_additional_info') }}</textarea>
                    @if ($errors->has('bride_additional_info'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('bride_additional_info') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="bride_father_name">Father's Name</label>
                    <input type="text" class="form-control" id="bride_father_name" name="bride_father_name" 
                           value="{{ old('bride_father_name') }}">
                    @if ($errors->has('bride_father_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('bride_father_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="bride_father_status">Father's Status</label>
                    <select class="form-control" id="bride_father_status" name="bride_father_status">
                        <option value="alive" {{ old('bride_father_status') == 'alive' ? 'selected' : '' }}>Alive</option>
                        <option value="deceased" {{ old('bride_father_status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                    @if ($errors->has('bride_father_status'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('bride_father_status') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="bride_mother_name">Mother's Name</label>
                    <input type="text" class="form-control" id="bride_mother_name" name="bride_mother_name" 
                           value="{{ old('bride_mother_name') }}">
                    @if ($errors->has('bride_mother_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('bride_mother_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="bride_mother_status">Mother's Status</label>
                    <select class="form-control" id="bride_mother_status" name="bride_mother_status">
                        <option value="alive" {{ old('bride_mother_status') == 'alive' ? 'selected' : '' }}>Alive</option>
                        <option value="deceased" {{ old('bride_mother_status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                    @if ($errors->has('bride_mother_status'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('bride_mother_status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h5>Wedding Event Information</h5>
            <p>Please enter the event details.</p>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_name">Event Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="event_name" name="event_name" 
                           value="{{ old('event_name') }}" required>
                    @if ($errors->has('event_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('event_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="event_date">Event Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="event_date" name="event_date" 
                           value="{{ old('event_date') }}" required>
                    @if ($errors->has('event_date'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('event_date') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="event_time">Event Time</label>
                    <input type="time" class="form-control" id="event_time" name="event_time" 
                           value="{{ old('event_time') }}">
                    @if ($errors->has('event_time'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('event_time') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="end_time">End Time</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" 
                           value="{{ old('end_time') }}">
                    @if ($errors->has('end_time'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('end_time') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h5>Location Information</h5>
            <p>Please enter the venue details.</p>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="venue_name">Venue Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="venue_name" name="venue_name" 
                           value="{{ old('venue_name') }}" required>
                    @if ($errors->has('venue_name'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('venue_name') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="address_location">Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="address_location" name="address" 
                           value="{{ old('address') }}" required>
                    @if ($errors->has('address'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="map_embed_url">Map Embed URL</label>
                    <input type="url" class="form-control" id="map_embed_url" name="map_embed_url" 
                           value="{{ old('map_embed_url') }}">
                    @if ($errors->has('map_embed_url'))
                        <div class="text-danger mt-2">
                            {{ $errors->first('map_embed_url') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Continue to Payment</button>
        <a href="{{ route('create-order.step1') }}" class="btn btn-secondary">Back</a>
    </div>
</form>
@endsection