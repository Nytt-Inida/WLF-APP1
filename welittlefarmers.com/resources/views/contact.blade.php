<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    {!! SEO::generate() !!}
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
        "name": "Contact",
        "item": "{{ route('contact') }}"
      }]
    }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8L4FMGSS7Y"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-8L4FMGSS7Y');
    </script>

    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/font.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/spacing.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        body {
            overflow-x: hidden;
            width: 100%;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .btn {
            text-align: center;
            border-radius: 5px;
            padding: 0 20px;
            width: auto;
            min-width: 118px;
            height: 46px;
            text-decoration: none;
            color: white;
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600 !important;
            font-size: 20px;
            border-radius: 23px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            color: #ffa03f;
            border: 1px solid #ffa03f;
            background-color: transparent;
        }

        .btn-primary:hover {
            color: #ffa03f;
            border: 1px solid #ffa03f;
            background-color: transparent;
        }

        .btn-secondary {
            background-color: #ffa03f;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #ffa03f;
            color: white;
            border: none;
        }

        .sign-in {
            margin-left: 20px;
        }

        .sign-in img {
            width: 47px;
            height: 47px;
            background-color: transparent;
        }

        .mobile-menu {
            display: none;
        }

        /* footer part */
        .footer-app-buttons {
            display: none;
        }

        .company-address li h6 {
            font-weight: 600 !important;
            color: #4f5966;
        }

        /* Responsive styles for mobile view */
        @media (max-width: 991px) {

            .right-nav .btn,
            .sign-in {
                display: none;
            }

            .right-nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .right-nav ul {
                flex-direction: column;
                align-items: flex-start;
            }

            .right-nav ul li {
                margin-bottom: 10px;
            }

            .sign-in img {
                width: 40px;
                height: 40px;
            }

            .mobile-menu {
                display: block;
                position: fixed;
                top: 0;
                left: -300px;
                width: 300px;
                height: 100%;
                background: #1B212F;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
                transition: 0.3s ease-in-out;
                z-index: 1000;
                padding-top: 20px;
            }

            .mobile-menu .navbar-nav {
                list-style: none;
                padding: 10px 5px;
            }

            .mobile-menu ul li {
                padding: 10px 10px 10px 0px;
            }

            .mobile-menu ul li a {
                text-decoration: none;
                font-family: 'Manrope', sans-serif !important;
                font-size: 22px;
                color: #FFFFFF !important;
                font-weight: 700 !important;
                display: block;
                transition: 0.3s;
            }

            /* Show menu when active */
            .mobile-menu.active {
                left: 0;
            }

            .close-btn {
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 33px;
                border: none;
                background: none;
                cursor: pointer;
                color: white;
            }

            .menu-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                transition: 0.3s;
            }

            .dropdown-submenu {
                display: none;
                padding-left: 1.7rem;
            }

            .dropdown-submenu li {
                position: relative;
                margin-left: 30px;
                padding-left: 10px;
                list-style: none;
            }

            .dropdown-submenu li::before {
                content: '';
                position: absolute;
                left: -20px;
                top: 50%;
                transform: translateY(-50%);
                width: 8px;
                height: 8px;
                border: 2px solid white;
                border-radius: 50%;
                background-color: transparent;
            }

            .dropdown-submenu li a {
                text-decoration: none;
                font-family: 'Manrope', sans-serif !important;
                font-size: 16px !important;
                color: #FFFFFF !important;
                font-weight: 500 !important;
                display: block;
                transition: 0.3s;
            }

            .nav-item.active .dropdown-submenu {
                display: block;
            }

            /* footer part */
            .footer-widget {
                margin-bottom: 20px;
            }

            .footer-widget h3 {
                font-size: 18px;
            }

            .footer-list ul {
                padding-left: 0;
            }

            .footer-list ul li {
                margin-bottom: 10px;
            }

            .footer-social a {
                margin-right: 10px;
            }

            .footer-contact ul {
                padding-left: 0;
            }

            .footer-contact ul li {
                margin-bottom: 10px;
            }

            .copyright-text,
            .footer-menu {
                text-align: center;
            }

            /* Social logo style */
            .footer-social a {
                display: inline-block;
                width: 40px;
                height: 40px;
                line-height: 40px;
                text-align: center;
                margin-right: 10px;
                background-color: #ffe3887b;
                color: #ffa03f;
                border-radius: 50%;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            /* Play and Apply store */
            .footer-app-buttons {
                display: flex;
                justify-content: start;
                margin-top: 25px;
            }

            .footer-app-buttons .app-button {
                margin: 0 10px;
            }

            .footer-app-buttons .app-button img {
                width: 140px;
                height: auto;
                transition: transform 0.3s ease;
            }

            .footer-app-buttons .app-button img:hover {
                transform: scale(1.05);
            }

            .explore-section {
                display: none;
            }

        }

        /* Footer part */
        @media (max-width: 576px) {
            .row>[class*="col-"] {
                margin-bottom: 20px;
            }
        }

        /* Hide desktop navbar on mobile */
        @media (max-width: 991px) {
            .navbar-toggler {
                background: none;
                border: none;
                font-weight: 600;
                font-size: 28px !important;
                color: #ffa03f;
            }

            .logo a img {
                display: block;
                width: 171px !important;
                height: 60px !important;
                margin: 0 auto;
                /* Centers the logo */
            }

            .logo {
                flex-grow: 2;
                display: flex;
                justify-content: center !important;
                align-items: center !important;
            }

            .header-icons {
                display: flex;
                gap: 10px;
            }

            .search-icon {
                margin-left: 20px;
                text-decoration: none;
                font-size: 27px;
                color: #ffa03f;
                padding-top: 5px;
            }

            .user-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 33px;
                height: 33px;
                background-color: #ffa03f;
                color: #FFFFFF;
                border-radius: 50%;
                text-decoration: none;
                margin-bottom: 10px;
                margin-left: 23px;
            }
        }

        @media (min-width: 768px) {
            .footer-social a {
                margin-right: 15px !important;
            }

            .footer-social a:last-child {
                margin-right: 0 !important;
            }
        }

        @media (min-width: 992px) {

            .navbar-nav .nav-link:not(.dropdown-toggle)::after,
            .navbar-nav .nav-link:not(.dropdown-toggle)::before {
                content: none !important;
            }
        }
    </style>
