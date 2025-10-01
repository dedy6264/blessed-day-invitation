@extends('layouts.admin')

@section('title', $title ?? 'Bank Accounts')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">{{ $title ?? 'Bank Accounts' }}</h1>
    @if(isset($createRoute))
    <a href="{{ $createRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-primary">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Bank Account
    </a>
    @endif
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? 'Bank Accounts' }} List</h6>
            </div>
            <div class="card-body">
                @if ($bankAccounts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Wedding Event</th>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>Account Holder</th>
                                    <th>Is Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bankAccounts as $bankAccount)
                                    <tr>
                                        <td>{{ $bankAccount->weddingEvent->event_name }} - {{ $bankAccount->weddingEvent->couple->groom_name }} & {{ $bankAccount->weddingEvent->couple->bride_name }}</td>
                                        <td>{{ $bankAccount->bank_name }}</td>
                                        <td>{{ $bankAccount->account_number }}</td>
                                        <td>{{ $bankAccount->account_holder_name }}</td>
                                        <td>
                                            @if($bankAccount->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($showRoute))
                                            <a href="{{ route($showRoute, $bankAccount) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif
                                            @if(isset($editRoute))
                                            <a href="{{ route($editRoute, $bankAccount) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if(isset($deleteRoute))
                                            <form action="{{ route($deleteRoute, $bankAccount) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this bank account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
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
                            {{ $bankAccounts->links() }}
                        </div>
                    </div>
                @else
                    <p class="text-center">No bank accounts found.</p>
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
@endsection