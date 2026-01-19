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

            .posts__thumb {
                width: 145px !important;
            }
        }

        .blog-content h4 {
            margin-bottom: 0.5rem;
            margin-top: 1.5rem;
        }

        .blog-content p {
            margin-bottom: 1rem;
        }

        .blog-content ul {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        .posts__thumb {
            object-fit: cover !important;
            width: 180px;
            height: 150px;
        }

        p {
            margin-top: 5px;
        }
    </style>

    <main>
        <section class="blog-details-area pt-150 pb-105 pt-md-100 pb-md-55 pt-xs-100 pb-xs-55">
            <div class="container">
                <div class="row">
                    <!-- Blog Content Area -->
                    <div class="col-lg-8">
                        <div class="blog-details-box mb-45">
                            <h2>{{ $blog->title }}</h2>

                            @if ($blog->featured_image)
                                <img class="img-fluid blog-details-img mb-35"
                                    src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}">
                            @endif

                            <!-- Blog Meta -->
                            <div class="blog-meta mb-30">
                                {{-- <span class="me-3"><i class="far fa-user"></i> {{ $blog->author }}</span> --}}
                                <span class="me-3"><i class="far fa-calendar"></i>
                                    {{ $blog->published_at->format('F d, Y') }}</span>
                                <span><i class="far fa-eye"></i> {{ $blog->views }} views</span>
                            </div>

                            <!-- Excerpt -->
                            @if ($blog->excerpt)
                                <div class="blog-excerpt mb-30">
                                    <p class="lead">{!! $blog->excerpt !!}</p>
                                </div>
                            @endif

                            <!-- Blog Content -->
                            <div class="blog-content blog-details-text mb-30">
                                {!! $blog->content !!}
                            </div>

                            <!-- Call to Action (End of Post) -->
                            <div class="cta-block-end p-5 mb-5 text-center" style="background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%); border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <h3 style="color: #333; font-weight: bold;">Ready to Turn Learning into Action?</h3>
                                <p class="lead mb-4" style="color: #555;">Join 25,000+ kids learning farming, sustainability & food science.</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="{{ route('courses.index') }}" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold text-white shadow-sm" style="background-color: #ffa03f; border: none;">Explore Our Courses</a>
                                </div>
                                <p class="mt-3 small text-muted">First lesson free!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Sidebar Area -->
                    <div class="col-lg-4">
                        <div class="blog-widget-area" style="padding: 0 !important; margin: 0 !important;">
                            <!-- Sidebar CTA (Static) -->
                            <div class="widget mb-20" style="padding: 0 !important; margin-left: 0 !important; margin-right: 0 !important; width: 100% !important;">
                                <div class="sidebar-cta p-2 text-center" style="background: #fff; border: 1px solid #eee; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); width: 100%;">
                                    <h4 class="mb-2" style="font-size: 18px; font-weight: bold;">New to Little Farmers?</h4>
                                    <!-- Using general blog image as thumbnail if specific course img not available (or placeholder) -->
                                    <img src="{{ asset('assets/img/blog/recent-1.jpg') }}" alt="Course Thumbnail" class="img-fluid rounded mb-2"> 
                                    <p class="mb-2 text-muted" style="font-size: 14px;">Start with our best-selling "The Little Farmer Online Course"!</p>
                                    <a href="{{ route('course.details', ['id' => 1]) }}" class="btn btn-success w-50 rounded-pill fw-bold">Enroll Now</a>
                                </div>
                            </div>
                            <!-- Recent Posts Widget -->
                            <div class="widget mb-50 ps-0 m-0" style="padding: 0 !important; width: 100% !important;">
                                <div class="blog-categories-widget ps-0 m-0">
                                    <h4 class="sub-title pb-2 mb-3 pt-2">Recent Posts</h4>
                                    <ul class="blog-recent-post ps-0 m-0 d-lg-flex flex-lg-column">
                                        @foreach ($recentBlogs as $recentBlog)
                                            <li style="width: 100% !important; padding: 0 !important; margin: 0 !important;">
                                                <div class="posts mb-2" style="width: 100% !important;">
                                                    @if ($recentBlog->featured_image)
                                                        <img src="{{ asset('storage/' . $recentBlog->featured_image) }}"
                                                            alt="{{ $recentBlog->title }}" class="posts__thumb mb-10" style="width: 100% !important;">
                                                    @endif
                                                    <p class="mb-1">{{ $recentBlog->published_at->format('F d, Y') }}</p>
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('blog.show', $recentBlog->slug) }}">
                                                            {{ $recentBlog->title }}
                                                        </a>
                                                    </h6>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Social Media Widget -->
                            <div class="widget mb-50">
                                <h4 class="sub-title pb-20 mb-25 pt-25">Follow Us on Social Media</h4>
                                <div class="blog-social-widget">
                                    <div class="social-media mb-30 blog-social-widget">
                                        <a href="https://www.linkedin.com/in/we-little-farmer-4bbb18380"><i
                                                class="fab fa-linkedin-in"></i></a>
                                        <a href="https://www.instagram.com/welittlefarmer/?igsh=ODB0eHE5eXBsajF3#"><i
                                                class="fab fa-instagram"></i></a>
                                    </div>
                                </div>
                            </div>

                            <!-- Tags -->
                            @if ($blog->tags->count() > 0)
                                <div class="blog-tags mt-40">
                                    <h5 class="mb-3">Tags:</h5>
                                    <div class="courses-tag-btn cat-btn tag-btn">
                                        @foreach ($blog->tags as $tag)
                                            <a href="{{ route('blogs.tag', $tag->slug) }}">{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // In-Article CTA Injection
        const blogContent = document.querySelector('.blog-content');
        if (blogContent) {
            const paragraphs = blogContent.querySelectorAll('p');
            // Inject after the 2nd paragraph if it exists, otherwise append
            if (paragraphs.length >= 2) {
                const ctaDiv = document.createElement('div');
                ctaDiv.className = 'cta-box-in-article p-4 mb-4 mt-4';
                ctaDiv.style.backgroundColor = '#f0f7ff';
                ctaDiv.style.borderLeft = '5px solid #007bff';
                ctaDiv.style.borderRadius = '5px';
                ctaDiv.innerHTML = `
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 style="color: #0056b3; font-weight: bold; margin-bottom: 5px;">Want your child to learn these skills hands-on?</h5>
                            <p class="mb-0">Explore our interactive farming courses designed for kids!</p>
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <a href="{{ route('courses.index') }}" class="btn btn-primary btn-sm rounded-pill px-3">Explore Courses</a>
                        </div>
                    </div>
                `;
                paragraphs[1].after(ctaDiv);
            }
        }
    });
</script>
@endpush
