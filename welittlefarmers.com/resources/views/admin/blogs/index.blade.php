@extends('admin.layout')

@push('head')
    <title>Little Farmers Academy - Online Farming Courses for Kids</title>
@endpush

@section('content')
    <div class="container-fluid py-4 mt-30">
        <div class="row mb-4 manage_blog">
            <div class="col-md-8">
                <h2>Manage Blogs</h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Published</th>
                                <th>Views</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blogs as $blog)
                                <tr>
                                    <td>
                                        @if ($blog->featured_image)
                                            <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                                alt="Kids harvesting their vegetables in a farm"
                                                style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                        @else
                                            <div style="width: 60px; height: 60px;"
                                                class="bg-secondary rounded d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($blog->title, 50) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $blog->slug }}</small>
                                    </td>
                                    <td>{{ $blog->author }}</td>
                                    <td>
                                        @if ($blog->published_at)
                                            {{ $blog->published_at->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="far fa-eye"></i> {{ $blog->views }}
                                    </td>
                                    <td>
                                        @if ($blog->is_published)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-sm btn-info"
                                                target="_blank" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-sm btn-warning"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this blog?')">
                                                @csrf
                                                @method('DELETE')
                                                <a type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-3">No blogs found. Create your first blog!</p>
                                        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create Blog
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($blogs->hasPages())
                    <div class="mt-4">
                        {{ $blogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
