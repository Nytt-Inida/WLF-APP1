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

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Apply sans-serif font everywhere */
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        a,
        input,
        button {
            font-family: Arial, Helvetica, sans-serif;
        }


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

            /* Update profile styling for mobile view */
            .update-profile-container {
                background: none !important;
            }

            .rounded-circle img {
                max-width: 100px !important;
                width: 100%;
                height: 100px !important;
            }

            .update-fields label {
                font-weight: 200;
                font-size: 20px !important;
            }

            .update-fields input {
                border-radius: 8px !important;
                border-width: Stroke/Border;
                padding-top: Space/300;
                padding-right: Space/400;
                padding-bottom: Space/300;
                padding-left: Space/400;
                border-top: 1px solid var(--Border-Default-Default, #D9D9D9) !important;
            }

            .update-profile-submit button {
                max-width: 104px !important;
                width: 100%;
                height: 45px !important;
                border-radius: 23px !important;
                font-size: 20px !important;
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

            .update-profile-content-main {
                padding-bottom: 0px !important;
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


            .navigation-arrow a img {
                width: 35px;
                height: 35px;
            }

        }

        /* update profile styling */
        .rounded-circle img {
            max-width: 294px;
            width: 100%;
            height: 287px;
        }

        .update-profile-name h1 {
            font-weight: 600;
            font-size: 48px !important;
            font-family: 'Manrope', sans-serif !important;
            color: #000000;
        }

        .update-profile-mail p {
            font-weight: 600;
            font-size: 20px;
            color: #000000;
            font-family: 'Manrope', sans-serif !important;
        }

        .update-profile-container {
            background: linear-gradient(180deg, rgba(226, 235, 205, 0.18) 0%, rgba(162, 181, 121, 0.52) 100%);
            border-radius: 40px;
        }

        .update-fields label {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 20px;
        }

        .update-fields input {
            width: 100%;
            height: 60px;
            border-radius: 24px;
            background: #FFFFFF;
            padding: 5px 1rem;
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 20px;
            border: none;
        }

        .update-profile-submit button {
            max-width: 180px;
            width: 100%;
            height: 60px;
            border-radius: 16px;
            color: #FFFFFF;
            background: #ffa03f;
            border: none;
            outline: none;
            font-family: 'Manrope', sans-serif !important;
            font-weight: 700;
            font-size: 25px;
        }

        .update-profile-content-main {
            padding-bottom: 5rem;
        }

        /* Footer styling */
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
    <style>
        /* Select2 Custom Styles to Match Input */
        .select2-container .select2-selection--single {
            height: 60px !important;
            border-radius: 24px !important;
            border: none !important;
            display: flex;
            align-items: center;
            background-color: #FFFFFF;
            padding-left: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000;
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 20px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 60px !important;
            right: 15px !important;
        }
        
        /* Mobile Specific Select2 Adjustments */
        @media (max-width: 991px) {
             .select2-container .select2-selection--single {
                height: 48px !important; /* Match mobile input height if different, derived from previous css */
                border: 1px solid #D9D9D9 !important;
                border-radius: 8px !important;
            }
        }
        
        /* Fix for Duplicate Input (Hide nice-select if present) */
        #updateProfileForm .nice-select {
            display: none !important;
        }
    </style>
</head>

<body>
    <!-- Header -->
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

    <!-- Middle Section - Update Profile Form -->
    <div class="update-profile">
        <div class="container-fluid update-profile-content-head">
            <div class="container px-0 pt-3 pt-lg-5 pb-3 update-profile=content-head">
                <div class="d-lg-none d-block navigation-arrow ps-5">
                    <a href="{{ route('profile') }}">
                        <img src="{{ asset('assets/img/left-arrow.png') }}"
                            alt="Kids harvesting their vegetables in a farm" />
                    </a>
                </div>
                <div class="row">
                    <div class="col d-flex align-items-lg-center justify-content-lg-start justify-content-center">
                        <div class="row">
                            <div class="col-lg-6 col-12 update-profile-img">
                                @if (Auth::user()->profile_image)
                                    <img src="{{ asset(Auth::user()->profile_image) }}"
                                        alt="A kid is learning online farming techniques" />
                                @else
                                    <div class="rounded-circle d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('assets/img/profile.png') }}"
                                            alt="Children working and exploring the farm" />
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-6 col-12 update-profile-details pt-lg-5 pt-2 d-lg-block d-none">
                                <div class="update-profile-name text-lg-start text-center">
                                    <h1>{{ Auth::user()->name }}</h1>
                                </div>
                                <div class="update-profile-mail mt-2 text-lg-start text-center">
                                    <p>{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid update-profile-content-main">
            <div class="container update-profile-container">
                <form class="p-lg-5" id="updateProfileForm" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <div class="update-fields mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="username" name="name"
                            value="{{ auth()->user()->name }}" required pattern="[A-Za-z\s]+" title="Must contain only letters and spaces">
                        @error('name')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="update-fields mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                            value="{{ auth()->user()->email }}" readonly>
                         @error('email')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="update-fields mb-3">
                        <label for="school_name" class="form-label">School name / Homeschooling <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('school_name') is-invalid @enderror" id="school_name" name="school_name"
                            value="{{ auth()->user()->school_name }}" required pattern="[A-Za-z\s]+" title="Must contain only letters and spaces">
                        @error('school_name')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="update-fields mb-3">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <select id="country" name="country" class="form-control @error('country') is-invalid @enderror" required style="width: 100%;">
                            <option value="">Select Country</option>
                            @foreach(['Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo (Democratic Republic of the)', 'Congo (Republic of the)', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea (North)', 'Korea (South)', 'Kosovo', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'] as $c)
                                <option value="{{ $c }}" {{ auth()->user()->country == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                        @error('country')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="update-fields mb-3">
                        <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                        <select id="age" name="age" class="form-control @error('age') is-invalid @enderror" required style="width: 100%;">
                            <option value="">Select Age</option>
                             @for ($i = 4; $i <= 18; $i++)
                                <option value="{{ $i }}" {{ auth()->user()->age == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 18 ? '+' : '' }}</option>
                            @endfor
                        </select>
                        @error('age')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="update-profile-submit text-end">
                        <button type="submit">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- <div class="container update-profile-container">
        <h3 class="text-center mb-4">Update Profile</h3>
       <form id="updateProfileForm" action="{{ route('profile.update') }}" method="POST">
    @csrf
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="name" value="{{ auth()->user()->name }}" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
    </div>
    <div class="mb-3">
        <label for="school_name" class="form-label">School name</label>
        <input type="text" class="form-control" id="school_name" name="school_name" value="{{ auth()->user()->school_name }}">
    </div>
    <div class="mb-3">
        <label for="country" class="form-label">Country</label>
        <input type="text" class="form-control" id="country" name="country" value="{{ auth()->user()->country }}" required>
    </div>
    <div class="mb-3">
        <label for="age" class="form-label">Age</label>
        <input type="number" class="form-control" id="age" name="age" value="{{ auth()->user()->age }}" required>
    </div>
    <button type="submit" class="btn btn-warning w-100">Confirm</button>
</form>

    </div> -->

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
    <script src="{{ asset('assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.easypiechart.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        document.getElementById('updateProfileForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const form = event.target;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect to the profile page upon successful update
                        window.location.href = "{{ route('profile') }}";
                    } else {
                        // Handle errors, display messages, etc.
                        alert('There was an issue updating your profile. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Mobile Navbar
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

    <!-- JS here -->
    <script src="{{ asset('assets/js/vendor/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/waypoints.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-bundle.js') }}"></script>
    <script src="{{ asset('assets/js/meanmenu.js') }}"></script>
    <script src="{{ asset('assets/js/slick.js') }}"></script>
    <script src="{{ asset('assets/js/magnific-popup.js') }}"></script>
    <script src="{{ asset('assets/js/parallax.js') }}"></script>
    <script src="{{ asset('assets/js/backtotop.js') }}"></script>
    <script src="{{ asset('assets/js/nice-select.js') }}"></script>
    <script src="{{ asset('assets/js/counterup.js') }}"></script>
    <script src="{{ asset('assets/js/wow.js') }}"></script>
    <script src="{{ asset('assets/js/isotope-pkgd.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded-pkgd.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for Country
            $('#country').select2({
                placeholder: "Select Country",
                allowClear: false
            });

            // Initialize Select2 for Age
            $('#age').select2({
                placeholder: "Select Age",
                allowClear: false,
                tags: true 
            });
            
             // Mobile Menu Logic (Ensured)
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const closeMenu = document.getElementById('closeMenu');
            const menuOverlay = document.getElementById('menuOverlay');
            const body = document.body;

            function openMenu() {
                mobileMenu.classList.add('active');
                menuOverlay.style.display = 'block';
                body.style.overflow = 'hidden';
            }

            function closeMenuFunc() {
                mobileMenu.classList.remove('active');
                menuOverlay.style.display = 'none';
                body.style.overflow = '';
            }

            if (mobileMenuToggle) mobileMenuToggle.addEventListener('click', openMenu);
            if (closeMenu) closeMenu.addEventListener('click', closeMenuFunc);
            if (menuOverlay) menuOverlay.addEventListener('click', closeMenuFunc);
        });
    </script>
</body>

</html>
