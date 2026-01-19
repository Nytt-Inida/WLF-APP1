@extends('main')


@push('head')
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Little Farmers Academy",
      "image": "{{ asset('assets/img/logo/header_logo_one.svg') }}",
      "@id": "https://welittlefarmers.com",
      "url": "https://welittlefarmers.com",
      "telephone": "+971543202013",
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Block-B 1035, Youssef Zahra Bldg, Al Quoz 3",
        "addressLocality": "Dubai",
        "addressCountry": "UAE"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 25.1537,
        "longitude": 55.2286
      },
      "department": [
        {
          "@type": "LocalBusiness",
          "name": "Little Farmers Academy - India",
          "telephone": "+917397187234",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Plot No 3, Sounderan Mills, Sulur",
            "addressLocality": "Coimbatore",
            "addressCountry": "IN"
          }
        }
      ]
    }
    </script>
    <link rel="preload" as="image" href="assets/img/icon/01.svg">

    <style>
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
        }

        .course__meta span:last-child {
            color: #1B212f !important;
        }

        .course__meta .course_price {
            font-weight: 600 !important;
            font-size: 22px !important;
        }

        .feature-course .section-title h2 {
            font-size: 26px !important;
            margin-bottom: 1rem !important;
        }

        .text-list li::before {
            background-color: #d1c674 !important;
        }

        .text-list li:nth-child(2)::before {
            background-color: #d1c674 !important;
        }

        .text-list li:last-child::before {
            background-color: #d1c674 !important;
        }

        .feature.tag_03 span {
            background-color: #d1c674 !important;
        }

        .course__meta .course_price {
            font-size: 18px !important;
        }

        .course__meta .class_btn {
            font-size: 14px;
            padding: 0.4rem 0.8rem !important;
            display: block !important;

        }

        @media (max-width: 768px) {
            .course__meta .course_price {
                font-size: 18px !important;
                margin: 0 !important;
            }

            .course__meta .class_btn {
                font-size: 14px;
                padding: 0.4rem 0.8rem !important;
                height: auto !important;
                padding-right: 8px !important;
            }
        }

        /* Sticky CTA Bar Styles (Restored to Classic) */
        .sticky-cta-bar {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 70px !important;
            background: #1B212F !important;
            color: #fff !important;
            z-index: 999999 !important;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.3) !important;
            border-top: 2px solid #ffa03f !important;
            display: none;
        }

        .cta-content {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .cta-text {
            color: #fff;
            margin: 0;
            font-weight: 500;
            font-size: 18px;
        }

        .cta-text strong {
            color: #ffa03f;
            font-size: 20px;
        }

        .cta-btn {
            background: #ffa03f;
            color: #fff !important;
            font-weight: 700;
            padding: 10px 25px;
            border-radius: 5px;
            border: none;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: inline-block;
            text-decoration: none;
        }

        .cta-btn:hover {
            background: #fff;
            color: #ffa03f !important;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .sticky-cta-bar {
                height: 60px !important;
            }

            .cta-text {
                font-size: 13px;
            }

            .cta-text strong {
                font-size: 16px;
            }

            .cta-btn {
                padding: 6px 15px;
                font-size: 13px;
            }
        }
        
        /* Classic Modal Styles */
        #guestLoginModal .modal-content {
            background: #1B212F !important;
            color: #fff !important;
            border: 2px solid #ffa03f !important;
            border-radius: 5px !important;
        }

        #guestLoginModal .modal-title {
            color: #ffa03f !important;
            font-weight: 700;
        }

        #guestLoginModal .text-muted {
            color: #b0b0b0 !important;
        }

        #guestLoginModal .btn-primary {
            background: #ffa03f !important;
            border-color: #ffa03f !important;
            color: #fff !important;
            border-radius: 5px !important;
            font-weight: 700;
        }

        #guestLoginModal .btn-outline-primary {
            border-color: #ffa03f !important;
            color: #ffa03f !important;
            border-radius: 5px !important;
            font-weight: 700;
        }

        #guestLoginModal .btn-outline-primary:hover {
            background: #ffa03f !important;
            color: #fff !important;
        }

        #guestLoginModal .btn-close {
            filter: invert(1) brightness(2); /* Make close button white */
        }
    </style>

@endpush

