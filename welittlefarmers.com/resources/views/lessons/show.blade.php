@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">{{ $lesson->title }}</h2>
    <div class="embed-responsive embed-responsive-16by9 mb-4">
        <iframe class="embed-responsive-item" src="{{ $lesson->video_url }}" allowfullscreen></iframe>
    </div>
    <p>{{ $lesson->content }}</p>
    <form method="POST" action="{{ route('lessons.complete', $lesson->id) }}">
        @csrf
        <button type="submit" class="btn btn-success">Mark as Completed</button>
    </form>
</div>
@endsection
