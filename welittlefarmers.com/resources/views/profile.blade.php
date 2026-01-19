<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    {!! SEO::generate() !!}
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
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

    <style>
        body {
            overflow-x: hidden;
            width: 100%;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /* Apply sans-serif font everywhere */
        /* body, h1, h2, h3, h4, h5, h6, p, a, input, button {
            font-family: Arial, Helvetica, sans-serif;
        } */

        /* Navbar styling */
        .btn {
            text-align: center;
            border-radius: 5px;
            width: 118px;
            height: 46px;
            text-decoration: none;
            color: white;
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600 !important;
            font-size: 20px;
            border-radius: 23px;
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

        /* Responsive styles for mobile view */
        @media (max-width: 991px) {

            .btn,
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

        /* Adjust the width for larger mobile screens (tablets) */
        @media (min-width: 768px) and (max-width: 1024px) {
            .card {
                width: 60%;
                margin: 0 auto;
            }
        }

        @media (max-width: 991px) {
            .card {
                width: 300%;
                margin-left: -100px;
                margin-top: -300px;
            }


            /* Profile content styling */
            .profile-content-head {
                background: none !important;
            }

            .rounded-circle img {
                width: 100%;
                max-width: 193px !important;
                max-height: 193px !important;
                border-radius: 50% !important;
            }

            .profile-name h1 {
                font-weight: 600;
                font-size: 38px !important;
                font-family: 'Manrope', sans-serif !important;
            }

            .profile-mail p {
                font-family: 'Manrope', sans-serif !important;
                font-weight: 600;
                font-size: 15px !important;
                border-bottom: none !important;
            }

            .my-certificate p {
                font-size: 15px !important;
            }

            /* Dynamic content changed styling dropdown */
            .profile-options {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .profile-option {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px;
                border-radius: 8px;
                box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
                cursor: pointer;
                font-family: 'Manrope', sans-serif !important;
            }

            .arrow-icon {
                font-weight: bold;
                color: gray;
                transition: transform 0.3s ease;
            }

            .generate-code {
                background: blue;
                color: white;
                padding: 8px 12px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            /* Arrow rotate */
            .arrow-icon.rotated {
                transform: rotate(90deg);
            }

            .edit-btn {
                width: 133px !important;
                width: 100%;
                height: 45px !important;
                border-radius: 23px !important;
                background: #ffa03f !important;
            }

            .edit-btn a {
                font-weight: 600;
                font-size: 20px !important;
                color: #FFFFFF;
            }


            .logout-mobile a {
                max-width: 104px !important;
                height: 45px !important;
                width: 100%;
                border-radius: 23px;
                background: #F44336 !important;
                color: #fff !important;
                text-decoration: none;
                font-family: 'Manrope', sans-serif !important;
                font-weight: 600 !important;
                font-size: 20px !important;
                display: inline-flex;
                justify-content: center;
                align-items: center;
                padding: 0.5rem 1rem;
            }


            /* footer part styling */
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


        .profile-content-head {
            background: #E2EBCD;
        }

        .rounded-circle img {
            max-width: 200px;
            width: 100%;
            height: 200px;
        }

        .profile-name h1 {
            font-weight: 600;
            font-size: 48px;
            font-family: 'Manrope', sans-serif !important;
            color: #000000;
        }

        .profile-mail p {
            font-weight: 600;
            font-size: 20px;
            color: #000000;
            font-family: 'Manrope', sans-serif !important;
            border-bottom: 1px solid black;
        }

        .profile-edit .img img {
            max-width: 38px;
            width: 100%;
            height: 38px;
        }

        .edit-btn {
            max-width: 164px;
            width: 100%;
            height: 50px;
            border-radius: 21px;
            background: #F0F4E7;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .edit-btn a {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 24px;
            text-decoration: none;
        }

        .logout .btn {
            background: #F44336;
            color: white;
        }

        .header-item {
            display: inline-block;
            cursor: pointer;
            transition: border-bottom 0.3s ease;
            padding-bottom: 5px;
        }


        .header-item.active {
            border-bottom: 3px solid #848979;
            /* Add your desired color */
        }

        .header-nav div {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 24px;
            text-align: center;
        }

        .my-certificate p {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 24px;
            text-align: center;
        }

        .referral-code .img img {
            max-width: 247px;
            width: 100%;
            max-height: 247px;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .referral-content {
            margin-top: 0.5rem;
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 22px !important;
        }

        .refferal-code-btn button {
            max-width: 186px;
            width: 100%;
            height: 67px;
            background: #4E57FE !important;
            border-radius: 12px;
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 20px;
            color: #FFFFFF;
            border: none !important;
        }

        /* Footer part styling */
        .footer-app-buttons {
            display: none;
        }

        .company-address li h6 {
            font-weight: 600 !important;
            color: #4f5966;
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
    <!-- Header -->
    <header>
        <div id="theme-menu-one" class="main-header-area main-head-three pl-50 pr-50 pt-20">
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
                            <a href="{{ route('home') }}"><img
                                    src="{{ asset('assets/img/logo/header_logo_one.svg') }}"
                                    alt="Kids harvesting their vegetables in a farm"></a>
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
                                    alt="Children inspired by robotics farming class"></a></div>
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
                                            <a class="nav-link dropdown-toggle" href="" id="navbarDropdown"
                                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Courses
                                            </a>
                                            <ul class="dropdown-menu submenu mega-menu__sub-menu-box"
                                                aria-labelledby="navbarDropdown">
                                                <li><a href="/course_details/1"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="Children working and exploring the farm"></span>
                                                        Our courses for Age 5 to 14</a>
                                                </li>
                                                <li><a href="{{ route('ai-agriculture-course') }}"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="Children working and exploring the farm"></span> AI
                                                        in Agriculture</a></li>
                                                <li><a href="{{ route('robotics-agriculture-course') }}"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="Children engaging with educator during gardening activities"></span>
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
                    <div class="col-lg-2 col-7 d-none d-lg-flex align-items-lg-center justify-content-lg-end">
                        <div class="right-nav d-flex align-items-center justify-content-end">
                            <ul class="d-flex align-items-center">
                                @if (!auth()->check())
                                    <li><a class="btn btn-primary ml-20" href="{{ route('login.form') }}">Login</a>
                                    </li>
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
                    <li><a href="https://welittlefarmers.com/">Home</a></li>
                    <li class="has-dropdown">
                        <a href="">Courses</a>
                        <ul class="sub-menu">
                            <li><a href="https://welittlefarmers.com/course_details/1">Our courses for Age 5 to 14</a>
                            </li>
                            <li><a href="{{ route('ai-agriculture-course') }}">AI in Agriculture</a></li>
                            <li><a href="{{ route('robotics-agriculture-course') }}">Robotics in Agriculture</a></li>

                        </ul>
                    </li>
                    <li><a href="{{ route('about') }}">About us</a></li>

                    <li class="has-dropdown"><a href="#">Blogs</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('blogs.index') }}">Blog Grid</a></li>

                        </ul>
                    </li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>
        </div>

        <!-- Overlay (for closing menu when clicking outside) -->
        <div id="menuOverlay" class="menu-overlay"></div>
    </header>

    <!-- Profile Content -->
    <div class="profile-content">
        <div class="container-fluid profile-content-head">
            <div class="container px-0 py-lg-5 pt-3 pt-lg-5 profile-header">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-12 d-lg-none d-block d-flex align-items-end justify-content-end mb-4">
                                <div class="edit-btn">
                                    <a href="{{ route('profile.edit') }}">Edit Profile</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 profile-img">
                                @if (Auth::user()->profile_image)
                                    <img src="{{ asset(Auth::user()->profile_image) }}"
                                        alt="Children inspired by robotics farming class" />
                                @else
                                    <div class="rounded-circle d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/profile.png') }}"
                                            alt="A kid is learning online farming techniques" />
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-6 col-12 profile-details pt-2">
                                <div class="profile-name text-lg-start text-center">
                                    <h1>{{ Auth::user()->name }}</h1>
                                </div>
                                <div class="profile-mail mt-2 text-lg-start text-center">
                                    <p>{{ Auth::user()->email }}</p>
                                </div>
                                <div class="profile-edit row mt-5">
                                    <div class="col-auto img d-none d-lg-block">
                                        <img src="{{ asset('assets/img/edit.png') }}"
                                            alt="A kid is learning online farming techniques" />
                                    </div>
                                    <div class="col d-flex align-items-center justify-content-start d-none d-lg-block">
                                        <div class="edit-btn">
                                            <a href="{{ route('profile.edit') }}">Edit Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="logout text-end">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display:none;">
                                @csrf
                            </form>
                            <a href="{{ route('logout') }}" class="btn w-25"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid profile-content-main py-lg-5 py-2">
            <div class="container">
                <!-- Desktop Tabs -->
                <div class="row header-nav w-50 mx-auto d-none d-lg-flex">
                    <div class="col header-item active" data-content="my-courses">My courses</div>
                    <div class="col header-item" data-content="my-certificate">My certificate</div>
                    <div class="col header-item" data-content="referral-code">Referral code</div>
                </div>

                <!-- Mobile Dropdown -->
                <div class="dropdown d-lg-none d-block text-center">
                    <div class="profile-options">
                        <div class="profile-option" data-content="my-courses">
                            <span>My Courses</span>
                            <span class="arrow-icon">></span>
                        </div>
                        <div class="profile-option" data-content="my-certificate">
                            <span>My Certificates</span>
                            <span class="arrow-icon">></span>
                        </div>
                        <div class="profile-option" id="mobile-referral">
                            <span>Referral Code</span>
                            <button class="generate-code">Generate code</button>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 d-lg-none d-block">
                    <div class="col text-end">
                        <div class="logout-mobile">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                            <a href="{{ route('logout') }}" class="btn-logout"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="container py-5 d-none d-lg-block" id="dynamic-content">
                <!-- Dynamic content will be here -->
                <div class="grid row">
                    @foreach ($purchasedCourses as $course)
                        <div class="col-lg-4 col-md-6 grid-item cat{{ $loop->index + 1 }}">
                            <div class="z-gallery mb-30" onclick="window.location.href='{{ route('course.details', ['id' => $course->id]) }}'" style="background: #FFFFFF; cursor: pointer;">
                                <div class="z-gallery__thumb mb-20">
                                    <a href="{{ route('course.details', ['id' => $course->id]) }}">
                                        <img src="assets/img/course/full-course-thumbnail.jpg"
                                            alt="{{ $course->title }}">

                                    </a>
                                </div>
                                <div class="z-gallery__content">
                                    <div class="course__tag mb-15 text-center">
                                        <span>{{ $course->age_group }}</span>
                                    </div>
                                    <h4 class="sub-title mb-20">
                                        <a
                                            href="{{ route('course.details', ['id' => $course->id]) }}">{{ $course->title }}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <footer class="footer-area pt-70 pb-40">
        <div class="container">
            <div class="row mb-15">
                <!-- Footer Logo and Description -->
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp2 animated" data-wow-delay='.1s'>
                    <div class="footer__widget mb-30">
                        <div class="footer-logo mb-20">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/img/logo/header_logo_one.svg') }}"
                                    alt="Children working and exploring the farm">
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
                        <div
                            class="app-download mt-30 d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                            <a href="https://play.google.com/store/games?hl=en"><img
                                    src="assets/img/google-play-badge.png" alt="Download on Google Play"
                                    style="width: 150px;"></a>
                            <a href="https://www.apple.com/in/app-store/"><img src="assets/img/app-store-badge.png"
                                    alt="Download on the App Store" style="width: 150px;"></a>
                        </div>
                    </div>
                </div>

                <!-- Contact Us Section -->
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp2 animated" data-wow-delay='.3s'>
                    <div class="footer__widget mb-30 pl-40 pl-md-0 pl-xs-0">
                        <h6 class="widget-title mb-35">Our office India</h6>
                        <ul class="fot-list">
                            <li><strong>Nytt Analytics Pvt Ltd</strong></li>
                            <li>Plot No 3, Sounderan Mills, Sulur, Coimbatore, India</li>
                            <li>+91 73971 87234</li>
                            <br>
                            <li><strong>Nytt Analytics Pvt Ltd</strong></li>
                            <li>CCCIET, Nalanchira, Trivandrum 695015 </li>
                            <li>+91 73971 87234</li>
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
                            <li>Block-B 1035, Youssef Zahra Bldg, Al Quoz 3, Dubai, UAE</li>
                            <li>+971 54 320 2013</li>
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
        document.addEventListener('DOMContentLoaded', () => {
            const headerItems = document.querySelectorAll('.header-item');
            const dynamicContent = document.getElementById('dynamic-content');
            const profileOptions = document.querySelectorAll('.profile-option');
            
            // User Data injected from Backend
            const userReferralCode = "{{ $user->referral_code }}";
            const userRewards = @json($userRewards);
            const isReferralEnabled = {{ $isReferralEnabled ? 'true' : 'false' }};

            // Helper to render rewards
            const renderRewards = () => {
                if (userRewards.length === 0) return '<p class="mt-3">No rewards earned yet.</p>';
                
                let html = '<div class="mt-4 text-start" style="max-width: 500px; margin: 0 auto;"><h5>Your Rewards</h5><ul class="list-group">';
                userRewards.forEach(reward => {
                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center mb-2" style="border: 1px solid #eee; padding: 10px; border-radius: 8px;">
                            <div>
                                <strong>${reward.code}</strong>
                                <br><small>${reward.type === 'percent' ? reward.value + '% Off' : '$' + reward.value + ' Off'}</small>
                            </div>
                            <span class="badge bg-success rounded-pill" style="color: green; background: #e8f5e9;">Active</span>
                        </li>
                    `;
                });
                html += '</ul></div>';
                return html;
            };

            // Define the content for each section
            const contentMap = {
                'my-courses': `
            <div class="dynamic-content">
                <div class="grid row">
                    @foreach ($purchasedCourses as $course)
                        <div class="col-lg-4 col-md-6 grid-item cat{{ $loop->index + 1 }}">
                            <div class="z-gallery mb-30" onclick="window.location.href='{{ route('course.details', ['id' => $course->id]) }}'" style="background: #FFFFFF; cursor: pointer;">
                                <div class="z-gallery__thumb mb-20">
                                    <a href="{{ route('course.details', ['id' => $course->id]) }}">
                                        <img src="assets/img/course/full-course-thumbnail.jpg" alt="{{ $course->title }}">
                                    </a>
                                </div>
                                <div class="z-gallery__content">
                                    <div class="course__tag mb-15 text-center">
                                        <span>{{ $course->age_group }}</span>
                                    </div>
                                    <h4 class="sub-title mb-20">
                                        <a href="{{ route('course.details', ['id' => $course->id]) }}">{{ $course->title }}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        `,
                'my-certificate': `
            <div class="dynamic-content">
                <div class="my-certificate" id="my-certificates-section">
                    <div class="text-center py-4" id="cert-loading">Loading certificates...</div>
                    <div class="row g-3" id="cert-grid" style="display:none;"></div>
                    <div class="text-center py-4" id="cert-empty" style="display:none;">No completed courses found.</div>
                </div>
            </div>
        `,
                'referral-code': `
            <div class="dynamic-content">
                <div class="referral-code text-center">
                    ${isReferralEnabled ? `
                    <div class="img">
                        <img src="{{ asset('assets/img/referral-code.jpeg') }}" alt="Referral Program" style="max-width: 200px; border-radius: 10px;" />
                    </div>
                    <div>
                        <p class="referral-content mt-3">
                            Share your referral code with friends! When they sign up and purchase a course, you get a reward coupon.
                        </p>
                    </div>
                    
                    <!-- Referral Code Section -->
                    <div class="refferal-code-btn mt-4 mb-2" id="referral-area" style="display: flex; gap: 10px; justify-content: center; align-items: center; flex-wrap: wrap;">
                        <p class="referral-code-value" id="user-ref-code" style="
                            font-weight: bold;
                            font-size: 24px;
                            color: #4CAF50;
                            background: #f4f4f4;
                            padding: 10px 25px;
                            border-radius: 10px;
                            border: 2px dashed #4CAF50;
                            margin: 0;
                        ">
                            ${userReferralCode}
                        </p>
                        <button class="btn btn-secondary" onclick="copyToClipboard('${userReferralCode}', this)" style="width: auto; height: 50px; border-radius: 10px; font-size: 16px;">
                            <i class="far fa-copy"></i> Copy Code
                        </button>
                    </div>

                    <!-- Referral Link Section -->
                     <div class="mt-4">
                        <p style="font-weight: 600; margin-bottom: 5px;">Or share this link:</p>
                        <div style="display: flex; gap: 10px; justify-content: center; align-items: center; max-width: 500px; margin: 0 auto; flex-wrap: wrap;">
                            <button class="btn btn-primary" onclick="copyToClipboard('{{ route('signup') }}?ref=${userReferralCode}', this)" style="width: auto; height: 46px; border-radius: 23px; font-size: 16px; border: 1px solid #ffa03f;">
                                <i class="fas fa-link"></i> Copy Referral Link
                            </button>
                        </div>
                    </div>
                    
                    ${renderRewards()}
                    ` : `
                    <div class="img">
                        <img src="{{ asset('assets/img/referral-code.jpeg') }}" alt="Referral Program" style="max-width: 200px; border-radius: 10px; opacity: 0.5;" />
                    </div>
                    <div class="mt-4">
                        <div style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px; padding: 20px; max-width: 500px; margin: 0 auto;">
                            <i class="fas fa-info-circle" style="font-size: 32px; color: #856404; margin-bottom: 15px;"></i>
                            <h5 style="color: #856404; margin-bottom: 10px;">Referral System Currently Disabled</h5>
                            <p style="color: #856404; margin: 0;">
                                Currently no referral bonus is active. The referral system has been disabled by the administrator. 
                                Please check back later or contact support for more information.
                            </p>
                        </div>
                    </div>
                    `}
                </div>
            </div>
        `
            };

            // Handle Desktop Tab Click
            headerItems.forEach(item => {
                item.addEventListener('click', () => {
                    headerItems.forEach(i => i.classList.remove('active'));
                    item.classList.add('active');

                    const contentKey = item.getAttribute('data-content');
                    dynamicContent.innerHTML = contentMap[contentKey];
                    if (contentKey === 'my-certificate') {
                        fetchAndRenderCertificates();
                    }
                });
            });

            // Handle Dropdown Click
            profileOptions.forEach(option => {
                option.addEventListener('click', () => {
                    const contentKey = option.getAttribute('data-content');
                    const existingContent = option.nextElementSibling;
                    const arrowIcon = option.querySelector('.arrow-icon');

                    // Check if the content is already displayed
                    if (existingContent && existingContent.classList.contains('dynamic-content')) {
                        // Remove the existing content (toggle off)
                        existingContent.remove();
                        // reset arrow rotation
                        arrowIcon.classList.remove('rotated');
                    } else {
                        // Add new content under the selected option
                        const contentDiv = document.createElement('div');
                        contentDiv.classList.add('dynamic-content');
                        contentDiv.innerHTML = contentMap[contentKey];
                        option.insertAdjacentElement('afterend', contentDiv);
                        // rotate arrow
                        arrowIcon.classList.add('rotated');
                        if (contentKey === 'my-certificate') {
                            fetchAndRenderCertificates(contentDiv.querySelector(
                                '#my-certificates-section'));
                        }

                        // Rotate the arrow icon
                        if (arrowIcon) {
                            arrowIcon.classList.add('rotated');
                        }
                    }
                });
            });

            window.copyToClipboard = function(text, btn) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(() => {
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                        btn.classList.replace('btn-secondary', 'btn-success');

                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.classList.replace('btn-success', 'btn-secondary');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy: ', err);
                    });
                } else {
                    // Fallback
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand("Copy");
                    textArea.remove();
                    alert("Copied!");
                }
            }


        });

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

        function fetchAndRenderCertificates(rootEl) {
            const root = rootEl || document.getElementById('my-certificates-section');
            if (!root) return;
            const loading = root.querySelector('#cert-loading');
            const grid = root.querySelector('#cert-grid');
            const empty = root.querySelector('#cert-empty');
            loading.style.display = 'block';
            grid.style.display = 'none';
            empty.style.display = 'none';

            fetch(`{{ route('auth.generateCertificate') }}`)
                .then(r => r.json())
                .then(data => {
                    loading.style.display = 'none';
                    const list = (data && data.completed_courses) ? data.completed_courses : [];
                    if (!list.length) {
                        empty.style.display = 'block';
                        return;
                    }
                    grid.innerHTML = '';
                    list.forEach(c => {
                        const col = document.createElement('div');
                        col.className = 'col-lg-4 col-md-6';
                        const imgSrc = c.image ? `/${c.image.replace(/^\//, '')}` :
                            `{{ asset('assets/img/certificate.png') }}`;
                        const downloadUrl = `/course/${c.course_id}/certificate/download`;
                        col.innerHTML = `
                    <div class="z-gallery mb-30" style="background:#FFFFFF;">
                        <div class="z-gallery__thumb mb-20">
                            <img src="assets/img/certificate_background.png" alt="${c.title}"/>
                        </div>
                        <div class="z-gallery__content text-center">
                            <h5 class="mb-2">${c.title}</h5>
                            <a class="btn btn-secondary" href="${downloadUrl}">Download</a>
                        </div>
                    </div>`;
                        grid.appendChild(col);
                    });
                    grid.style.display = 'flex';
                    grid.style.flexWrap = 'wrap';
                })
                .catch(() => {
                    loading.style.display = 'none';
                    empty.style.display = 'block';
                });
        }

        // Mobile referral code generation
        document.addEventListener('DOMContentLoaded', () => {
            const mobileReferral = document.getElementById('mobile-referral');
            const generateBtn = mobileReferral?.querySelector('.generate-code');

            generateBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Generate a 6-character alphanumeric code
                const code = Math.random().toString(36).substring(2, 8).toUpperCase();

                // Replace button with the generated code
                generateBtn.remove();

                const codeEl = document.createElement('p');
                codeEl.textContent = code;
                codeEl.style.cssText = `
            margin: 10px 0 0 0;
            font-size: 18px;
            font-weight: bold;
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 8px 12px;
            border-radius: 8px;
            display: inline-block;
        `;

                mobileReferral.appendChild(codeEl);
            });
        });
    </script>

</body>

</html>