@section('content')
        <!--slider-area start-->
        <section class="slider-area pt-80 pt-xs-150 pb-xs-35">
            <img class="sl-shape shape_01" src="{{ asset('assets/img/icon/01.svg') }}" alt="Children working and exploring the farm">
            <img class="sl-shape shape_02" src="{{ asset('assets/img/icon/02.svg') }}" alt="Kids harvesting their vegetables in a farm
"
                loading="lazy">
            <img class="sl-shape shape_03" src="{{ asset('assets/img/icon/03.svg') }}" alt="Children inspired by robotics farming class"
                loading="lazy">
            <img class="sl-shape shape_04" src="{{ asset('assets/img/icon/04.svg') }}" alt="A kid is learning online farming techniques"
                loading="lazy">
            <img class="sl-shape shape_05" src="{{ asset('assets/img/icon/05.svg') }}"
                alt="Children engaging with educator during gardening activities" loading="lazy">
            <img class="sl-shape shape_06" src="{{ asset('assets/img/icon/06.svg') }}"
                alt="Children engaging with educator during gardening activities" loading="lazy">
            <img class="sl-shape shape_07" src="{{ asset('assets/img/shape/dot-box-5.svg') }}"
                alt="Children working and exploring the farm" loading="lazy">
            <div class="main-slider pt-10">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-6 col-lg-6 order-last order-lg-first">
                            <div class="slider__img__box mb-50 pr-30">
                                <img class="img-one mt-55 pr-70" src="{{ asset('assets/img/slider/01.png') }}"
                                    alt="Kids harvesting their vegetables in a farm
" loading="lazy">
                                <img class="slide-shape img-two" src="{{ asset('assets/img/slider/02.png') }}"
                                    alt="Children engaging with educator during gardening activities" loading="lazy">
                                <img class="slide-shape img-three" src="{{ asset('assets/img/slider/03.png') }}"
                                    alt="Children inspired by robotics farming class" loading="lazy">
                                <img class="slide-shape img-four" src="{{ asset('assets/img/shape/dot-box-1.svg') }}"
                                    alt="A kid is learning online farming techniques" loading="lazy">
                                <img class="slide-shape img-five" src="{{ asset('assets/img/shape/dot-box-2.svg') }}"
                                    alt="Children engaging with educator during gardening activities" loading="lazy">
                                <img class="slide-shape img-six d-md-block d-none
                               "
                                    src="{{ asset('assets/img/shape/zigzg-1.svg') }}" alt="Kids harvesting their vegetables in a farm
"
                                    loading="lazy">
                                <img class="slide-shape img-seven wow fadeInRight animated" data-delay="1.5s"
                                    src="{{ asset('assets/img/icon/dot-plan-1.svg') }}" alt="A kid is learning online farming techniques"
                                    loading="lazy">
                                <img class="slide-shape img-eight" src="{{ asset('assets/img/slider/earth-bg.svg') }}"
                                    alt="A kid is learning online farming techniques" loading="lazy">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="slider__content text-center pt-15">
                                <h1 class="main-title mb-40 wow fadeInUp2 animated" data-wow-delay='.1s'>Discover the joy of
                                    <span class="vec-shape">farming.</span>
                                </h1>
                                <h5 class="mb-35 wow fadeInUp2 animated" data-wow-delay='.2s'>Equip your child with essential life 
skills like growing their own food for 
tomorrow's world.</h5>

                                <p class="highlight-text"><span>#1</span>specialized farming and food 
science course for kids – for future 
farmers, nutritionists, food scientists, 
and farming technologists.</p>

                                <div class="slider__btn d-lg-none mt-30 mb-20">
                                    @if (auth()->check() && auth()->user()->payment_status == 2)
                                        <a class="btn btn-secondary" href="{{ route('course.details', ['id' => 1]) }}">Go to Your Course</a>
                                    @else
                                        <a class="btn btn-secondary" href="{{ route('course.details', ['id' => 1]) }}">Start Free Trial</a>
                                    @endif
                                </div>

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--slider-area end-->

        <!--great-deal-area start-->
        <section class="great-deal-area pt-150 pb-10 pt-md-100 pb-md-40 pt-xs-100 pb-xs-40">
            <div class="container">
                <div class="row justify-content-lg-center justify-content-start">
                    <div class="col-xl-3 col-lg-8">
                        <div class="deal-box mb-30 text-center text-xl-start">
                            <h2 class="mb-20"><b>Beyond Certificate</b></h2>
                            <p>Upon completion, your child can 
