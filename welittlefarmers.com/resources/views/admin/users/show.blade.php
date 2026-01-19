@extends('admin.layout')

@push('head')
    <title>User Details - {{ $user->name }}</title>
@endpush

@section('content')

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>User Details</h2>
                    <div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Basic Info -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">Full Name</label>
                                <p class="fs-5">{{ $user->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">Email</label>
                                <p class="fs-5">
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">Age</label>
                                <p class="fs-5">{{ $user->age ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">Category</label>
                                <p class="fs-5">
                                    @if ($user->category)
                                        <span class="badge bg-info">{{ $user->category }}</span>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">School Name</label>
                                <p class="fs-5">{{ $user->school_name ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">Country</label>
                                <p class="fs-5">{{ $user->country ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">Registered On</label>
                                <p class="fs-5">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">Last Updated</label>
                                <p class="fs-5">{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Status Card -->
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Status</label>
                            @if ($user->payment_status == 0)
                                <p class="fs-5">
                                    <span class="badge bg-secondary">No Payment</span>
                                </p>
                            @elseif($user->payment_status == 1)
                                <p class="fs-5">
                                    <span class="badge bg-warning text-dark">Pending Verification</span>
                                </p>
                            @elseif($user->payment_status == 2)
                                <p class="fs-5">
                                    <span class="badge bg-success">Verified</span>
                                </p>
                            @endif
                        </div>

                        @if ($paymentDetails['pending_course'])
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Pending Course</label>
                                <p class="fs-5">
                                    <strong>{{ $paymentDetails['pending_course']->title }}</strong><br>
                                    <small
                                        class="text-muted">{{ $currencySymbol }}{{ number_format($paymentDetails['pending_course']->price, 2) }}</small>
                                </p>
                            </div>
                        @endif

                        @if ($paymentDetails['submitted_at'])
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Submitted On</label>
                                <p class="fs-5">{{ $paymentDetails['submitted_at']->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif

                        @if ($paymentDetails['verified_at'])
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Verified On</label>
                                <p class="fs-5">{{ $paymentDetails['verified_at']->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif

                        @if ($paymentDetails['verified_by_admin'])
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Verified By</label>
                                <p class="fs-5">{{ $paymentDetails['verified_by_admin']->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrolled Courses -->
        @if ($enrolledCourses->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-book"></i> Enrolled Courses ({{ $enrolledCourses->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            {{-- <th>Progress</th>
                                    <th>Status</th> --}}
                                            <th>Enrolled On</th>
                                            <th>Last Accessed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($enrolledCourses as $progress)
                                            <tr>
                                                <td>
                                                    <strong>{{ $progress->course->title ?? 'N/A' }}</strong>
                                                </td>
                                                {{-- <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $progress->progress ?? 0 }}%;" 
                                                 aria-valuenow="{{ $progress->progress ?? 0 }}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ $progress->progress ?? 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if (($progress->progress ?? 0) >= 100)
                                            <span class="badge bg-success">Completed</span>
                                        @elseif(($progress->progress ?? 0) > 0)
                                            <span class="badge bg-info">In Progress</span>
                                        @else
                                            <span class="badge bg-secondary">Not Started</span>
                                        @endif
                                    </td> --}}
                                                <td>
                                                    <small>{{ $progress->created_at->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $progress->updated_at->diffForHumans() }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Lesson Progress -->
        @if ($lessonProgress->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-graduation-cap"></i> Lesson Progress ({{ $lessonProgress->count() }}
                                lessons)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>Lesson</th>
                                            <th>Status</th>
                                            <th>Completed On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lessonProgress as $progress)
                                            <tr>
                                                <td>
                                                    <strong>{{ $progress->lesson->title ?? 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    @if ($progress->is_completed)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check"></i> Completed
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">In Progress</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($progress->is_completed)
                                                        <small>{{ $progress->updated_at->format('M d, Y h:i A') }}</small>
                                                    @else
                                                        <small class="text-muted">-</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
