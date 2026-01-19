<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEO::generate() !!}
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Little Farmers Academy",
      "url": "https://welittlefarmers.com",
      "logo": "{{ asset('assets/img/logo/header_logo_one.svg') }}",
      "sameAs": [
        "https://www.linkedin.com/in/we-little-farmer-4bbb18380",
        "https://www.instagram.com/welittlefarmer/?igsh=ODB0eHE5eXBsajF3#"
      ]
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Little Farmers Academy",
      "url": "https://welittlefarmers.com",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://welittlefarmers.com/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    @stack('head')

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

    <link rel="manifest" href="{{ asset('assets/site.webmanifest') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
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

        /* Drop down for dropdown-toggle */
        .navbar-nav .dropdown-toggle::after {
            display: inline-block;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: .3em solid;
            border-right: .3em solid transparent;
            border-bottom: 0;
            border-left: .3em solid transparent;
        }

        .navbar-nav .nav-link {
            white-space: nowrap;
        }

        /* Apply only for large screens (≥992px) */
        @media (min-width: 992px) {

            .navbar-nav .nav-link:not(.dropdown-toggle)::after,
            .navbar-nav .nav-link:not(.dropdown-toggle)::before {
                content: none !important;
            }
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

            /* Drop down for dropdown-toggle */
            .mobile-menu .dropdown-icon::after {
                content: "";
                display: inline-block;
                margin-left: .5em;
                vertical-align: middle;
                border-top: .3em solid #fff;
                border-right: .3em solid transparent;
                border-left: .3em solid transparent;
                border-bottom: 0;
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
    </style>
    <script>
        // Define global watchedVideos for all pages, before any other scripts attach listeners
        window.watchedVideos = window.watchedVideos || {};
        var watchedVideos = window.watchedVideos;
    </script>
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
        <div id="theme-menu-one" class="main-header-area pl-50 pr-50 pt-20 pb-lg-15">
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
                                    alt="Little Farmers Academy Logo" width="135" height="55"></a>
                        </div>
                        <!-- Search & User Icons (Right) -->
                        <div class="header-icons d-flex">
                            {{-- <a href="#" class="search-icon d-none"><i class="fas fa-search"></i></a> --}}
                            <a href="{{ auth()->check() ? route('profile') : (request()->routeIs('login.form') ? route('signup.form') : route('login.form')) }}"
                                class="user-icon"><i class="fas fa-user-alt"></i></a>
                        </div>
                    </div>

                    <!-- Desktop View -->
                    <div class="col-lg-2 d-none d-lg-block">
                        <div class="logo"><a href="{{ route('home') }}"><img
                                    src="{{ asset('assets/img/logo/header_logo_one.svg') }}" alt="Logo"></a></div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-flex align-items-lg-center justify-content-lg-center">
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
                                                                alt="" width="18" height="18"></span> Little Farmers Online Course</a>
                                                </li>
                                                <li><a href="{{ route('ai-agriculture-course') }}"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="" width="18" height="18"></span> AI in Agriculture</a></li>
                                                <li><a href="{{ route('robotics-agriculture-course') }}"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="" width="18" height="18"></span> Robotics in Agriculture</a></li>
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
                    <div class="col-lg-4 col-7 d-none d-lg-flex align-items-lg-center justify-content-lg-end">
                        <div class="right-nav d-flex align-items-center justify-content-end">
                            <ul class="d-flex align-items-center">
                                @if (!auth()->check())
                                    <li>
                                        @if (request()->routeIs('login.form'))
                                            <a class="btn btn-primary ml-20" href="{{ route('signup.form') }}">Signup</a>
                                        @else
                                            <a class="btn btn-primary ml-20" href="{{ route('login.form') }}">Login</a>
                                        @endif
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
                                                alt="Login" width="40" height="40"></a></li>
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
                    <li><a href="{{ route('home') }}">Home</a></li>
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
</body>

</html>
