@extends('layouts.admin-master')

@section('title', 'People Management')

@section('content')
<!-- Page Heading -->
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">People</h1>
    @if(isset($createRoute))
    <a href="{{ $createRoute }}" class="shadow-sm d-sm-inline-block btn btn-sm btn-primary">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Person
    </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">People List</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>

                                <th>Person Index</th>
                                <th>Couple</th>
                                <th>Role</th>
                                <th>Full Name</th>
                                <th>Photo</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($people as $person)
                            <tr>
                                <td>{{ $person->id }}</td>
                                <td>{{ $person->person_index }}</td>
                                <td>{{ $person->couple->groom_name }} & {{ $person->couple->bride_name }}</td>
                                <td>{{ ucfirst($person->role) }}</td>
                                <td>{{ $person->full_name }}</td>
                                <td>
                                    @if($person->image_url)
                                        <img src="{{ asset($person->image_url) }}" alt="{{ $person->full_name }}" style="max-width: 50px; max-height: 50px;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $person->created_at->format('d M Y') }}</td>
                                <td>
                                    @if(isset($showRoute))
                                    <a href="{{ route($showRoute, $person) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    @if(isset($editRoute))
                                    <a href="{{ route($editRoute, $person) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(isset($deleteRoute))
                                    <form action="{{ route($deleteRoute, $person) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No people found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $people->links() }}
            </div>
        </div>
    </div>
</div>
@endsection