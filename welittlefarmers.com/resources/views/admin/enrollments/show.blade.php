@extends('admin.layout')

@section('title', 'Enrollment Details - ' . $enrollment->name)

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Back to Enrollments
                </a>
                <h2>Enrollment Inquiry Details</h2>
            </div>
        </div>

        <div class="row">
            <!-- Student Info Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar-circle-lg mx-auto mb-3">
                            {{ strtoupper(substr($enrollment->name, 0, 1)) }}
                        </div>
                        <h4>{{ $enrollment->name }}</h4>
                        <p class="text-muted">{{ $enrollment->email }}</p>

                        <span class="badge {{ $enrollment->getStatusBadgeClass() }} mb-3 fs-6">
                            {{ $enrollment->getStatusText() }}
                        </span>

                        <hr>

                        <div class="text-start">
                            <p><strong>Inquiry ID:</strong> #{{ $enrollment->id }}</p>
                            <p><strong>Submitted:</strong> {{ $enrollment->created_at->format('F d, Y') }}</p>
                            <p><strong>Time Ago:</strong> {{ $enrollment->created_at->diffForHumans() }}</p>
                            @if ($enrollment->phone)
                                <p>
                                    <strong>Phone:</strong>
                                    <a href="tel:{{ $enrollment->phone }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-phone"></i> {{ $enrollment->phone }}
                                    </a>
                                </p>
                            @endif
                        </div>

                        <hr>

                        <!-- Quick Actions -->
                        <div class="d-grid gap-2">
                            @if ($enrollment->phone)
                                <a href="tel:{{ $enrollment->phone }}" class="btn btn-success">
                                    <i class="fas fa-phone"></i> Call Student
                                </a>
                            @endif
                            <a href="mailto:{{ $enrollment->email }}" class="btn btn-primary">
                                <i class="fas fa-envelope"></i> Send Email
                            </a>
                            @if ($enrollment->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $enrollment->phone) }}"
                                    target="_blank" class="btn btn-success">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                            @endif
                        </div>

                        <hr>

                        <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this enrollment inquiry?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-trash"></i> Delete Inquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Details & Actions -->
            <div class="col-md-8">
                <!-- Course Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Course Information</h5>
                    </div>
                    <div class="card-body">
                        @if ($enrollment->course)
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>{{ $enrollment->course->title }}</h4>
                                    @if ($enrollment->course->description)
                                        <p class="text-muted">{{ Str::limit($enrollment->course->description, 200) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="info-box p-3 bg-light rounded">
                                        <h6 class="text-muted mb-1">Course ID</h6>
                                        <p class="mb-0"><strong>#{{ $enrollment->course->id }}</strong></p>
                                    </div>
                                </div>
                                @if ($enrollment->course->price)
                                    <div class="col-md-4">
                                        <div class="info-box p-3 bg-light rounded">
                                            <h6 class="text-muted mb-1">Price</h6>
                                            <p class="mb-0">
                                                <strong>₹{{ number_format($enrollment->course->price, 2) }}</strong></p>
                                        </div>
                                    </div>
                                @endif
                                @if ($enrollment->course->duration)
                                    <div class="col-md-4">
                                        <div class="info-box p-3 bg-light rounded">
                                            <h6 class="text-muted mb-1">Duration</h6>
                                            <p class="mb-0"><strong>{{ $enrollment->course->duration }}</strong></p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> Course information not available
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Update Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Update Status</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.enrollments.updateStatus', $enrollment) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="pending" {{ $enrollment->status === 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="contacted"
                                            {{ $enrollment->status === 'contacted' ? 'selected' : '' }}>
                                            Contacted
                                        </option>
                                        <option value="enrolled"
                                            {{ $enrollment->status === 'enrolled' ? 'selected' : '' }}>
                                            Enrolled
                                        </option>
                                        <option value="rejected"
                                            {{ $enrollment->status === 'rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="admin_notes" class="form-label">Admin Notes</label>
                                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="4"
                                    placeholder="Add notes about this inquiry...">{{ $enrollment->admin_notes }}</textarea>
                                <small class="text-muted">Add any relevant information about contacts made, issues, or next
                                    steps.</small>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Timeline / Activity -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <!-- Inquiry Submitted -->
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Inquiry Submitted</h6>
                                    <p class="text-muted mb-0">
                                        <small>{{ $enrollment->created_at->format('F d, Y h:i A') }}</small>
                                    </p>
                                    <p class="mb-0">Student submitted enrollment inquiry for
                                        {{ $enrollment->course->title ?? 'this course' }}</p>
                                </div>
                            </div>

                            @if ($enrollment->contacted_at)
                                <!-- Contacted -->
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Student Contacted</h6>
                                        <p class="text-muted mb-0">
                                            <small>{{ $enrollment->contacted_at->format('F d, Y h:i A') }}</small>
                                        </p>
                                        @if ($enrollment->contactedByAdmin)
                                            <p class="mb-0">Contacted by {{ $enrollment->contactedByAdmin->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if ($enrollment->status === 'enrolled')
                                <!-- Enrolled -->
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Successfully Enrolled</h6>
                                        <p class="text-muted mb-0">
                                            <small>{{ $enrollment->updated_at->format('F d, Y h:i A') }}</small>
                                        </p>
                                        <p class="mb-0">Student has been enrolled in the course</p>
                                    </div>
                                </div>
                            @elseif($enrollment->status === 'rejected')
                                <!-- Rejected -->
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Inquiry Rejected</h6>
                                        <p class="text-muted mb-0">
                                            <small>{{ $enrollment->updated_at->format('F d, Y h:i A') }}</small>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Account Info (if user exists) -->
                @if ($enrollment->user)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">User Account Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>User ID:</strong> #{{ $enrollment->user->id }}</p>
                                    <p><strong>Account Created:</strong>
                                        {{ $enrollment->user->created_at->format('F d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <strong>Email Verified:</strong>
                                        @if ($enrollment->user->email_verified_at)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-warning">No</span>
                                        @endif
                                    </p>
                                    <a href="{{ route('admin.users.show', $enrollment->user) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-user"></i> View User Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .avatar-circle-lg {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 40px;
        }

        .info-box {
            transition: all 0.3s;
        }

        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 40px;
            padding-bottom: 30px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item:not(:last-child):before {
            content: '';
            position: absolute;
            left: 9px;
            top: 30px;
            bottom: -10px;
            width: 2px;
            background: #e0e0e0;
        }

        .timeline-marker {
            position: absolute;
            left: 0;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #e0e0e0;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .timeline-content h6 {
            color: #333;
            font-weight: 600;
        }
    </style>
@endsection
