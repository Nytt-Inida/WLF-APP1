@extends('admin.layout')
@push('head')
    <title>Edit Blog - Little Farmers Academy</title>
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row edit_blog">
            <div class="col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Edit Blog</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Blog Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $blog->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Author -->
                            <div class="mb-3">
                                <label for="author" class="form-label">Author *</label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror"
                                    id="author" name="author" value="{{ old('author', $blog->author) }}" required>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Excerpt -->
                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt (Short Description)</label>
                                <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerptEditor" name="excerpt">{{ old('excerpt', $blog->excerpt) }}</textarea>

                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">A brief summary that appears in blog listings</small>
                            </div>

                            <!-- Content with Summernote -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Blog Content *</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="summernote" name="content">{{ old('content', $blog->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Use the editor toolbar to format your content. No
                                    HTML knowledge needed!
                                </small>
                            </div>

                            <!-- Featured Image -->
                            <div class="mb-3">
                                <label for="featured_image" class="form-label">Featured Image</label>

                                @if ($blog->featured_image)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                            alt="Children inspired by robotics farming class"
                                            style="max-width: 300px; height: auto;" class="img-thumbnail d-block">
                                        <small class="text-muted mt-1 d-block">Current Image</small>
                                    </div>
                                @endif

                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                    id="featured_image" name="featured_image" accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload a new image to replace the current one. Recommended size:
                                    1200x630 pixels</small>
                            </div>

                            <!-- Tags -->
                            <div class="mb-3">
                                <label class="form-label">Tags</label>
                                <div class="row">
                                    @foreach ($tags as $tag)
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tags[]"
                                                    value="{{ $tag->id }}" id="tag{{ $tag->id }}"
                                                    {{ $blog->tags->contains($tag->id) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tag{{ $tag->id }}">
                                                    {{ $tag->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Publish Settings -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1"
                                            id="is_published" {{ $blog->is_published ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">
                                            <strong>Published</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted">Toggle to publish/unpublish this blog</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="published_at" class="form-label">Publish Date</label>
                                    <input type="datetime-local" class="form-control" id="published_at" name="published_at"
                                        value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}">
                                    <small class="text-muted">The date this blog was/will be published</small>
                                </div>
                            </div>

                            <!-- Blog Stats -->
                            <div class="alert alert-info mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-eye"></i> Views:</strong> {{ $blog->views }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-calendar"></i> Created:</strong>
                                        {{ $blog->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-clock"></i> Updated:</strong>
                                        {{ $blog->updated_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                                <div>
                                    <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-info me-2"
                                        target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Preview
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery (required for Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 400,
                minHeight: 300,
                maxHeight: 600,
                placeholder: 'Write your blog content here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                styleTags: ['p', 'h2', 'h3', 'h4'],
                callbacks: {
                    onImageUpload: function(files) {
                        uploadImage(files[0]);
                    }
                }
            });

            $('#excerptEditor').summernote({
                height: 150,
                minHeight: 300,
                maxHeight: 600,
                placeholder: 'Write a short description here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                styleTags: ['p', 'h2', 'h3', 'h4']
            });

            // Image upload function
            function uploadImage(file) {
                let data = new FormData();
                data.append('file', file);
                data.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('admin.blogs.upload-image') }}', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.location) {
                            $('#summernote').summernote('insertImage', result.location);
                        } else {
                            alert('Image upload failed!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Image upload failed!');
                    });
            }
        });
    </script>
@endsection
