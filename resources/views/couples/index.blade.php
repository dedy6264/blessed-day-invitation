@extends('layouts.admin')

@section('title', $title ?? 'Couples')

@section('content')

<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Couples' }}</h1>
    {{-- @if (isset($createRoute)) --}}
        <a href="{{ route('create-order.step1') }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-primary">
        {{-- <a href="{{ $createRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-primary"> --}}
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Couple
        </a>
    {{-- @endif --}}
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Couples' }}</h6>
            </div>
            <div class="card-body">
                {{-- @dd($records) --}}
                @if (isset($records) && count($records) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Groom Name</th>
                                    <th>Bride Name</th>
                                    <th>Wedding Date</th>
                                    <th>Transaction Status</th>
                                    <th>Reference No</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr>
                                        <td>{{ $record->client->client_name ?? 'N/A' }}</td>
                                        <td>{{ $record->groom_name ?? 'N/A' }}</td>
                                        <td>{{ $record->bride_name ?? 'N/A' }}</td>
                                        <td>{{ $record->wedding_date ?? 'N/A' }}</td>
                                        <td>
                                            @if($record->transactions->first())
                                                <span class="transaction-status 
                                                    @if($record->transactions->first()->status === 'paid') 
                                                        badge-success 
                                                    @elseif($record->transactions->first()->status === 'pending') 
                                                        badge-warning 
                                                    @elseif($record->transactions->first()->status === 'cancelled') 
                                                        badge-secondary 
                                                    @elseif($record->transactions->first()->status === 'expired') 
                                                        badge-danger 
                                                    @else 
                                                        badge-info 
                                                    @endif">
                                                    {{ ucfirst($record->transactions->first()->status) }}
                                                </span>
                                            @else
                                                <span class="transaction-status badge-light">No Transaction</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $record->transactions->first() ? $record->transactions->first()->reference_no : 'N/A' }}
                                        </td>
                                        <td>
                                            @if (isset($editRoute))
                                                <a href="{{ route($editRoute, $record->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                             <!-- Attendant button -->
                                            <a href="{{ route($attendantRoute, $record->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-users"></i> Attendant
                                            </a>
                                            @if($record->transactions->first()->status !== 'paid') 
                                                @if (isset($deleteRoute))
                                                    <form action="{{ route($deleteRoute, $record->id) }}" method="POST" 
                                                        style="display: inline-block;"
                                                        onsubmit="return confirm('Are you sure you want to delete this couple?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $records->links() }}
                        </div>
                    </div>
                @else
                    <p class="text-center">No couples found.</p>
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

<style>
    .transaction-status {
        font-size: 0.85em;
        padding: 0.25em 0.6em;
        border-radius: 0.375rem;
        font-weight: 500;
    }
    .badge-success { background-color: #28a745; }
    .badge-warning { background-color: #ffc107; color: #212529; }
    .badge-info { background-color: #17a2b8; }
    .badge-danger { background-color: #dc3545; }
    .badge-secondary { background-color: #6c757d; }
    .badge-light { background-color: #f8f9fa; color: #212529; }
</style>
@endsection