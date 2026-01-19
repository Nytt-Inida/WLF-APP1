@extends('admin.layout')

@push('head')
    <title>Edit User - {{ $user->name }}</title>
@endpush

@section('content')

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Edit User: {{ $user->name }}</h2>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to User Details
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                           id="age" name="age" value="{{ old('age', $user->age) }}" min="1" max="120">
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                           id="category" name="category" value="{{ old('category', $user->category) }}">
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="school_name" class="form-label">School Name</label>
                            <input type="text" class="form-control @error('school_name') is-invalid @enderror" 
                                   id="school_name" name="school_name" value="{{ old('school_name', $user->school_name) }}">
                            @error('school_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                   id="country" name="country" value="{{ old('country', $user->country) }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Info Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">User Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold text-muted">Registered</label>
                        <p>{{ $user->created_at->format('M d, Y h:i A') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted">Last Updated</label>
                        <p>{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted">Payment Status</label>
                        <p>
                            @if($user->payment_status == 0)
                                <span class="badge bg-secondary">No Payment</span>
                            @elseif($user->payment_status == 1)
                                <span class="badge bg-warning">Pending</span>
                            @elseif($user->payment_status == 2)
                                <span class="badge bg-success">Verified</span>
                            @endif
                        </p>
                    </div>

                    @if($user->pendingCourse)
                    <div class="mb-3">
                        <label class="fw-bold text-muted">Pending Course</label>
                        <p>
                            <strong>{{ $user->pendingCourse->title }}</strong><br>
                            <small class="text-muted">{{ $currencySymbol }}{{ number_format($user->pendingCourse->price, 2) }}</small>
                        </p>
                    </div>
                    @endif

                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="fas fa-info-circle"></i>
                            Note: Only user profile information can be edited here. Payment and course access are managed through separate admin functions.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
