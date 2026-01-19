@extends('admin.layout')

@push('head')
    <title>Admin Dashboard - Little Farmers Academy</title>
@endpush

@section('content')

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2>Welcome back, {{ auth('admin')->user()->name }}! 👋</h2>
                <p class="text-muted">Here's what's happening with your platform</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-0">Total Blogs</h6>
                                <h2 class="mb-0">{{ $stats['total_blogs'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-blog fa-3x opacity-50"></i>
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
                                <h6 class="card-title text-white-50 mb-0">Published</h6>
                                <h2 class="mb-0">{{ $stats['published_blogs'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-check-circle fa-3x opacity-50"></i>
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
                                <h6 class="card-title text-white-50 mb-0">Drafts</h6>
                                <h2 class="mb-0">{{ $stats['draft_blogs'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-file-alt fa-3x opacity-50"></i>
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
                                <h6 class="card-title text-white-50 mb-0">Total Views</h6>
                                <h2 class="mb-0">{{ number_format($stats['total_views']) }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-eye fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Management Stats Section -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-0">Total Users</h6>
                                <h2 class="mb-0">{{ $userStats['total_users'] }}</h2>
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
                                <h6 class="card-title text-white-50 mb-0">Verified Users</h6>
                                <h2 class="mb-0">{{ $userStats['verified_users'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-check-circle fa-3x opacity-50"></i>
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
                                <h6 class="card-title text-white-50 mb-0">Pending Payments</h6>
                                <h2 class="mb-0">{{ $userStats['pending_payment_users'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-hourglass-half fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-0">Unverified Users</h6>
                                <h2 class="mb-0">{{ $userStats['unverified_users'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-user-slash fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PENDING PAYMENTS SECTION - NEW -->
        @php
            $pendingPayments = \App\Models\User::where('payment_status', 1)
                ->with('pendingCourse')
                ->orderBy('payment_submitted_at', 'desc')
                ->get();
        @endphp

        @if ($pendingPayments->count() > 0)
            <div class="row mb-4" id="pending-payments">
                <div class="col-12">
                    <div class="card border-warning" style="border-width: 3px !important;">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-exclamation-triangle"></i>
                                Pending Payments ({{ $pendingPayments->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Course</th>
                                            <th>Amount</th>
                                            <th>Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingPayments as $payment)
                                            <tr>
                                                <td>
                                                    <strong>{{ $payment->name }}</strong><br>
                                                    <small class="text-muted">{{ $payment->email }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $payment->pendingCourse->title ?? 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    @php
                                                        $enrollment = $payment->pendingEnrollment;
                                                        $finalAmount = $enrollment ? $enrollment->amount : ($payment->pendingCourse->price ?? 0);
                                                        $originalAmount = $payment->pendingCourse->price ?? 0;
                                                    @endphp

                                                    @if($enrollment && $enrollment->coupon_code)
                                                        <div>
                                                            <span class="text-decoration-line-through text-muted small">{{ $currencySymbol }}{{ number_format($originalAmount, 2) }}</span>
                                                            <br>
                                                            <span class="badge bg-success">Make {{ $currencySymbol }}{{ number_format($finalAmount, 2) }}</span>
                                                        </div>
                                                        <div class="mt-1">
                                                            <small class="text-primary fw-bold">
                                                                <i class="fas fa-tag"></i> {{ $enrollment->coupon_code }}
                                                            </small>
                                                            @php
                                                                // Try to find referrer
                                                                $referrer = \App\Models\User::where('referral_code', $enrollment->coupon_code)->first();
                                                            @endphp
                                                            @if($referrer)
                                                                <br>
                                                                <small class="text-muted" style="font-size: 0.8em;">
                                                                    Ref: {{ $referrer->name }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="badge bg-success">
                                                            {{ $currencySymbol }}{{ number_format($originalAmount, 2) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $payment->payment_submitted_at->diffForHumans() }}</small><br>
                                                    <small
                                                        class="text-muted">{{ $payment->payment_submitted_at->format('M d, Y h:i A') }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $finalAmountFormatted = $currencySymbol . number_format($finalAmount, 2);
                                                        $couponCode = ($enrollment && $enrollment->coupon_code) ? $enrollment->coupon_code : '';
                                                        
                                                        $referrerEmail = '';
                                                        if($couponCode) {
                                                            $refUser = \App\Models\User::where('referral_code', $couponCode)->first();
                                                            if($refUser) $referrerEmail = $refUser->email;
                                                        }
                                                    @endphp
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        data-user="{{ $payment->name }}"
                                                        data-course="{{ $payment->pendingCourse->title ?? 'N/A' }}"
                                                        data-amount="{{ $finalAmountFormatted }}"
                                                        data-code="{{ $couponCode }}"
                                                        data-referrer="{{ $referrerEmail }}"
                                                        data-action="{{ route('admin.payment.verify', $payment->id) }}"
                                                        onclick="openVerifyModal(this)">
                                                        <i class="fas fa-check"></i> Verify
                                                    </button>

                                                    <form action="{{ route('admin.payment.reject', $payment->id) }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Reject this payment? User will need to submit again.')">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    </form>
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

        <!-- RECENT COURSE ENROLLMENTS SECTION -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap"></i> Recent Course Enrollments
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Course</th>
                                        <th>Amount</th>
                                        <th>Payment Status</th>
                                        <th>Enrolled On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentEnrollments as $enrollment)
                                        <tr>
                                            <td>
                                                <strong>{{ $enrollment->user->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $enrollment->user->email ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $enrollment->course->title ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-info">{{ $currencySymbol }}{{ number_format($enrollment->amount, 2) }}</span>
                                            </td>
                                            <td>
                                                @if ($enrollment->payment_status == 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($enrollment->payment_status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span
                                                        class="badge bg-secondary">{{ ucfirst($enrollment->payment_status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $enrollment->created_at->format('M d, Y') }}</small><br>
                                                <small
                                                    class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $enrollment->user->id) }}"
                                                    class="btn btn-sm btn-info" title="View Student Details">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <p class="text-muted mb-0">No enrollments yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECENT USERS SECTION -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> Recent Users
                        </h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">View All Users</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        {{-- <th>Category</th> --}}
                                        <th>Payment Status</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers as $user)
                                        <tr>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td>
                                                <small>{{ $user->email }}</small>
                                            </td>
                                            {{-- <td>
                                        <span class="badge bg-info">{{ $user->category ?? 'N/A' }}</span>
                                    </td> --}}
                                            <td>
                                                @if ($user->payment_status == 0)
                                                    <span class="badge bg-secondary">No Payment</span>
                                                @elseif($user->payment_status == 1)
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($user->payment_status == 2)
                                                    <span class="badge bg-success">Verified</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $user->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $user->id) }}"
                                                    class="btn btn-sm btn-info" title="View User Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <p class="text-muted mb-0">No users yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Blogs</h5>
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Views</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBlogs as $blog)
                                        <tr>
                                            <td>
                                                <strong>{{ Str::limit($blog->title, 40) }}</strong>
                                            </td>
                                            <td>
                                                @if ($blog->is_published)
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-warning">Draft</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="far fa-eye"></i> {{ $blog->views }}
                                            </td>
                                            <td>{{ $blog->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.blogs.edit', $blog) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted mb-0">No blogs yet. Create your first one!</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create New Blog
                            </a>
                            <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> Manage All Blogs
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-users"></i> Manage Users
                            </a>
                            <a href="{{ route('admin.referrals.settings') }}" class="btn btn-outline-primary">
                                <i class="fas fa-gift"></i> Manage Referrals
                            </a>
                            <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary" target="_blank">
                                <i class="fas fa-external-link-alt"></i> View Website
                            </a>

                            @if ($pendingPayments->count() > 0)
                                <hr>
                                <div class="alert alert-warning mb-0">
                                    <strong><i class="fas fa-bell"></i> {{ $pendingPayments->count() }} Pending
                                        Payment(s)</strong>
                                    <p class="small mb-0 mt-1">Please verify payments to activate users</p>
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <div class="alert alert-info mb-0">
                            <h6><i class="fas fa-info-circle"></i> Tips</h6>
                            <ul class="mb-0 small">
                                <li>Publish blogs regularly to keep audience engaged</li>
                                <li>Use relevant tags for better organization</li>
                                <li>Add featured images for visual appeal</li>
                                <li>Verify payments within 24 hours</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .opacity-50 {
            opacity: 0.5;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card.border-warning {
            animation: fadeIn 0.5s ease-out;
        }
    </style>

@endsection

<!-- Payment Verification Modal -->
<div class="modal fade" id="verifyPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle"></i> Verify Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <h4>Confirm Payment?</h4>
                <p class="text-muted mb-4">Please verify the details below before proceeding.</p>
                
                <div class="card bg-light border-0 mb-3">
                    <div class="card-body text-start">
                        <div class="row mb-2">
                            <div class="col-5 text-muted">Student:</div>
                            <div class="col-7 fw-bold" id="modalUser"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-muted">Course:</div>
                            <div class="col-7 fw-bold" id="modalCourse"></div>
                        </div>
                        <hr>
                        <div class="row mb-2 align-items-center">
                            <div class="col-5 text-muted">Amount Received:</div>
                            <div class="col-7 text-success fw-bold fs-5" id="modalAmount"></div>
                        </div>
                        <div class="row mb-0" id="modalDiscountRow" style="display: none;">
                             <div class="col-5 text-muted">Coupon Code:</div>
                             <div class="col-7">
                                 <span class="badge bg-primary" id="modalCode"></span>
                             </div>
                        </div>
                        <div class="row mb-0 mt-2" id="modalReferrerRow" style="display: none;">
                             <div class="col-5 text-muted">Referrer:</div>
                             <div class="col-7 small text-dark" id="modalReferrer"></div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning small text-start">
                    <i class="fas fa-exclamation-triangle"></i> This will immediately grant the user access to the course content.
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <form id="verifyForm" action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success px-4">
                        Yes, Verify Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function openVerifyModal(element) {
        // Get data from data attributes
        const user = element.getAttribute('data-user');
        const course = element.getAttribute('data-course');
        const amount = element.getAttribute('data-amount');
        const code = element.getAttribute('data-code');
        const referrer = element.getAttribute('data-referrer');
        const actionUrl = element.getAttribute('data-action');

        // Update Modal Content
        document.getElementById('modalUser').textContent = user;
        document.getElementById('modalCourse').textContent = course;
        document.getElementById('modalAmount').textContent = amount;
        document.getElementById('verifyForm').action = actionUrl;

        // Handle Coupon & Referrer
        const discountRow = document.getElementById('modalDiscountRow');
        const referrerRow = document.getElementById('modalReferrerRow');
        const modalCode = document.getElementById('modalCode');
        const modalReferrer = document.getElementById('modalReferrer');
        
        if (code && code !== 'null' && code !== '') {
            modalCode.textContent = code;
            discountRow.style.display = 'flex';
            
            // Show referrer if exists
            if (referrer && referrer !== '') {
                modalReferrer.textContent = referrer;
                referrerRow.style.display = 'flex';
            } else {
                referrerRow.style.display = 'none';
            }
        } else {
            discountRow.style.display = 'none';
            referrerRow.style.display = 'none';
        }

        // Show Modal
        var myModal = new bootstrap.Modal(document.getElementById('verifyPaymentModal'));
        myModal.show();
    }
</script>
@endsection