download a certificate. Beyond that, this 
course builds essential survival skills: 
learning to grow their own food, as vital 
as learning to swim, through structured 
farming and food science lessons.</p>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="deal-active owl-carousel mb-30">
                            <div class="single-item">
                                <div class="single-box mb-30">
                                    <div class="single-box__icon mb-25">
                                        <img src="{{ asset('assets/img/icon/puzzle.svg') }}"
                                            alt="Kids harvesting their vegetables in a farm" width="50" height="50" loading="lazy">
                                    </div>
                                    <h4 class="sub-title mb-20">Calmness and Love towards Nature</h4>
                                    <p>Helps your child stay calm, mindful, and loving towards nature.</p>
                                </div>
                            </div>
                            <div class="single-item">
                                <div class="single-box s-box2 mb-30">
                                    <div class="single-box__icon mb-25">
                                        <img src="{{ asset('assets/img/icon/manager.svg') }}"
                                            alt="Children inspired by robotics farming class" width="50" height="50" loading="lazy">
                                    </div>
                                    <h4 class="sub-title mb-20">Knowledge about Food Science</h4>
                                    <p>Introduces food science concepts and future career possibilities.</p>
                                </div>
                            </div>
                            <div class="single-item">
                                <div class="single-box s-box3 mb-30">
                                    <div class="single-box__icon mb-25">
                                        <img src="{{ asset('assets/img/icon/notepad.svg') }}"
                                            alt="A kid is learning online farming techniques" width="50" height="50" loading="lazy">
                                    </div>
                                    <h4 class="sub-title mb-20">Healthy Food Awareness</h4>
                                    <p>A must-have skill to lead a healthy family and society in the future.</p>
                                </div>
                            </div>
                            <div class="single-item">
                                <div class="single-box mb-30">
                                    <div class="single-box__icon mb-25">
                                        <img src="{{ asset('assets/img/icon/puzzle.svg') }}"
                                            alt="Children engaging with educator during gardening activities"
                                            loading="lazy">
                                    </div>
                                    <h4 class="sub-title mb-20">Encourages Green,Happy Minds</h4>
                                    <p>Explains more happiness with more green around us.</p>
                                </div>
                            </div>
                            <div class="single-item">
                                <div class="single-box s-box2 mb-30">
                                    <div class="single-box__icon mb-25">
                                        <img src="{{ asset('assets/img/icon/manager.svg') }}"
                                            alt="Children working and exploring the farm" loading="lazy">
                                    </div>
                                    <h4 class="sub-title mb-20">Sustainable Skills and Hobbies</h4>
                                    <p>Builds sustainable skills and long-term hobbies.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--great-deal-area end-->

        <!-- Courses Display Area start -->
        <section class="feature-course pt-30 pb-30 pt-md-95 pb-md-80 pt-xs-95 pb-xs-80">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="section-title text-center mb-50">
                            <h5 class="bottom-line mb-25">Our Online Farming Courses</h5>
                            <h2>Online Farming Courses for Every Age Group <br> Help your child learn farming,
                                sustainability, and food science at their own pace.</h2>
                            <p>Start your child's journey today and earn their first Farming Certificate!</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-12 text-center">
                        <!-- Portfolio Menu -->
                        <div class="portfolio-menu mb-30">
                            <button class="gf_btn active" data-filter="*" onclick="fetchCourses('any')">All</button>
                            <button class="gf_btn" data-filter=".cat2" onclick="fetchCourses('age 5 to 8')">Age 5-8</button>
                            <button class="gf_btn" data-filter=".cat3" onclick="fetchCourses('age 9 to 12')">Age 9-
                                12</button>
                            <button class="gf_btn" data-filter=".cat4" onclick="fetchCourses('age 13 to 15')">Age 13-
                                15</button>

                        </div>

                        <div class="grid row">
                            @foreach ($courses as $course)
                                <div class="col-lg-4 col-md-6 grid-item cat{{ $loop->index + 1 }}">
                                    <div class="z-gallery mb-30" onclick="window.location.href='{{ route('course.details', ['id' => $course->id]) }}'" style="cursor: pointer;">
                                        <div class="z-gallery__thumb mb-20">
                                            <a>
                                                <img src="{{ asset('assets/img/course/full-course-thumbnail.jpg') }}"
                                                    alt="{{ $course->title }}" loading="lazy" width="370" height="250">
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
                                                    <span class="class_count"><img class="icon"
                                                            src="{{ asset('assets/img/icon/time.svg') }}" loading="lazy"
                                                            alt="Kids harvesting their vegetables in a farm">
                                                        {{ $course->number_of_classes }}
                                                        Classes</span>
                                                </div>
                                                <div class="col btn-back">
                                                    <div class="d-flex align-items-center justify-content-between w-100">
                                                        <div class="course_price d-flex align-items-center p-0 m-0" style="color: #1b212f !important; font-size: 16px !important;">
                                                            {!! $course->is_paid ? '' : 'Full course just&nbsp;<span style="color: #DA4E44;">' . convertPrice($course->price, true, $course->price_usd) . '</span>' !!}
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <a class="class_btn btn" style="width: auto !important; min-width: 100px; padding: 5px 15px !important;"
                                                                href="{{ route('course.details', ['id' => $course->id]) }}">{{ $course->cta_text ?? 'Enroll Now' }}</a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- AI Live Session -->
                            <div class="col-lg-4 col-md-6 grid-item cat5">
                                <div class="z-gallery mb-30" onclick="window.location.href='/ai-agriculture-course'" style="cursor: pointer;">
                                    <div class="z-gallery__thumb mb-20">
                                        <a><img class="img-fluid" src="{{ asset('assets/img/course/ai-course-thumbnail.jpg') }}"
                                                loading="lazy" alt="Children working and exploring the farm" width="370" height="250"></a>
                                    </div>
                                    <div class="z-gallery__content">
                                        <div class="course__tag mb-15">
                                            <span>Any Age</span>
                                        </div>
                                        <h4 class="sub-title mb-20"><a>AI in Agriculture for Kids | Smart Farming Live
                                                Course</a></h4>
                                        <div class="course__meta row row-cols-1 gap-3">
                                            <div class="col">
                                                <span class="class_count"><img class="icon"
                                                        src="{{ asset('assets/img/icon/time.svg') }}"
                                                        alt="Kids harvesting their vegetables in a farm" loading="lazy">
                                                    5 Live Classes</span>
                                            </div>
                                            <div class="col btn-back">
                                                <div class="row">
                                                    <div class="col d-flex align-items-center justify-content-center">
                                                        <a class="class_btn btn" href="/ai-agriculture-course">Enroll For Live Session</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Hydroponics Live Session -->
                            <div class="col-lg-4 col-md-6 grid-item cat5">
                                <div class="z-gallery mb-30" onclick="window.location.href='/robotics-agriculture-course'" style="cursor: pointer;">
                                    <div class="z-gallery__thumb mb-20">
                                        <a><img class="img-fluid" src="{{ asset('assets/img/course/robotics-course-thumbnail.jpg') }}"
                                                alt="Children inspired by robotics farming class" loading="lazy" width="370" height="250"></a>
                                    </div>
                                    <div class="z-gallery__content">
                                        <div class="course__tag mb-15">
                                            <span>Any Age</span>
                                        </div>
                                        <h4 class="sub-title mb-20"><a>Robotics in Farming for Kids | Live Online
                                                Course</a></h4>
                                        <div class="course__meta row row-cols-1 gap-3">
                                            <div class="col">
                                                <span class="class_count"><img class="icon"
                                                        src="{{ asset('assets/img/icon/time.svg') }}"
                                                        alt="Children engaging with educator during gardening activities"
                                                        loading="lazy">
                                                    5 Live Classes</span>
                                            </div>
                                            <div class="col btn-back">
                                                <div class="row">
                                                    <div class="col d-flex align-items-center justify-content-center">
                                                        <a class="class_btn btn" href="/robotics-agriculture-course">Enroll For Live session
                                                            </a>
                                                    </div>
                                                </div>
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
        <!-- Courses Display Area end -->

        <!-- why-chose-section-wrapper start -->
        <div class="why-chose-section-wrapper gradient-bg mr-100 ml-100">
            <section class="why-chose-us">
                <div class="why-chose-us-bg pt-10 pb-30 pt-md-95 pb-md-90 pt-xs-95 pb-xs-90">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-7 col-lg-7">
                                <div class="chose-img-wrapper mb-50 pos-rel">
                                    <div class="coures-member">
                                        <h5 style="line-height: 2rem;"> 👩‍🌾 25,000+ Happy Learners</h5>
                                        <img class="choses chose_01" src="{{ asset('assets/img/chose/01.jpg') }}"
                                            alt="Kids harvesting their vegetables in a farm" width="50" height="50" loading="lazy">
                                        <img class="choses chose_02" src="{{ asset('assets/img/chose/02.jpg') }}"
                                            alt="Children inspired by robotics farming class" width="50" height="50" loading="lazy">
                                        <img class="choses chose_03" src="{{ asset('assets/img/chose/03.jpg') }}"
                                            alt="Children inspired by robotics farming class" width="50" height="50" loading="lazy">
                                        <img class="choses chose_04" src="{{ asset('assets/img/chose/04.jpg') }}"
                                            alt="Children working and exploring the farm" width="50" height="50" loading="lazy">
                                        <span>25k+</span>
                                    </div>
                                    <div class="feature tag_01"><span><img src="{{ asset('assets/img/icon/shield-check.svg') }}"
                                                alt="Children engaging with educator during gardening activities"
                                                loading="lazy" width="20" height="20"></span> Fun & Safe Learning</div>
                                    <div class="feature tag_02"><span><img src="{{ asset('assets/img/icon/catalog.svg') }}"
                                                alt="Children inspired by robotics farming class" loading="lazy" width="20" height="20"></span>
                                        🌾 20+ Farming Lessons</div>
                                    <div class="feature tag_03"><span><i class="fal fa-check"></i></span> 🌱 Learn by
                                        Growing!</div>
                                    <div class="video-wrapper d-none">
                                        <a href="https://www.youtube.com/watch?v=7omGYwdcS04" class="popup-video"><i
                                                class="fas fa-play"></i></a>
                                    </div>
                                    <div class="img-bg pos-rel">
                                        <img class="chose_05 pl-70 pl-lg-0 pl-md-0 pl-xs-0" src="{{ asset('assets/img/chose/05.png') }}"
                                            alt="Children inspired by robotics farming class" loading="lazy" width="450" height="450">
                                    </div>
                                    <img class="chose chose_06" src="{{ asset('assets/img/shape/dot-box3.svg') }}"
                                        alt="Children working and exploring the farm" loading="lazy" width="100" height="100">
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-5">
                                <div class="chose-wrapper pl-25 pl-lg-0 pl-md-0 pl-xs-0">
                                    <div class="section-title mb-30 wow fadeInUp2 animated" data-wow-delay='.1s'>
                                        <h5 class="bottom-line mb-25">Explore Little Farmers Academy</h5>
                                        <h2 class="mb-25">Why Teach Farming Skills to Kids? </h2>
                                        <p>Farming connects children with nature and teaches life skills beyond classrooms.
                                            At Little Farmers Academy, kids (ages 5–15) learn responsibility,
                                            sustainability, and how food grows through fun online farming
                                            courses.<br><br><strong>Future-Ready Careers Your Child Can Explore Through
                                                Farming</strong></p>
                                    </div>
                                    <ul class="text-list mb-40 wow fadeInUp2 animated" data-wow-delay='.2s'>
                                        <ul>
                                            <li><strong>Food Scientist:</strong> Learns how to develop healthy foods and
                                                improve food safety and nutrition.</li>
                                            <li><strong>Agricultural Engineer:</strong> Designs smart farming tools and
                                                machines using technology.</li>
                                            <li><strong>Agricultural Scientist:</strong> Studies plants and soil to improve
                                                crop yield and food quality.</li>
                                            <li><strong>Organic Farmer:</strong> Practices eco-friendly and chemical-free
                                                farming methods.</li>
                                            <li><strong>Agri Entrepreneur:</strong> Builds sustainable businesses in
                                                farming, honey, or plant-based products.</li>
                                            <li><strong>Farm Manager:</strong>Oversees farm operations and resource
                                                management efficiently.</li>
                                            <li><strong>Environmental Consultant:</strong> Promotes responsible resource use
                                                and sustainable solutions.</li>
                                            <li><strong>Farm-to-Table Chef:</strong> Creates healthy dishes using fresh,
                                                locally grown ingredients.</li>
                                        </ul>
                                    </ul>
                                    <a href="{{ route('about') }}" class="theme_btn wow fadeInUp2 animated"
                                        data-wow-delay='.3s'>More Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- why-chose-section-wrapper end -->

        @if ($stickyBarEnabled)
            <div id="sticky-cta-bar" class="sticky-cta-bar">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-8 col-md-9">
                            <div class="cta-content flex-column align-items-start justify-content-center d-flex h-100">
                                <span class="cta-badge"
                                    style="background: #ffa03f; color: #fff; padding: 2px 8px; border-radius: 3px; font-size: 14px; font-weight: 800; text-transform: uppercase; margin-bottom: 2px; line-height: 1;">Limited
                                    Time Offer</span>
                                <p class="cta-text d-none d-md-block">
                                    @if ($stickyBarCoupon && $stickyBarPercent)
                                        Get {{ $stickyBarPercent }}% OFF with code
                                        <strong>{{ $stickyBarCoupon }}</strong>
                                    @elseif($stickyBarCoupon)
                                        Claim your exclusive discount with code <strong>{{ $stickyBarCoupon }}</strong>
                                    @elseif($stickyBarPercent)
                                        Save {{ $stickyBarPercent }}% on our course catalog!
                                    @else
                                        Unleash Your Child's Potential! Enroll in Our Farming Course for Just
                                        <strong>{{ $currencySymbol }}{{ $stickyBarPrice }}</strong>
                                    @endif
                                </p>
                                <p class="cta-text d-md-none">
                                    @if ($stickyBarPercent)
                                        {{ $stickyBarPercent }}% OFF Today!
                                    @else
                                        Course for {{ $currencySymbol }}{{ $stickyBarPrice }}!
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-4 col-md-3 text-end">
                            <button onclick="handleClaimOffer()" class="cta-btn">Claim Offer</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Guest Login Modal -->
        <div class="modal fade" id="guestLoginModal" tabindex="-1" aria-labelledby="guestLoginModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <div class="mb-4">
                            <i class="fas fa-lock-alt fa-3x mb-3" style="color: #ffa03f;"></i>
                            <h4 class="modal-title mb-2" id="guestLoginModalLabel">Login OR Sign Up</h4>
                            <p class="text-muted">To claim this exclusive offer, please log in to your account or sign up as
                                a new user.</p>
                        </div>
                        <div class="d-grid gap-3">
                            <a href="{{ route('login.form', ['redirect_to' => route('course.details', ['id' => 1, 'coupon' => $stickyBarCoupon])]) }}"
                                class="btn btn-primary btn-lg py-3">Login to Continue</a>
                            <a href="{{ route('signup.form', ['redirect_to' => route('course.details', ['id' => 1, 'coupon' => $stickyBarCoupon])]) }}"
                                class="btn btn-primary btn-lg py-3">New User? Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        // Use window load to ensure jQuery and all assets are fully ready
        window.addEventListener('load', function() {
            (function($) {
                // Ensure everything runs within this closure


                // Course Fetching logic
                window.fetchCourses = function(ageGroup) {
                    const liveCoursesHtml = `
                <div class="col-lg-4 col-md-6 grid-item cat5">
                    <div class="z-gallery mb-30" onclick="window.location.href='/ai-agriculture-course'" style="cursor: pointer;">
                        <div class="z-gallery__thumb mb-20">
                            <a>
                                <img class="img-fluid" src="{{ asset('assets/img/course/ai-course-thumbnail.jpg') }}" loading="lazy" alt="Kids harvesting their vegetables in a farm">
                            </a>
                        </div>
                        <div class="z-gallery__content">
                            <div class="course__tag mb-15">
                                <span>Any Age</span>
                            </div>
                            <h4 class="sub-title mb-20"><a >AI in Agriculture for Kids | Smart Farming Live Course</a></h4>
                            <div class="course__meta row row-cols-1 gap-3">
                                <div class="col">
                                    <span class="class_count"><img class="icon" src="{{ asset('assets/img/icon/time.svg') }}" alt="Kids harvesting their vegetables in a farm
                        " loading="lazy"> 5 Live Classes</span>
                                </div>
                                <div class="col btn-back">
                                    <div class="row">
                                        <div class="col d-flex align-items-center justify-content-center">
                                            <a class="class_btn btn" href="/ai-agriculture-course">Enroll For Live Session</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 grid-item cat5">
                    <div class="z-gallery mb-30" onclick="window.location.href='/robotics-agriculture-course'" style="cursor: pointer;">
                        <div class="z-gallery__thumb mb-20">
                            <a>
                                <img class="img-fluid" src="{{ asset('assets/img/course/robotics-course-thumbnail.jpg') }}" alt="Children inspired by robotics farming class" loading="lazy">
                            </a>
                        </div>
                        <div class="z-gallery__content">
                            <div class="course__tag mb-15">
                                <span>Any Age</span>
                            </div>
                            <h4 class="sub-title mb-20"><a>Robotics in Farming for Kids | Live Online Course</a></h4>
                            <div class="course__meta row row-cols-1 gap-3">
                                <div class="col">
                                    <span class="class_count"><img class="icon" src="{{ asset('assets/img/icon/time.svg') }}" alt="Children inspired by robotics farming class" loading="lazy"> 5 Live Classes</span>
                                </div>
                                <div class="col btn-back">
                                    <div class="row">
                                        <div class="col d-flex align-items-center justify-content-center">
                                            <a class="class_btn btn" href="/robotics-agriculture-course">Enroll For Live Session</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                        // Get current URL params
                        const urlParams = new URLSearchParams(window.location.search);
                        const testCountry = urlParams.get('test_country');

                        $.ajax({
                        url: '{{ route('fetch.courses') }}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        data: {
                            age_group: ageGroup,
                            test_country: testCountry // Pass it along
                        },
                        success: function(response) {
                            var gridContent = '';

                            if (response.code === 200) {

                                var courses = response.courses;

                                if (courses.length > 0) {
                                    courses.forEach(function(course, index) {
                                        var courseDetailsUrl =
                                            `{{ url('course_details') }}/${course.id}`;
                                        gridContent += `
                                    <div class="col-lg-4 col-md-6 grid-item cat${index + 1}">
                                        <div class="z-gallery mb-30" onclick="window.location.href='${courseDetailsUrl}'" style="cursor: pointer;">
                                            <div class="z-gallery__thumb mb-20">
                                                <a>
                                                    <img src="{{ asset('assets/img/course/full-course-thumbnail.jpg') }}" alt="${course.title}">
                                                </a>
                                            </div>
                                            <div class="z-gallery__content">
                                                <div class="course__tag mb-15">
                                                    <span>${course.age_group}</span>
                                                </div>
                                                <h4 class="sub-title mb-20">
                                                    <a>${course.title}</a>
                                                </h4>
                                                <div class="course__meta row row-cols-1 gap-3">
                                                        <div class="col">
                                                            <span class="class_count"><img class="icon" src="{{ asset('assets/img/icon/time.svg') }}" loading="lazy" alt="Children inspired by robotics farming class"> ${course.number_of_classes} Classes</span>
                                                        </div>
                                                        <div class="col btn-back">
                                                            <div class="row">
                                                                <div class="d-flex align-items-center justify-content-between w-100">
                                                                    <div class="course_price d-flex align-items-center p-0 m-0" style="color: #1b212f !important; font-size: 16px !important;">
                                                                        ${course.is_paid ? '' : 'Full course just&nbsp;<span style="color: #DA4E44;">' + course.formatted_price + '</span>'}
                                                                    </div>
                                                                    <div class="d-flex align-items-center justify-content-end">
                                                                        <a class="class_btn btn" style="width: auto !important; min-width: 100px; padding: 5px 15px !important;" href="${courseDetailsUrl}">${course.cta_text || 'Book now'}</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                                    });
                                } else {
                                    gridContent = `
                                <div class="col-12 text-center">
                                    <p>No courses available for this age group.</p>
                                </div>`;
                                }

                                // Append live courses ONLY if filtering by 'any' (All)
                                if (ageGroup.toLowerCase() === 'any' ||
                                    ageGroup.toLowerCase() === 'any age' ||
                                    ageGroup.toLowerCase() === 'age 9 to 12' ||
                                    ageGroup.toLowerCase() === 'age 13 to 15') {
                                    gridContent += liveCoursesHtml;
                                }

                                $('.grid').css('margin-bottom', '0px');

                            } else if (response.code === 404) {
                                gridContent = `
                            <div class="col-12 text-center d-none">
                                <p>No courses found for the selected age group.</p>
                            </div>`;
                                if (ageGroup.toLowerCase() === 'any' ||
                                    ageGroup.toLowerCase() === 'any age' ||
                                    ageGroup.toLowerCase() === 'age 9 to 12' ||
                                    ageGroup.toLowerCase() === 'age 13 to 15') {
                                    gridContent += liveCoursesHtml;
                                }
                                $('.grid').css('margin-bottom', '20px');
                            }

                            $('.grid').html(gridContent);
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 404) {
                                var gridContent = `
                            <div class="col-12 text-center d-none">
                                <p>No courses found for the selected age group.</p>
                            </div>`;
                                if (ageGroup.toLowerCase() === 'any' ||
                                    ageGroup.toLowerCase() === 'any age' ||
                                    ageGroup.toLowerCase() === 'age 9 to 12' ||
                                    ageGroup.toLowerCase() === 'age 13 to 15') {
                                    gridContent += liveCoursesHtml;
                                }
                                $('.grid').html(gridContent);
                                $('.grid').css('margin-bottom', '20px');
                            } else {
                                // Error handling removed for production

                            }
                        }
                    });
                };

                // Fetch initial courses
                fetchCourses('any');

                // Sticky CTA Bar Visibility logic
                var stickyBar = $('#sticky-cta-bar');
                if (stickyBar.length) {

                    $(window).scroll(function() {
                        // Use a lower threshold (100px) and check both window and body
                        var scrollPos = $(window).scrollTop() || $('html').scrollTop() || $('body').scrollTop();
                        if (scrollPos > 100) {
                            stickyBar.fadeIn(300);
                            // Move buttons up to clear the sticky bar (height ~70px)
                            $('.float-whatsapp').css('bottom', '90px');
                            $('.mobile-course-fab').css('bottom', '90px');
                        } else {
                            stickyBar.fadeOut(200);
                            // Restore default CSS positions
                            $('.float-whatsapp').css('bottom', '');
                            $('.mobile-course-fab').css('bottom', '');
                        }
                    });
                } else {
                    console.warn('Sticky bar element NOT found in DOM.');
                }

                window.handleClaimOffer = function() {
                    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
                    const isEnrolled = {{ isset($isEnrolled) && $isEnrolled ? 'true' : 'false' }};
                    const couponCode = "{{ $stickyBarCoupon }}";
                    let courseDetailsUrl = "{{ route('course.details', ['id' => 1]) }}";
                    
                    // Check if already enrolled
                    if (isAuthenticated && isEnrolled) {
                        alert('You are already enrolled in this course!');
                        window.location.href = courseDetailsUrl;
                        return;
                    }
                    
                    // Add parameters
                    const params = [];
                    if (couponCode) params.push("coupon=" + couponCode);
                    params.push("show_claim_popup=1");
                    
                    if(params.length > 0) {
                        courseDetailsUrl += "?" + params.join("&");
                    }

                    if (isAuthenticated) {
                        window.location.href = courseDetailsUrl;
                    } else {
                        // Pass the modified URL to login/signup forms so they redirect correctly after auth
                        const loginBtn = document.querySelector('#guestLoginModal a[href*="login"]');
                        const signupBtn = document.querySelector('#guestLoginModal a[href*="signup"]');
                        
                        if(loginBtn) {
                             let currentHref = new URL(loginBtn.href);
                             currentHref.searchParams.set('redirect_to', courseDetailsUrl);
                             loginBtn.href = currentHref.toString();
                        }
                        
                        if(signupBtn) {
                             let currentHref = new URL(signupBtn.href);
                             currentHref.searchParams.set('redirect_to', courseDetailsUrl);
                             signupBtn.href = currentHref.toString();
                        }

                        var loginModal = new bootstrap.Modal(document.getElementById(
                            'guestLoginModal'));
                        loginModal.show();
                    }
                };

            })(jQuery);
        });
    </script>
@endsection
