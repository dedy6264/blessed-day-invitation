@extends('orders.layout')

@section('title', 'Create Order - Step 4: Payment Method')

@section('wizard-content')
<form method="POST" action="{{ route('create-order.process-step4') }}">
    @csrf
    
    <div class="row">
        <div class="col-12">
            <h5>Payment Method</h5>
            <p>Please select a payment method to complete the order.</p>
            
            @if($paymentMethods->isEmpty())
                <div class="alert alert-warning">
                    No payment methods available. Please create a payment method first.
                </div>
                <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">Create Payment Method</a>
            @else
                <div class="row">
                    @foreach($paymentMethods as $paymentMethod)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $paymentMethod->payment_method_name }}</h5>
                                    @if($paymentMethod->description)
                                        <p class="card-text">{{ $paymentMethod->description }}</p>
                                    @endif
                                    
                                    <ul class="list-unstyled">
                                        @if($paymentMethod->provider_admin_fee > 0)
                                            <li><strong>Provider Admin Fee:</strong> Rp{{ number_format($paymentMethod->provider_admin_fee, 0, ',', '.') }}</li>
                                        @endif
                                        @if($paymentMethod->provider_merchant_fee > 0)
                                            <li><strong>Provider Merchant Fee:</strong> Rp{{ number_format($paymentMethod->provider_merchant_fee, 0, ',', '.') }}</li>
                                        @endif
                                        @if($paymentMethod->admin_fee > 0)
                                            <li><strong>Admin Fee:</strong> Rp{{ number_format($paymentMethod->admin_fee, 0, ',', '.') }}</li>
                                        @endif
                                        @if($paymentMethod->merchant_fee > 0)
                                            <li><strong>Merchant Fee:</strong> Rp{{ number_format($paymentMethod->merchant_fee, 0, ',', '.') }}</li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method_id" 
                                               id="payment_method_{{ $paymentMethod->id }}_payment" value="{{ $paymentMethod->id }}"
                                               {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label" for="payment_method_{{ $paymentMethod->id }}_payment">
                                            Select this payment method
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Complete Order</button>
                    <a href="{{ route('create-order.step3') }}" class="btn btn-secondary">Back</a>
                </div>
            @endif
        </div>
    </div>
</form>
@endsection