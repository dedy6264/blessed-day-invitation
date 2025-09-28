@extends('orders.layout')

@section('title', 'Create Order - Step 3: Couple Information')

@section('wizard-content')
<form method="POST" action="{{ route('create-order.process-step3') }}">
    @csrf
    
    <div class="row">
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
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Continue to Payment</button>
                <a href="{{ route('create-order.step2') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</form>
@endsection