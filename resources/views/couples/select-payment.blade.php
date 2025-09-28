@extends('layouts.admin')

@section('title', $title ?? 'Select Payment Method')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Select Payment Method' }}</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Select Payment Method' }}</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p><strong>Couple:</strong> {{ $couple->groom_name }} & {{ $couple->bride_name }}</p>
                    <p><strong>Wedding Date:</strong> {{ $couple->wedding_date->format('d M Y') }}</p>
                </div>
                
                @if(isset($processPaymentRoute))
                <form method="POST" action="{{ $processPaymentRoute }}">
                    @csrf
                    
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="payment_method_id">Payment Method</label>
                            <select class="form-control" id="payment_method_id" name="payment_method_id" required>
                                <option value="">Select a payment method</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                        {{ $method->payment_method_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('payment_method_id'))
                                <div class="mt-2 text-danger">
                                    {{ $errors->first('payment_method_id') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Process Payment</button>
                    @if(isset($indexRoute))
                    <a href="{{ $indexRoute }}" class="btn btn-secondary">Cancel</a>
                    @endif
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection