@extends('main')

@push('head')
    <title>All Courses - Little Farmers Academy</title>
    <style>
        .page-title-area {
            background-color: #f7f7f7;
            padding: 120px 0 50px; /* Adjusted to reduce empty space */
            margin-bottom: 50px;
        }
        .course__meta .class_count {
            color: black !important;
            font-weight: 600;
        }

        .course__meta .class_btn {
            color: #ffa03f;
            border: solid 1px #ffa03f !important;
            padding: 0.5rem 1rem !important;
            text-align: center;
            width: 70%;
            height: 100%;
            display: block !important;
        }

        .course__meta span:last-child {
            color: #1B212f !important;
        }

        .course__meta .course_price {
            font-weight: 600 !important;
            font-size: 18px !important;
        }
    </style>
@endpush

@section('content')
    <main>
        <!-- Page Title -->
        <section class="page-title-area text-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <h2 class="mb-20 mt-3">All Courses</h2>
                        <ul class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Courses</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Courses Grid -->
        <section class="feature-course pb-100">
            <div class="container">
                <div class="row pt-20">
                    <!-- DB Courses -->
                    @foreach ($courses as $course)
                        <div class="col-lg-4 col-md-6 grid-item">
                            <div class="z-gallery mb-30" onclick="window.location.href='{{ route('course.details', ['id' => $course->id]) }}'" style="cursor: pointer;">
                                <div class="z-gallery__thumb mb-20">
                                    <a>
                                        <img src="{{ asset('assets/img/course/full-course-thumbnail.jpg') }}"
                                            alt="{{ $course->title }}" loading="lazy" class="img-fluid">
                                    </a>
                                </div>
                                <div class="z-gallery__content">
                                    <div class="course__tag mb-15">
                                        <span>{{ $course->age_group }}</span>
                                    </div>
                                    <h4 class="sub-title mb-20">
                                        <a>{{ $course->title }}</a>
                                    </h4>
                                    <div class="course__meta row row-cols-1 gap-3">
                                        <div class="col">
                                            <span class="class_count">
                                                <img class="icon" src="{{ asset('assets/img/icon/time.svg') }}" loading="lazy" alt="icon">
                                                {{ $course->number_of_classes }} Classes
                                            </span>
                                        </div>
                                        <div class="col btn-back">
                                            <div class="d-flex align-items-center justify-content-between w-100">
                                                <div class="course_price d-flex align-items-center p-0 m-0" style="color: #1b212f !important;">
                                                    {!! $course->is_paid ? '' : 'Full course just&nbsp;<span style="color: #DA4E44;">' . convertPrice($course->price, true, $course->price_usd) . '</span>' !!}
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <a class="class_btn btn" style="width: auto !important; min-width: 100px; padding: 5px 15px !important;"
                                                        href="{{ route('course.details', ['id' => $course->id]) }}">
                                                        {{ $course->cta_text ?? 'Enroll Now' }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- AI Live Session (Hardcoded) -->
                    <div class="col-lg-4 col-md-6 grid-item">
                        <div class="z-gallery mb-30" onclick="window.location.href='{{ route('ai-agriculture-course') }}'" style="cursor: pointer;">
                            <div class="z-gallery__thumb mb-20">
                                <a><img class="img-fluid" src="{{ asset('assets/img/course/ai-course-thumbnail.jpg') }}"
                                        loading="lazy" alt="AI in Agriculture"></a>
                            </div>
                            <div class="z-gallery__content">
                                <div class="course__tag mb-15">
                                    <span>Any Age</span>
                                </div>
                                <h4 class="sub-title mb-20"><a>AI in Agriculture for Kids | Smart Farming Live Course</a></h4>
                                <div class="course__meta row row-cols-1 gap-3">
                                    <div class="col">
                                        <span class="class_count"><img class="icon"
                                                src="{{ asset('assets/img/icon/time.svg') }}"
                                                alt="time icon" loading="lazy">
                                            5 Live Classes</span>
                                    </div>
                                    <div class="col btn-back">
                                        <div class="row">
                                            <div class="col d-flex align-items-center justify-content-center">
                                                <a class="class_btn btn" style="width: auto !important; padding: 5px 15px !important;" href="{{ route('ai-agriculture-course') }}">Enroll For Live Session</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Robotics Live Session (Hardcoded) -->
                    <div class="col-lg-4 col-md-6 grid-item">
                        <div class="z-gallery mb-30" onclick="window.location.href='{{ route('robotics-agriculture-course') }}'" style="cursor: pointer;">
                            <div class="z-gallery__thumb mb-20">
                                <a><img class="img-fluid" src="{{ asset('assets/img/course/robotics-course-thumbnail.jpg') }}"
                                        alt="Robotics in Farming" loading="lazy"></a>
                            </div>
                            <div class="z-gallery__content">
                                <div class="course__tag mb-15">
                                    <span>Any Age</span>
                                </div>
                                <h4 class="sub-title mb-20"><a>Robotics in Farming for Kids | Live Online Course</a></h4>
                                <div class="course__meta row row-cols-1 gap-3">
                                    <div class="col">
                                        <span class="class_count"><img class="icon"
                                                src="{{ asset('assets/img/icon/time.svg') }}"
                                                alt="time icon" loading="lazy">
                                            5 Live Classes</span>
                                    </div>
                                    <div class="col btn-back">
                                        <div class="row">
                                            <div class="col d-flex align-items-center justify-content-center">
                                                <a class="class_btn btn" style="width: auto !important; padding: 5px 15px !important;" href="{{ route('robotics-agriculture-course') }}">Enroll For Live session</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection