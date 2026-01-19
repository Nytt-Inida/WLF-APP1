@extends('main')

@push('head')
    <link rel="preload" as="image" href="{{ asset('assets/img/about/01.png') }}">

    <style>
        .about_01,
        .about_02 {
            border-radius: 50%;
        }
    </style>
     <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "{{ route('home') }}"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "About Us",
        "item": "{{ route('about') }}"
      }]
    }
    </script>
@endpush

@section('content')
    <main>

        <!-- About Us Section -->
        <section class="about-us-area pt-150 pb-30 pt-md-100 pb-md-70 pt-xs-100 pb-xs-70">
            <div class="container">
                <div class="row align-items-center mb-120">
                    <div class="col-lg-7">
                        <div class="about__img__box mb-60">
                            <img class="about-main-thumb pl-110" src="assets/img/about/01.png"
                                alt="Kids harvesting their vegetables in a farm">
                            <img class="about-img about_01" src="assets/img/about/03.jpg"
                                alt="Children engaging with educator during gardening activities">
                            <img class="about-img about_02" src="assets/img/about/04.jpg"
                                alt="Children working and exploring the farm">
                            <img class="about-img about_03" src="assets/img/slider/earth-bg.svg"
                                alt="A kid is learning online farming techniques">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="about-wrapper">
                            <div class="section-title section-title-4 mb-60">
                                <h5 class="bottom-line mb-25">About Us</h5>
                                <h2 class="mb-20">Top agriculture classes for children: modern farming & Robotics</h2>
                                <strong>Learn <a href="{{ route('course.details', 1) }}" class="text-primary">Sustainable Farming</a> with Expert Guidance</strong> <br>
                                <p class="my-3">To inspire kids and adults through sustainable farming education,
                                    integrating robotics technology and innovative gardening practices.</p>
                                <p>Through online farming courses and modern agricultural technology, delivered by highly
                                    qualified educators, to improve kids' farming knowledge and skills. </p>
                                
                                <div class="mt-30">
                                    <h5 class="mb-15" style="color: #444;">See Our Courses in Action</h5>
                                    <a class="btn btn-primary" href="{{ route('courses.index') }}">Explore Courses</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- About Us Section End -->

        <!-- Instructors Section Start -->
        <div class="about-section-wrapper pos-rel gradient-bg">
            <div class="what-blur-shape-one"></div>
            <div class="what-blur-shape-two"></div>

            <!-- Course Instructors Section Start -->
            <section class="course-instructor nav-style-two f-round-bg pt-30 pb-30 pt-md-95 pt-xs-95">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-8 col-lg-9">
                            <div class="section-title text-center text-md-start mb-60">
                                <h5 class="bottom-line mb-25">Our Instructors</h5>
                                <h2 class="mb-25">Meet Our Team</h2>
                                <p>Our instructors bring a wealth of experience and expertise in their respective fields,
                                    ensuring every course offers valuable insights and hands-on learning opportunities.</p>
                            </div>
                        </div>
                    </div>
                    <div class="instructor-active owl-carousel">
                        <div class="item">
                            <div class="z-instructors text-center mb-30">
                                <div class="z-instructors__thumb mb-15">
                                    <img src="assets/img/instructor/praveen.jpg" alt="Praveen P">
                                    <!-- <div class="social-media">
                                                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div> -->
                                </div>
                                <div class="z-instructors__content">
                                    <h4 class="sub-title mb-10"><a href="#">Praveen P</a></h4>
                                    <p>Data Scientist & Robotics Expert</p>
                                    <p class="mt-10">Specializes in AI-driven robotics solutions for agriculture, focusing
                                        on predictive analysis, automation, and real-time data insights to optimize farming
                                        practices.</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="z-instructors text-center mb-30">
                                <div class="z-instructors__thumb mb-15">
                                    <img src="assets/img/instructor/subarna.jpg" alt="Subarna V">
                                    <!-- <div class="social-media">
                                                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div> -->
                                </div>
                                <div class="z-instructors__content">
                                    <h4 class="sub-title mb-10"><a href="#">Subarna V</a></h4>
                                    <p>Agriculturist</p>
                                    <p class="mt-10">Expert in integrating robotics and AI into traditional farming
                                        practices to boost productivity and sustainability. A passionate advocate for
                                        educating young minds about agriculture.</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="z-instructors text-center mb-30">
                                <div class="z-instructors__thumb mb-15">
                                    <img src="assets/img/instructor/mensi.jpg" alt="Mensilla M">
                                    <!-- <div class="social-media">
                                                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div> -->
                                </div>
                                <div class="z-instructors__content">
                                    <h4 class="sub-title mb-10"><a href="#">Mensilla M</a></h4>
                                    <p>Food Scientist</p>
                                    <p class="mt-10">Specializes in post-harvest processing and food technology. Focuses on
                                        utilizing technology to ensure quality and sustainability in food production.</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="z-instructors text-center mb-30">
                                <div class="z-instructors__thumb mb-15">
                                    <img src="assets/img/instructor/mohaimin.jpg" alt="Mohaimin Kader">
                                    <!-- <div class="social-media">
                                                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div> -->
                                </div>
                                <div class="z-instructors__content">
                                    <h4 class="sub-title mb-10"><a href="#">Mohaimin Kader</a></h4>
                                    <p>Animation & Design Lead</p>
                                    <p class="mt-10">Leads the creative team in developing interactive and visually
                                        appealing educational content for our courses, ensuring an engaging learning
                                        experience for kids and adults alike.</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="z-instructors text-center mb-30">
                                <div class="z-instructors__thumb mb-15">
                                    <img src="assets/img/instructor/yash.jpg" alt="Yash Chhalotre">
                                    <!-- <div class="social-media">
                                                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div> -->
                                </div>
                                <div class="z-instructors__content">
                                    <h4 class="sub-title mb-10"><a href="#">Yash Chhalotre</a></h4>
                                    <p>Robotics Specialist</p>
                                    <p class="mt-10">
                                        Focuses on integrating automation and data analytics into modern farming education.
                                        Designs interactive learning tools and robotics modules that teach the fundamentals
                                        of precision agriculture and smart farming technology.</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="z-instructors text-center mb-30">
                                <div class="z-instructors__thumb mb-15">
                                    <img src="assets/img/instructor/mohsin.jpg" alt="Mohsin Kader">
                                    <!-- <div class="social-media">
                                                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div> -->
                                </div>
                                <div class="z-instructors__content">
                                    <h4 class="sub-title mb-10"><a href="#">Mohsin Kader</a></h4>
                                    <p>Operations Strategist</p>
                                    <p class="mt-10">Oversees the planning and execution of educational farming projects
                                        and community initiatives. Ensures smooth coordination between teams, partners, and
                                        students to create impactful learning experiences that promote sustainable
                                        agriculture and innovation.</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="z-instructors text-center mb-30">
                                <div class="z-instructors__thumb mb-15">
                                    <img src="assets/img/instructor/aravind.jpg" alt="Aravind A S">
                                    <!-- <div class="social-media">
                                                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div> -->
                                </div>
                                <div class="z-instructors__content">
                                    <h4 class="sub-title mb-10"><a href="#">Aravind A S</a></h4>
                                    <p>Graphic Designer</p>
                                    <p class="mt-10">Creates engaging visual content for social media, websites, and
                                        course materials. Brings the world of agriculture and innovation to life through
                                        design, ensuring the Little Farmers brand inspires creativity and curiosity across
                                        all platforms.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                

                <div class="row mt-50">
                    <div class="col-12 text-center">
                            <h4 class="mb-20">Learn from Our Expert Team</h4>
                            <a class="btn btn-secondary" href="{{ route('courses.index') }}">View Our Courses</a>
                    </div>
                </div>
                </div>
            </section>
            <!-- Course Instructors Section End -->
        </div>

    <!-- Join Community CTA -->
    <section class="cta-area pt-100 pb-100" style="background-color: #f7fcf8;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                        <h2 class="mb-30">Join Our Growing Community of Little Farmers</h2>
                        <div class="d-flex justify-content-center gap-4 flex-wrap mb-40">
                            <span style="font-size: 18px; font-weight: 600;"><i class="fas fa-user-friends me-2" style="color: #ffa03f;"></i> 25,000+ Happy Learners</span>
                            <span style="font-size: 18px; font-weight: 600;"><i class="fas fa-book-open me-2" style="color: #ffa03f;"></i> 20+ Farming Lessons</span>
                            <span style="font-size: 18px; font-weight: 600;"><i class="fas fa-infinity me-2" style="color: #ffa03f;"></i> Lifetime Access</span>
                        </div>
                        <div class="cta-buttons d-flex justify-content-center gap-3 flex-wrap">
                            <a class="btn btn-primary" href="{{ route('course.details', ['id' => 1]) }}" style="white-space: nowrap;">Start Your Free Trial</a>
                            <a class="btn btn-secondary" style="background: transparent; border: 2px solid #ffa03f; color: #ffa03f; white-space: nowrap;" href="{{ route('contact') }}">Contact Us for Guidance</a>
                        </div>
                </div>
            </div>
        </div>
    </section>

    </main>
@endsection
