@extends('layouts.admin')

@section('title', 'View Person')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">View Person</h1>
    <div>
        @if(isset($editRoute))
        <a href="{{ route($editRoute, $record) }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-warning">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit
        </a>
        @endif
        @if(isset($indexRoute))
        <a href="{{ $indexRoute}}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to People
        </a>
        @endif
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Person Details</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $record->id }}</td>
                        </tr>

                        <tr>
                            <th>Person Index:</th>
                            <td>{{ $record->person_index }}</td>
                        </tr>
                        <tr>
                            <th>Couple:</th>
                            <td>{{ $record->couple->groom_name }} & {{ $record->couple->bride_name }}</td>
                        </tr>
                        <tr>
                            <th>Role:</th>
                            <td>{{ ucfirst($record->role) }}</td>
                        </tr>
                        <tr>
                            <th>Full Name:</th>
                            <td>{{ $record->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Photo:</th>
                            <td>
                                @if($record->image_url)
                                    <img src="{{ asset($record->image_url) }}" alt="{{ $record->full_name }}" style="max-width: 200px; max-height: 200px;">
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Additional Info:</th>
                            <td>{{ $record->additional_info ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $record->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $record->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
                
                <h5 class="mt-4">Parent Information</h5>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <th>Father's Name:</th>
                            <td>{{ $record->personParent->father_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Father's Status:</th>
                            <td>{{ ucfirst($record->personParent->father_status ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                            <th>Mother's Name:</th>
                            <td>{{ $record->personParent->mother_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Mother's Status:</th>
                            <td>{{ ucfirst($record->personParent->mother_status ?? 'N/A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection