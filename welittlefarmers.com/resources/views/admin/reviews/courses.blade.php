@extends('admin.layout')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Manage Course Reviews</h3>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
            <div class="card-body">
                @if($courses->isEmpty())
                    <div class="alert alert-info">
                        No courses found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Course Title</th>
                                    <th>Review Questions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                @php
                                    $questionCount = \App\Models\ReviewQuestion::where('course_id', $course->id)->count();
                                    $activeCount = \App\Models\ReviewQuestion::where('course_id', $course->id)->where('is_active', true)->count();
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $course->title }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $activeCount }} Active</span>
                                        <span class="badge bg-secondary">{{ $questionCount }} Total</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.reviews.index', $course->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Manage Questions
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