</head>

<body>
    <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

    <!-- Add your site or application content here -->
    <!-- preloader -->
    <div id="preloader">
        <div class="preloader">
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- preloader end  -->
    <header>
        <div id="theme-menu-one" class="main-header-area pl-50 pr-50 pt-20 pb-15">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Mobile View -->
                    <div class="col-12 d-flex align-items-center justify-content-between d-lg-none">
                        <!-- Hamburger Button (Left) -->
                        <div class="pt-1">
                            <button id="mobileMenuToggle" class="navbar-toggler">
                                <span class="navbar-toggler-icon">&#9776;</span>
                            </button>
                        </div>
                        <!-- Logo (Center) -->
                        <div class="logo text-center">
                            <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo/header_logo_one.svg') }}"
                                    alt="Little Farmers Academy Logo"></a>
                        </div>
                        <!-- Search & User Icons (Right) -->
                        <div class="header-icons d-flex">
                            <a href="#" class="search-icon d-none"><i class="fas fa-search"></i></a>
                            <a href="{{ auth()->check() ? route('profile') : route('login.form') }}"
                                class="user-icon"><i class="fas fa-user-alt"></i></a>
                        </div>
                    </div>

                    <!-- Desktop View -->
                    <div class="col-lg-2 d-none d-lg-block">
                        <div class="logo"><a href="{{ route('home') }}"><img
                                    src="{{ asset('assets/img/logo/header_logo_one.svg') }}"
                                    alt="Kids harvesting their vegetables in a farm
