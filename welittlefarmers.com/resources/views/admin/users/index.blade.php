@extends('admin.layout')

@push('head')
    <title>User Management - Little Farmers Academy</title>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>User Management</h2>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                <p class="text-muted">Manage and view details of all registered users</p>
            </div>
        </div>

        <!-- User Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-dark">
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
                                <h6 class="card-title text-white-50 mb-0">Verified</h6>
                                <h2 class="mb-0">{{ $stats['verified_users'] }}</h2>
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
                                <h6 class="card-title text-white-50 mb-0">Pending</h6>
                                <h2 class="mb-0">{{ $stats['pending_users'] }}</h2>
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
                                <h6 class="card-title text-white-50 mb-0">Unverified</h6>
                                <h2 class="mb-0">{{ $stats['unverified_users'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-user-slash fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filters & Search</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name, email, or category..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <select name="payment_status" class="form-select">
                            <option value="">All Payment Status</option>
                            <option value="0" {{ request('payment_status') == '0' ? 'selected' : '' }}>No Payment
                            </option>
                            <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>Pending</option>
                            <option value="2" {{ request('payment_status') == '2' ? 'selected' : '' }}>Verified
                            </option>
                        </select>
                    </div>

                    {{-- <div class="col-md-2">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="col-md-2">
                        <select name="sort_by" class="form-select">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Newest
                            </option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Users ({{ $users->total() }} total)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                {{-- <th>Category</th> --}}
                                <th>Country</th>
                                <th>Payment Status</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if ($user->age)
                                            <br><small class="text-muted">Age: {{ $user->age }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $user->email }}</small>
                                    </td>
                                    {{-- <td>
                                @if ($user->category)
                                    <span class="badge bg-info">{{ $user->category }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td> --}}
                                    <td>
                                        @if ($user->country)
                                            <small>{{ $user->country }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
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
                                        <small>{{ $user->created_at->format('M d, Y') }}</small><br>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning"
                                                title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                            No users found. Try adjusting your filters.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{ $users->appends(request()->query())->render() }}
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
@endsection
