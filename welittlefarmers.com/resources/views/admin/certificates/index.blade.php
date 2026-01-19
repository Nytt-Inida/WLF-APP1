@extends('admin.layout')

@section('title', 'Manage Certificates')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Certificate Management</h2>
                <p class="text-muted">Issue certificates to users who completed courses</p>
            </div>
        </div>

        <!-- Error Message -->
        @if (isset($error))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Issue Certificate Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Issue New Certificate</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.enrollments.certificates.issue') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select Course</label>
                            <select name="course_id" class="form-control" id="courseSelect" required>
                                <option value="">-- Select Course --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select User</label>
                            <select name="user_id" class="form-control" id="userSelect" required>
                                <option value="">-- Select User --</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Optional notes about certificate..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-certificate"></i> Issue Certificate
                    </button>
                </form>
            </div>
        </div>

        <!-- Issued Certificates List -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Issued Certificates</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Certificate #</th>
                                <th>User</th>
                                <th>Course</th>
                                <th>Issued By</th>
                                <th>Issued Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($certificatesIssued as $cert)
                                <tr>
                                    <td><strong>{{ $cert->certificate_number ?? 'N/A' }}</strong></td>
                                    <td>{{ optional($cert->user)->name ?? 'Unknown' }}</td>
                                    <td>{{ optional($cert->course)->title ?? 'Unknown' }}</td>
                                    <td>{{ optional($cert->issuedByAdmin)->name ?? 'Unknown' }}</td>
                                    <td>{{ $cert->issued_at?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.enrollments.certificates.download', $cert) }}"
                                            class="btn btn-sm btn-info" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="{{ route('admin.enrollments.certificates.revoke', $cert) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Revoke this certificate?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No certificates issued yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($certificatesIssued->hasPages())
                    <div class="mt-4">
                        {{ $certificatesIssued->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Load users without certificates for selected course
        document.getElementById('courseSelect').addEventListener('change', function() {
            const courseId = this.value;
            if (!courseId) {
                document.getElementById('userSelect').innerHTML = '<option value="">-- Select User --</option>';
                return;
            }

            fetch(`/admin/enrollments/certificates/get-users/${courseId}`)
                .then(r => r.json())
                .then(users => {
                    let html = '<option value="">-- Select User --</option>';
                    users.forEach(user => {
                        html += `<option value="${user.id}">${user.name} (${user.email})</option>`;
                    });
                    document.getElementById('userSelect').innerHTML = html;
                });
        });
    </script>
@endsection
