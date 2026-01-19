@extends('main')

@push('head')
    <title>Little Farmers Academy - Online Farming Courses for Kids</title>
@endpush

@section('content')

    <style>
        .page-title-area {
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            .page-title-area {
                margin-top: 80px;
                height: 300px;
            }
        }

        .z-blogs__thumb {
            overflow: hidden;
            border-radius: 10px;
        }

        .z-blogs__thumb img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .z-blogs__thumb:hover img {
            transform: scale(1.05);
        }

        .blog-excerpt {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <!--slider-area start-->
    <section class="slider-area pb-xs-35">
    </section>
    <!--slider-area end-->

    <main>
        <!-- Blog Area Start -->
        <section class="blog-area">
            <div class="blog-bg pt-150 pb-120 pt-md-100 pb-md-70 pt-xs-100 pb-xs-70">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="section-title text-center mb-60">
                                <h2 class="mb-25">
                                    @if (isset($tag))
                                        Blogs Tagged: {{ $tag->name }}
                                    @else
                                        Stay Updated with Our Latest News & Innovations
                                    @endif
                                </h2>
                                <p>Explore our wide range of topics, including advancements in agricultural technology,
                                    educational content on farming, and inspiring stories from our students and instructors.
                                    Our blog is designed to keep you informed and engaged with the latest trends and
                                    insights in the world of agriculture.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @forelse($blogs as $blog)
                            <div class="col-lg-4 col-md-6">
                                <div class="z-blogs mb-30">
                                    <div class="z-blogs__thumb mb-30">
                                        <a href="{{ route('blog.show', $blog->slug) }}">
                                            @if ($blog->featured_image)
                                                <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                                    alt="{{ $blog->title }}">
                                            @else
                                                <img src="{{ asset('assets/img/blog/default-blog.jpg') }}"
                                                    alt="{{ $blog->title }}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="z-blogs__content">
                                        <!-- Tags -->
                                        @if ($blog->tags->count() > 0)
                                            <div class="mb-15">
                                                @foreach ($blog->tags->take(2) as $blogTag)
                                                    <a href="{{ route('blogs.tag', $blogTag->slug) }}"
                                                        class="badge text-white me-1" style="background-color: #FCB252;">
                                                        {{ $blogTag->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Blog Title -->
                                        <h4 class="sub-title mb-15">
                                            <a href="{{ route('blog.show', $blog->slug) }}">
                                                {{ $blog->title }}
                                            </a>
                                        </h4>

                                        <!-- Excerpt -->
                                        @if ($blog->excerpt)
                                            <p class="blog-excerpt">{!! Str::limit($blog->excerpt, 150) !!}</p>
                                        @else
                                            <p class="blog-excerpt">{!! Str::limit($blog->content, 150) !!}</p>
                                        @endif

                                        <!-- Read More Link -->
                                        <a href="{{ route('blog.show', $blog->slug) }}" class="btn-link"
                                            style="text-decoration: none;">
                                            <p style="color:#FCB252; font-weight: 600; font-size: 14px;">Read More</p>
                                        </a>


                                        <!-- Meta Info -->
                                        <div class="z-blogs__meta d-sm-flex justify-content-between pt-20 border-top mt-20">
                                            <span>
                                                <i class="far fa-calendar"></i>
                                                {{ $blog->published_at->format('F d, Y') }}
                                            </span>
                                            {{-- <span>
                                                <i class="far fa-user"></i>
                                                {{ $blog->author }}
                                            </span> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <h4>No blogs found</h4>
                                    <p>Check back soon for new content!</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($blogs->hasPages())
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-center mt-40">
                                    {{ $blogs->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <!-- Blog Area End -->
    </main>

@endsection
