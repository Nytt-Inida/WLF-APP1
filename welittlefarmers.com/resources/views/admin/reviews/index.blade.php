@extends('admin.layout')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Review Questions for: {{ $course->title }}</h3>
                <div>
                    <a href="{{ route('admin.reviews.courses') }}" class="btn btn-secondary">Back to Courses</a>
                    <a href="{{ route('admin.reviews.create', $course->id) }}" class="btn btn-primary">Add Question</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($questions->isEmpty())
                    <div class="alert alert-info">
                        No review questions found. <a href="{{ route('admin.reviews.create', $course->id) }}">Create one now</a>.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Question</th>
                                    <th>Options</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $question)
                                <tr>
                                    <td>{{ $question->order }}</td>
                                    <td>{{ Str::limit($question->question, 50) }}</td>
                                    <td>
                                        <small>
                                            1. {{ Str::limit($question->option_1, 20) }}<br>
                                            2. {{ Str::limit($question->option_2, 20) }}<br>
                                            3. {{ Str::limit($question->option_3, 20) }}<br>
                                            4. {{ Str::limit($question->option_4, 20) }}<br>
                                            5. {{ Str::limit($question->option_5, 20) }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($question->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.reviews.edit', [$course->id, $question->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="{{ route('admin.reviews.toggle', [$course->id, $question->id]) }}" class="btn btn-sm btn-warning">
                                            {{ $question->is_active ? 'Deactivate' : 'Activate' }}
                                        </a>
                                        <form action="{{ route('admin.reviews.destroy', [$course->id, $question->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
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

