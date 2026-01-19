@extends('admin.layout')

@section('title', 'Send Course Exploration Reminders')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Send Course Exploration Reminders</h2>
                <p class="text-muted">Encourage registered users who haven't explored any courses yet</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Enrollments
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
                                <h6 class="card-title text-white-50 mb-0">Users Without Inquiries</h6>
                                <h2 class="mb-0">{{ $stats['users_without_inquiries'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-user-times fa-3x opacity-50"></i>
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
                                <h6 class="card-title text-white-50 mb-0">Total Users</h6>
                                <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-users fa-3x opacity-50"></i>
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
                                <h6 class="card-title text-white-50 mb-0">Users With Inquiries</h6>
                                <h2 class="mb-0">{{ $stats['users_with_inquiries'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-user-check fa-3x opacity-50"></i>
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
                                <h6 class="card-title text-white-50 mb-0">Reminders Sent Today</h6>
                                <h2 class="mb-0">{{ $stats['reminders_sent_today'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-paper-plane fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.enrollments.sendBulkRemindersToNonInquirers') }}" method="POST"
                            id="bulkReminderForm">
                            @csrf
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <input type="checkbox" id="selectAll" class="form-check-input me-2">
                                        Select All
                                    </h5>
                                    <small class="text-muted">Selected: <span id="selectedCount">0</span></small>
                                </div>
                                <button type="submit" class="btn btn-primary" id="sendBulkBtn" disabled>
                                    <i class="fas fa-paper-plane"></i> Send Selected Reminders
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" disabled>
                                </th>
                                <th>User</th>
                                <th>Email Verified</th>
                                <th>Registered</th>
                                <th>Last Reminder</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usersWithoutInquiries as $user)
                                @php
                                    $canSendReminder =
                                        !$user->last_reminder_sent_at ||
                                        $user->last_reminder_sent_at->lt(now()->subDays(7));
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                            class="form-check-input reminder-checkbox" form="bulkReminderForm"
                                            @if (!$canSendReminder) disabled title="Reminder sent recently" @endif>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle-sm me-2">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong><br>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $user->created_at->format('M d, Y') }}</small><br>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if ($user->last_reminder_sent_at)
                                            <small>{{ $user->last_reminder_sent_at->format('M d, Y') }}</small><br>
                                            <small
                                                class="text-muted">{{ $user->last_reminder_sent_at->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($canSendReminder)
                                            <form
                                                action="{{ route('admin.enrollments.sendReminderToNonInquirer', $user) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Send course exploration reminder to {{ $user->name }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" title="Send Reminder">
                                                    <i class="fas fa-paper-plane"></i> Send Reminder
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled
                                                title="Reminder sent recently (wait 7 days)">
                                                <i class="fas fa-check"></i> Sent Recently
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-muted mb-0">All registered users have made inquiries! 🎉</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($usersWithoutInquiries->hasPages())
                    <div class="mt-4">
                        {{ $usersWithoutInquiries->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Alert -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Reminder System Explanation</h5>
                    <ul class="mb-0">
                        <li><strong>Target:</strong> Registered users who have NEVER made any course enrollment inquiry</li>
                        <li><strong>Purpose:</strong> Encourage them to explore available courses and start learning</li>
                        <li><strong>Frequency:</strong> Maximum once every 7 days per user</li>
                        <li><strong>Email Content:</strong> Features available courses and motivates them to start learning
                        </li>
                        <li><strong>Excluded:</strong> Users who already have inquiries (they're being followed up
                            separately)</li>
                    </ul>
                </div>
            </div>
        </div>

        @if ($availableCourses->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Available Courses (These will be promoted in the email)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($availableCourses->take(6) as $course)
                                    <div class="col-md-4 mb-3">
                                        <div class="course-card p-3 border rounded">
                                            <h6>{{ $course->title }}</h6>
                                            <p class="text-muted small mb-2">{{ Str::limit($course->description, 60) }}
                                            </p>
                                            <strong class="text-primary">₹{{ number_format($course->price, 0) }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .opacity-50 {
            opacity: 0.5;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .avatar-circle-sm {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .reminder-checkbox:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .course-card {
            background: #f8f9fa;
            transition: all 0.3s;
        }

        .course-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const reminderCheckboxes = document.querySelectorAll('.reminder-checkbox:not([disabled])');
            const selectedCountSpan = document.getElementById('selectedCount');
            const sendBulkBtn = document.getElementById('sendBulkBtn');

            function updateSelection() {
                const checkedCount = document.querySelectorAll('.reminder-checkbox:checked').length;
                selectedCountSpan.textContent = checkedCount;
                sendBulkBtn.disabled = checkedCount === 0;
            }

            selectAllCheckbox.addEventListener('change', function() {
                reminderCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelection();
            });

            reminderCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(reminderCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                    updateSelection();
                });
            });

            document.getElementById('bulkReminderForm').addEventListener('submit', function(e) {
                const count = document.querySelectorAll('.reminder-checkbox:checked').length;
                if (!confirm(`Send course exploration reminders to ${count} user(s)?`)) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
