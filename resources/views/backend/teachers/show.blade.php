@extends('layouts.backend')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Teachers /</span> Teacher Details</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Teacher Information</h5>
                        <div class="float-end">
                            <a href="{{ route('teachers.index') }}" class="btn btn-secondary btn-sm">Back</a>
                            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Employee ID:</strong> {{ $teacher->employee_id }}</p>
                                <p><strong>Name:</strong> {{ $teacher->first_name }} {{ $teacher->last_name }}</p>
                                <p><strong>Gender:</strong> {{ ucfirst($teacher->gender) }}</p>
                                <p><strong>Date of Birth:</strong> {{ $teacher->date_of_birth ? $teacher->date_of_birth->format('d M Y') : 'N/A' }}</p>
                                <p><strong>Religion:</strong> {{ $teacher->religion ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Phone:</strong> {{ $teacher->phone ?? 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ $teacher->user->email ?? 'N/A' }}</p>
                                <p><strong>Qualification:</strong> {{ $teacher->qualification ?? 'N/A' }}</p>
                                <p><strong>Join Date:</strong> {{ $teacher->join_date ? $teacher->join_date->format('d M Y') : 'N/A' }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-{{ $teacher->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($teacher->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Assigned Subjects</h5>
                    </div>
                    <div class="card-body">
                        @if($teacher->subjects->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teacher->subjects as $subject)
                                            <tr>
                                                <td>{{ $subject->name }}</td>
                                                <td>{{ $subject->code }}</td>
                                                <td>{{ ucfirst($subject->type) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No subjects assigned yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection