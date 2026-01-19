@extends('admin.layout')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3>Create Review Question for: {{ $course->title }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reviews.store', $course->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                        <textarea name="question" id="question" class="form-control" rows="3" required>{{ old('question') }}</textarea>
                        @error('question')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="option_1" class="form-label">Option 1 <span class="text-danger">*</span></label>
                        <input type="text" name="option_1" id="option_1" class="form-control" value="{{ old('option_1') }}" required>
                        @error('option_1')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="option_2" class="form-label">Option 2 <span class="text-danger">*</span></label>
                        <input type="text" name="option_2" id="option_2" class="form-control" value="{{ old('option_2') }}" required>
                        @error('option_2')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="option_3" class="form-label">Option 3 <span class="text-danger">*</span></label>
                        <input type="text" name="option_3" id="option_3" class="form-control" value="{{ old('option_3') }}" required>
                        @error('option_3')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="option_4" class="form-label">Option 4 (Optional)</label>
                        <input type="text" name="option_4" id="option_4" class="form-control" value="{{ old('option_4') }}">
                        @error('option_4')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="option_5" class="form-label">Option 5 (Optional)</label>
                        <input type="text" name="option_5" id="option_5" class="form-control" value="{{ old('option_5') }}">
                        @error('option_5')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', 0) }}" min="0">
                        @error('order')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.reviews.index', $course->id) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