"></a></div>
                    </div>
                    <div class="col-lg-8 d-none d-lg-flex align-items-lg-center justify-content-lg-center">
                        <nav class="main-menu navbar navbar-expand-lg justify-content-center">
                            <div class="nav-container">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                                        </li>
                                        <li class="nav-item dropdown mega-menu">
                                            <a class="nav-link dropdown-toggle" href="{{ route('courses.index') }}" id="navbarDropdown"
                                                role="button" aria-expanded="false">
                                                Courses
                                            </a>
                                            <ul class="dropdown-menu submenu mega-menu__sub-menu-box"
                                                aria-labelledby="navbarDropdown">
                                                <li><a href="{{ route('course.details', ['id' => 1]) }}"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="Children inspired by robotics farming class"></span>
                                                        Our courses for Age 5 to 14</a>
                                                </li>
                                                <li><a href="{{ route('ai-agriculture-course') }}"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="Kids harvesting their vegetables in a farm"></span>
                                                        AI in Agriculture</a></li>
                                                <li><a href="{{ route('robotics-agriculture-course') }}"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="A kid is learning online farming techniques"></span>
                                                        Robotics in Agriculture</a></li>
                                            </ul>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('about') }}">About Us</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('blogs.index') }}">Blog</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="col-lg-2 d-none d-lg-flex align-items-lg-center justify-content-lg-end">
                        <div class="right-nav d-flex align-items-center justify-content-end">
                            <ul class="d-flex align-items-center">
                                @if (!auth()->check())
                                    <li><a class="btn btn-primary ml-20"
                                            href="{{ route('login.form', ['redirect_to' => url()->current()]) }}">Login</a>
                                    </li>
                                @endif
                                @if (auth()->check() && auth()->user()->payment_status == 2)
                                    <li><a class="btn btn-secondary ml-20"
                                            href="{{ route('course.details', ['id' => 1]) }}">Go to Your Course</a>
                                    </li>
                                @else
                                    <li><a class="btn btn-secondary ml-20"
                                            href="{{ route('course.details', ['id' => 1]) }}">Start Free Trial</a>
                                    </li>
                                @endif
                                @if (auth()->check())
                                    <!-- Profile Icon with SVG -->
                                    <!-- Only show login icon when the user is not logged in -->
                                    <li><a class="sign-in ml-20" href="{{ route('profile') }}" role="button"><img
                                                src="{{ asset('assets/img/logo/user-logo.png') }}"
                                                alt="Login"></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sliding Mobile Menu -->
        <div id="mobileMenu" class="mobile-menu">
            <button id="closeMenu" class="close-btn">&times;</button>
            <nav class="side-mobile-menu ps-3">
                <ul id="mobile-menu-active" class="mt-2">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="has-dropdown">
                        <a href="{{ route('courses.index') }}">Courses</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('course.details', ['id' => 1]) }}">Our courses for Age 5 to 14</a>
                            </li>
                            <li><a href="{{ route('ai-agriculture-course') }}">AI in Agriculture</a></li>
                            <li><a href="{{ route('robotics-agriculture-course') }}">Robotics in Agriculture</a></li>

                        </ul>
                    </li>
                    <li><a href="{{ route('about') }}">About us</a></li>

                    <li class="has-dropdown"><a href="#">Blogs</a>
                        <ul class="sub-menu">
                            <a class="nav-link" href="{{ route('blogs.index') }}">Blog</a>

                        </ul>
                    </li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>
        </div>

        <!-- Overlay (for closing menu when clicking outside) -->
        <div id="menuOverlay" class="menu-overlay"></div>
    </header>


    <main>
        <!-- Top Banner -->
        <section class="contact-banner pt-150 pb-50 pt-md-120 pt-xs-120" style="background-color: #f1f8e9;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="mb-10" style="font-weight: 700; color: #1b212f;">Looking for course information?</h3>
                        <p class="mb-0" style="font-size: 18px; color: #585858;">Explore our farming programs designed for kids aged 5-14.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="{{ route('courses.index') }}" class="theme_btn theme_btn_bg">Explore Our Programs</a>
                    </div>
                </div>
            </div>
        </section>
        <!--contact-us-area start-->
        <section class="contact-us-area pt-50 pb-120 pt-md-50 pt-xs-50 pb-md-70 pb-xs-70">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-lg-6">
                        <div class="contact-img mb-30">
                            <img class="img-fluid" src="assets/img/contact/01.jpg"
                                alt="Kids harvesting their vegetables in a farm">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="contact-wrapper pl-75 mb-30">
                            <div class="section-title mb-30">
                                <h2>Get In Touch With Us</h2>
                                <p>Have a question or need help? We’d love to hear from you. Reach out and our team will
                                    get back to you soon.</p>
                            </div>
                            <div class="single-contact-box mb-30">
                                <div class="contact__iocn">
                                    <img src="assets/img/icon/material-location-on.svg"
                                        alt="Children working and exploring the farm">
                                </div>
                                <div class="contact__text">
                                    <h6 style="line-height: 1.5; font-weight: 500;">
                                        <a href="https://www.google.com/maps/search/?api=1&query=Nytt+-+Insights+Gulf+LLC+Block-B+1035,+Youssef+Zahra+Bldg,+Al+Quoz+3,+Dubai,+UAE" target="_blank" style="color: inherit;">
                                            <strong>Nytt - Insights Gulf</strong> <br> LLC Block-B 1035, Youssef Zahra Bldg, Al Quoz 3, Dubai, UAE
                                        </a>
                                    </h6>
                                </div>
                            </div>
                            <div class="single-contact-box cb-2 mb-30 d-flex align-items-center">
                                <div class="contact__iocn">
                                    <img src="assets/img/icon/phone-alt.svg"
                                        alt="Children inspired by robotics farming class">
                                </div>
                                <div class="contact__text">
                                    <h6 style="font-weight: 500;">
                                        <a href="tel:+971543202013" style="color: inherit;">+971 54 320 2013</a>
                                    </h6>
                                </div>
                            </div>
                            <div class="single-contact-box cb-3 mb-30 d-flex align-items-center">
                                <div class="contact__iocn">
                                    <img src="assets/img/icon/feather-mail.svg"
                                        alt="Children working and exploring the farm">
                                </div>
                                <div class="contact__text">
                                    <h6 style="font-weight:500 ;">
                                        <a href="mailto:register@welittlefarmers.com" style="color: inherit;">register@welittlefarmers.com</a>
                                    </h6>
                                    <!-- <h5>info2@example.com</h5> -->
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar Extras -->
                        <div class="contact-extras mt-30 pt-30 border-top">
                            <h4 class="mb-20" style="font-size: 20px; font-weight: 600;">Need Instant Help?</h4>
                            <ul class="list-unstyled mb-25">
                                <li class="mb-15"><a href="{{ route('faq') }}" style="color: #6d6d6d; font-size: 16px;"><i class="fas fa-question-circle me-2" style="color: #ffaa45;"></i> Frequently Asked Questions</a></li>
                                <li><a href="{{ route('courses.index') }}" style="color: #6d6d6d; font-size: 16px;"><i class="fas fa-book-open me-2" style="color: #ffaa45;"></i> Browse Our Courses</a></li>
                            </ul>
                            <a href="https://wa.me/971543202013" target="_blank" class="theme_btn theme_btn_bg w-100 text-center d-block"><i class="fab fa-whatsapp me-2"></i> Chat Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--contact-us-area end-->
        
        <!-- While You're Here Section -->
        <section class="feature-course pb-90 pt-50 grey-bg-2">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="section-title text-center mb-50">
                            <h2>While you're here, check out our courses</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Course 1: Age 5-14 -->
                    <div class="col-lg-4 col-md-6 grid-item">
                        <div class="z-gallery mb-30" onclick="window.location.href='{{ route('course.details', ['id' => 1]) }}'" style="cursor: pointer;">
                            <div class="z-gallery__thumb mb-20">
                                <a href="{{ route('course.details', ['id' => 1]) }}"><img class="img-fluid" src="assets/img/course/full-course-thumbnail.jpg" alt="Our courses for Age 5 to 14"></a>
                            </div>
                            <div class="z-gallery__content">
                                <h4 class="sub-title mb-20"><a href="{{ route('course.details', ['id' => 1]) }}">Our courses for Age 5 to 14</a></h4>
                                <div class="course__meta">
                                    <div class="d-flex align-items-center justify-content-between">
                                         <a class="class_btn btn theme_btn_bg" href="{{ route('course.details', ['id' => 1]) }}">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Course 2: AI -->
                    <div class="col-lg-4 col-md-6 grid-item">
                        <div class="z-gallery mb-30" onclick="window.location.href='{{ route('ai-agriculture-course') }}'" style="cursor: pointer;">
                            <div class="z-gallery__thumb mb-20">
                                <a href="{{ route('ai-agriculture-course') }}"><img class="img-fluid" src="assets/img/course/ai-course-thumbnail.jpg" alt="AI in Agriculture"></a>
                            </div>
                             <div class="z-gallery__content">
                                <h4 class="sub-title mb-20"><a href="{{ route('ai-agriculture-course') }}">AI in Agriculture</a></h4>
                                 <div class="course__meta">
                                    <div class="d-flex align-items-center justify-content-between">
                                         <a class="class_btn btn theme_btn_bg" href="{{ route('ai-agriculture-course') }}">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Course 3: Robotics -->
                    <div class="col-lg-4 col-md-6 grid-item">
                        <div class="z-gallery mb-30" onclick="window.location.href='{{ route('robotics-agriculture-course') }}'" style="cursor: pointer;">
                             <div class="z-gallery__thumb mb-20">
                                <a href="{{ route('robotics-agriculture-course') }}"><img class="img-fluid" src="assets/img/course/robotics-course-thumbnail.jpg" alt="Robotics in Agriculture"></a>
                            </div>
                            <div class="z-gallery__content">
                                <h4 class="sub-title mb-20"><a href="{{ route('robotics-agriculture-course') }}">Robotics in Agriculture</a></h4>
                                 <div class="course__meta">
                                    <div class="d-flex align-items-center justify-content-between">
                                         <a class="class_btn btn theme_btn_bg" href="{{ route('robotics-agriculture-course') }}">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--contact-map-area start-->

    </main>
    <!-- footer-area start -->
    <footer class="footer-area pt-70 pb-40">
        <div class="container">
            <div class="row mb-15">
                <!-- Footer Logo and Description -->
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp2 animated" data-wow-delay='.1s'>
                    <div class="footer__widget mb-30">
                        <div class="footer-logo mb-20">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/img/logo/header_logo_one.svg') }}"
                                    alt="Kids harvesting their vegetables in a farm">
                            </a>
                        </div>
                        <p>Little Farmers Academy provides fun and educational farming courses for kids, teaching them
                            how
                            to grow plants and understand the importance of nature.</p>
                        <div class="social-media footer__social mt-30 text-center text-md-start">
                            <a href="https://www.linkedin.com/in/we-little-farmer-4bbb18380"><i
                                    class="fab fa-linkedin-in"></i></a>
                            <a href="https://www.instagram.com/welittlefarmer/?igsh=ODB0eHE5eXBsajF3#"><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                        <!-- App Download Icons -->
                        <!-- <div
                            class="app-download mt-30 d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                            <a href="https://play.google.com/store/games?hl=en"><img
                                    src="assets/img/google-play-badge.png" alt="Download on Google Play"
                                    style="width: 150px;"></a>
                            <a href="https://www.apple.com/in/app-store/"><img src="assets/img/app-store-badge.png"
                                    alt="Download on the App Store" style="width: 150px;"></a>
                        </div> -->
                    </div>
                </div>

                <!-- Contact Us Section -->
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp2 animated" data-wow-delay='.3s'>
                    <div class="footer__widget mb-30 pl-40 pl-md-0 pl-xs-0">
                        <h6 class="widget-title mb-35">Our office India</h6>
                        <ul class="fot-list">
                            <li><strong>Nytt Analytics Pvt Ltd</strong></li>
                            <li><a href="https://www.google.com/maps/search/?api=1&query=Plot+No+3,+Sounderan+Mills,+Sulur,+Coimbatore,+India" target="_blank" style="color: inherit;">Plot No 3, Sounderan Mills, Sulur, Coimbatore, India</a></li>
                            <li><a href="tel:+917397187234" style="color: inherit;">+91 73971 87234</a></li>
                            <br>
                            <li><strong>Nytt Analytics Pvt Ltd</strong></li>
                            <li><a href="https://www.google.com/maps/search/?api=1&query=CCCIET,+Nalanchira,+Trivandrum+695015" target="_blank" style="color: inherit;">CCCIET, Nalanchira, Trivandrum 695015</a></li>
                            <li><a href="tel:+917397187234" style="color: inherit;">+91 73971 87234</a></li>
                            <br>

                        </ul>
                    </div>
                </div>

                <!-- Explore Section -->
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp2 animated" data-wow-delay='.5s'>
                    <div class="footer__widget mb-25 pl-90 pl-md-0 pl-xs-0">
                        <h6 class="widget-title mb-35">Our Office UAE</h6>
                        <ul class="fot-list">

                            <li><strong>Nytt - Insights Gulf LLC</strong></li>
                            <li><a href="https://www.google.com/maps/search/?api=1&query=Block-B+1035,+Youssef+Zahra+Bldg,+Al+Quoz+3,+Dubai,+UAE" target="_blank" style="color: inherit;">Block-B 1035, Youssef Zahra Bldg, Al Quoz 3, Dubai, UAE</a></li>
                            <li><a href="tel:+971543202013" style="color: inherit;">+971 54 320 2013</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Resources Section -->
                <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp2 animated" data-wow-delay='.7s'>
                    <div class="footer__widget mb-30 pl-150 pl-lg-0 pl-md-0 pl-xs-0">
                        <h6 class="widget-title mb-35">Resources</h6>
                        <ul class="fot-list mb-30">
                            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('refund') }}">Refund Policy</a></li>
                            <li><a href="{{ route('delivery') }}">Delivery Policy</a></li>
                            <li><a href="{{ route('faq') }}">FAQs</a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright Section -->
        <div class="copy-right-area border-bot pt-40">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="copyright text-center">
                            <h5>© 2024 <a href="#">Little Farmers Academy</a>. All Rights Reserved.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer-area end -->

    <!-- JS here -->
    <script src="{{ asset('assets/js/vendor/modernizr-3.5.0.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/slick.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/metisMenu.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.nice-select.js') }}" defer></script>
    <script src="{{ asset('assets/js/ajax-form.js') }}" defer></script>
    <script src="{{ asset('assets/js/wow.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/waypoints.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.scrollUp.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.easypiechart.js') }}" defer></script>
    <script src="{{ asset('assets/js/plugins.js') }}" defer></script>
    <script src="{{ asset('assets/js/main.js') }}" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mobileMenu = document.getElementById("mobileMenu");
            const menuToggle = document.getElementById("mobileMenuToggle");
            const closeMenu = document.getElementById("closeMenu");
            const menuOverlay = document.getElementById("menuOverlay");
            const dropdownIcons = document.querySelectorAll(".dropdown-icon");

            // Open menu
            menuToggle.addEventListener("click", function() {
                mobileMenu.classList.add("active");
                menuOverlay.classList.add("active");
            });

            // Close menu
            closeMenu.addEventListener("click", function() {
                mobileMenu.classList.remove("active");
                menuOverlay.classList.remove("active");
            });

            // Close menu when clicking outside
            menuOverlay.addEventListener("click", function() {
                mobileMenu.classList.remove("active");
                menuOverlay.classList.remove("active");
            });

            // Toggle dropdown content
            dropdownIcons.forEach(icon => {
                icon.addEventListener("click", function(event) {
                    event.preventDefault();
                    const parentItem = this.closest(".nav-item");
                    parentItem.classList.toggle("active");
                });
            });
        });
    </script>
    <style>
        @media (max-width: 768px) {
            #scrollUp {
                display: none !important;
            }
            .float-whatsapp {
                bottom: 15px !important;
                right: 15px !important;
            }
        }
    </style>
</body>
</html>
