@extends('layouts.admin')

@section('title', $title ?? 'Create Order')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $title ?? 'Create Order' }}</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <!-- Wizard Progress -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Creation Wizard</h6>
            </div>
            <div class="card-body">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" 
                        style="width: {{ $progress ?? 0 }}%" 
                        aria-valuenow="{{ $progress ?? 0 }}" 
                        aria-valuemin="0" 
                        aria-valuemax="100">
                        Step {{ $step ?? 1 }} of 7
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col">
                        <div class="wizard-steps d-flex justify-content-between">
                            <div class="step {{ request()->routeIs('create-order.step1') ? 'active' : '' }}">1. Package</div>
                            <div class="step {{ request()->routeIs('create-order.step2') ? 'active' : '' }}">2. Client</div>
                            <div class="step {{ request()->routeIs('create-order.step3') ? 'active' : '' }}">3. Couple</div>
                            <div class="step {{ request()->routeIs('create-order.step4') ? 'active' : '' }}">4. Payment</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wizard Content -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Create Order' }}</h6>
                @if(!request()->routeIs('create-order.step1'))
                <form method="POST" action="{{ route('create-order.cancel') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary">Cancel Order</button>
                </form>
                @endif
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('wizard-content')
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.wizard-steps {
    width: 100%;
}

.step {
    text-align: center;
    padding: 10px;
    border-radius: 5px;
    background-color: #f8f9fc;
    color: #858796;
    flex: 1;
    margin: 0 5px;
}

.step.active {
    background-color: #4e73df;
    color: white;
    font-weight: bold;
}

.progress {
    height: 30px;
}

.progress-bar {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection