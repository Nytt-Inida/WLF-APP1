@extends('admin.layout')

@section('title', 'Manage Enrollments')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Enrollment Inquiries</h2>
                <p class="text-muted">Manage and track course enrollment requests</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.enrollments.export') }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Export to CSV
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-0">Total Inquiries</h6>
                                <h2 class="mb-0">{{ $stats['total_inquiries'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-clipboard-list fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-0">Pending</h6>
                                <h2 class="mb-0">{{ $stats['pending'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-clock fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-0">Contacted</h6>
                                <h2 class="mb-0">{{ $stats['contacted'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-phone fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-0">Enrolled</h6>
                                <h2 class="mb-0">{{ $stats['enrolled'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-check-circle fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ !isset($status) ? 'active' : '' }}"
                            href="{{ route('admin.enrollments.index') }}">
                            All
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($status) && $status === 'pending' ? 'active' : '' }}"
                            href="{{ route('admin.enrollments.filter', 'pending') }}">
                            Pending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($status) && $status === 'contacted' ? 'active' : '' }}"
                            href="{{ route('admin.enrollments.filter', 'contacted') }}">
                            Contacted
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($status) && $status === 'enrolled' ? 'active' : '' }}"
                            href="{{ route('admin.enrollments.filter', 'enrolled') }}">
                            Enrolled
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isset($status) && $status === 'rejected' ? 'active' : '' }}"
                            href="{{ route('admin.enrollments.filter', 'rejected') }}">
                            Rejected
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Inquiries Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiries as $inquiry)
                                <tr>
                                    <td>#{{ $inquiry->id }}</td>
                                    <td>
                                        <strong>{{ $inquiry->name }}</strong><br>
                                        <small class="text-muted">{{ $inquiry->email }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $inquiry->course->title ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        @if ($inquiry->phone)
                                            <a href="tel:{{ $inquiry->phone }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-phone"></i> {{ $inquiry->phone }}
                                            </a>
                                        @else
                                            <span class="text-muted">No phone</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $inquiry->getStatusBadgeClass() }}">
                                            {{ $inquiry->getStatusText() }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $inquiry->created_at->format('M d, Y') }}</small><br>
                                        <small class="text-muted">{{ $inquiry->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.enrollments.show', $inquiry) }}"
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.enrollments.destroy', $inquiry) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this inquiry?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No enrollment inquiries found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($inquiries->hasPages())
                    <div class="mt-4">
                        {{ $inquiries->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .opacity-50 {
            opacity: 0.5;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-pills .nav-link {
            border-radius: 20px;
            padding: 8px 20px;
            margin-right: 10px;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
@endsection
