<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="title" content="Farming Certificate Courses for Kids | Sustainable Workshops">
    <meta name="description"
        content="Enroll kids in our Farming Certificate Courses and Sustainable Workshops to learn hands-on farming experience.">

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
        "name": "Courses",
        "item": "{{ route('courses.index') }}"
      },{
        "@type": "ListItem",
        "position": 3,
        "name": "{{ $course->title ?? 'Course Details' }}",
        "item": "{{ url()->current() }}"
      }]
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Course",
      "name": "{{ $course->title ?? 'Farming Course' }}",
      "description": "{{ $course->description ?? 'Learn sustainable farming for kids.' }}",
      "provider": {
        "@type": "Organization",
        "name": "Little Farmers Academy",
        "sameAs": "https://welittlefarmers.com"
      },
      "offers": {
        "@type": "Offer",
        "priceCurrency": "INR",
        "price": "{{ $course->price ?? '0.00' }}",
         "category": "EducationalCourse"
      },
      "hasCourseInstance": {
        "@type": "CourseInstance",
        "courseMode": "Online",
        "instructor": {
            "@type": "Person",
            "name": "Expert Educators"
        }
      }
    }
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    <!-- interfont -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

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

        body {
            padding: 0;
            margin: 0;
            box-sizing: border-box
        }



        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            /* Dark background */
        }

        .modal-content {
            background-color: #E2EBCD !important;
            margin: 1% auto !important;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 939px;
            max-height: 760px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-contents img {
            width: 100%;
            max-width: 168px;
            max-height: 163px;
        }

        .question-container p {
            font-weight: 400;
            font-size: 20px;
        }

        .input-box span {
            color: #000000;
        }

        .input-box {
            width: 100%;
            height: 68px;
            background-color: #F5F8F0;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-left: 20px;
            margin-bottom: 5px;
        }

        /* Make quiz option labels fully clickable */
        .option-label {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 12px 16px;
            border-radius: 12px;
            transition: background-color 0.12s ease;
        }

        .option-label:hover {
            background: #eef7ea;
        }

        .option-text {
            display: inline-block;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 24px;
        }

        .modal-footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .nav-button {
            padding: 5px 10px;
            font-size: 24px;
            font-weight: bold;
            border: none;
            cursor: pointer !important;
            border-radius: 5px;
        }

        .prev-btn {
            background-color: #198754 !important;
            color: white;
        }

        .disabled-btn {
            background-color: #D9D9D9 !important;
            cursor: not-allowed;
        }

        .next-btn {
            background-color: #198754 !important;
            color: white;
        }

        .submit-btn {
            background-color: #198754 !important;
            color: white;
            font-weight: bold;
        }

        .modal-header {
            border-bottom: 1px solid #5F625EA6 !important;
        }

        .container-styling {
            margin-top: 50px;
        }

        .note-reminder {
            background-color: #E0EDC2;
        }

        .note-reminder .img img {
            width: 40px;
            height: 40px;
        }

        .page-title-wrapper p {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 14px;
            color: black;
        }

        .progress-outer img {
            width: 25px !important;
            height: 25px !important;
            padding-bottom: 6px;
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

        /* Navbar end */

        .original-price {
            font-weight: 600;
            font-size: 18px;
            color: black;
        }

        .sub-title {
            font-weight: 700 !important;
            font-size: 25px;
            color: #DA4E44 !important;
        }

        .mobile-icons img {
            width: 24px;
            height: 24px;
        }

        /* PROGRESS BAR */
        .progress-ring circle {
            transform: rotate(90deg) scaleX(-1);
            transform-origin: center;
        }

        .progresss-ring-bg {
            fill: none;
            stroke: #e0e0e0;
            stroke-width: 6;
        }

        .progresss-ring-fill {
            fill: none;
            stroke: #ffa03f !important;
            stroke-width: 6;
            stroke-linecap: round;
            transition: stroke-dashoffset 1s ease-in-out;
        }

        .progresss-img {
            width: 25px;
            height: 25px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            background-color: white;
            /* Optional: helps image stand out */
            border-radius: 50%;
        }

        .progresss-text {
            font-size: 10px;
            fill: #000;
            transform: rotate(90deg);
        }

        /* Quiz result progress */
        .progress-containers {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .progress-ring-bg {
            fill: none;
            stroke: #e0e0e0;
            stroke-width: 10;
        }

        .progress-ring-fill {
            fill: none;
            stroke: #4CAF50;
            stroke-width: 10;
            stroke-linecap: round;
            transition: stroke-dashoffset 1s ease-in-out;
        }

        .progress-text {
            font-size: 20px;
            font-weight: bold;
            fill: #4CAF50;
        }

        .modal-body {
            padding: 1rem 1rem 0rem 1rem !important;
        }

        .modal-body .progress-row {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.28) 0%, #E0EDC2 98%) !important;
            border-radius: 24px;
        }

        .modal-body p {
            font-size: 20px !important;
            color: #000000;
            margin-bottom: 0.5rem;
        }

        .correct img,
        .worng img,
        .skipped img {
            width: 20px;
            height: 20px;
        }

        .correct span,
        .worng span,
        .skipped span {
            font-size: 15px;
            font-weight: 500;
        }

        .quez-summery h3 {
            font-weight: 500;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .question-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 10px;
        }

        .answer-icon {
            width: 20px;
            /* Adjust size as needed */
            height: 20px;
        }

        .dot-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        /* Courses tag btn */
        .courses-tag-btn a i {
            display: none;
        }

        /* Footer part styling */
        .footer-app-buttons {
            display: none;
        }

        .company-address li h6 {
            font-weight: 600 !important;
            color: #4f5966;
        }

        /* Certificate download popup */
        .center-message {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            backdrop-filter: blur(2px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .center-message .center-content {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px 22px;
            max-width: 560px;
            width: 92%;
            text-align: center;
            box-shadow: 0 16px 40px rgba(0, 0, 0, .18);
        }

        .center-message .message-img {
            width: 140px;
            height: auto;
            border-radius: 10px;
            margin-bottom: 14px;
        }

        .center-message .message-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #1b212f;
        }

        .center-message .message-text {
            font-size: 16px;
            margin-bottom: 16px;
            color: #4f5966;
        }

        .center-message .back-btn {
            background: #ffa03f;
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 10px;
            min-width: 160px;
        }

        .back-btn {
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background-color: #f6ad10;
        }

        /* Video wrapper styling */
        .video-wrapper {
            position: relative;
            width: 100%;
        }

        /* Regular button styling */
        #lessonActionBtn {
            position: absolute;
            top: auto;
            bottom: 55px; /* Positioned above Plyr controls */
            right: 20px;
            display: none;
            background: #ffa03f;
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            z-index: 1001;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
            width: auto;
            min-width: 140px;
            height: auto;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #lessonActionBtn:hover {
            transform: scale(1.05);
            background: #ff8c1a;
        }

        /* Mobile specific adjustments for the button */
        @media (max-width: 768px) {
            #lessonActionBtn {
                 bottom: 60px; /* Higher on mobile to clear larger touch targets */
                 right: 10px;
                 padding: 6px 10px;
                 font-size: 12px;
                 min-width: auto; /* Shrink to fit content */
            }
        }

        /* --- SIDEBAR LESSON STATES (NEW) --- */
        .lesson-link {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            padding: 12px 15px;
            border-radius: 8px;
            color: #1B212F;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
            margin-bottom: 5px;
            position: relative;
            line-height: 1.4;
            height: auto !important;
        }

        .lesson-title {
            flex-grow: 1;
            display: block; /* Ensure title behaves as block inside flex item if needed, or just standard text */
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Ensure State Icon is Inline-Flex/Flex and doesn't break */
        .state-icon {
            display: flex !important; 
            align-items: center !important;
            justify-content: center !important;
            height: 24px !important; /* Fixed height to match icon */
            width: 24px !important;
            margin-right: 12px !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* SCROLL BOX & LIST STYLES (Restored from legacy clean-up) */
        .learn-box {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
            position: relative;
        }
        .learn-box::-webkit-scrollbar { width: 0px; }
        
        .learn-box h6 {
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }
        .learn-list { margin-bottom: 20px; }
        .download-certificate-fixed { text-align: center; }

        /* LOCKED */
        .lesson-link.locked {
            opacity: 0.6;
            filter: grayscale(100%);
            cursor: not-allowed;
            background: #f8f9fa;
            color: #6c757d;
        }

        /* UNLOCKED / PENDING */
        .lesson-link.unlocked {
            font-weight: 500;
            background: white;
        }
        .lesson-link.unlocked:hover {
            background: #f8f9fa;
        }

        /* COMPLETED */
        .lesson-link.completed {
            color: #6c757d; 
            background: #f1f3f5;
        }
        .lesson-link.completed .lesson-title {
            opacity: 0.9;
        }

        /* PLAYING (Overrides others) */
        .lesson-link.playing {
            font-weight: 700;
            color: #ffa03f !important;
            background: #fff8f0 !important;
            border-left: 4px solid #ffa03f;
            box-shadow: 0 2px 8px rgba(255, 160, 63, 0.15);
            opacity: 1 !important;
            filter: none !important;
        }

        /* ICON VISIBILITY LOGIC */
        .state-icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0; /* Prevent icon from squashing */
        }

        /* Default Colors */
        .state-icon i { transition: color 0.3s; }
        .lesson-link.locked .state-icon { color: #aaa; }
        .lesson-link.completed .state-icon { color: #28a745; }
        .lesson-link.unlocked .state-icon { color: #1B212F; opacity: 0.5; }
        .lesson-link.playing .state-icon { color: #ffa03f !important; opacity: 1; }

        /* TOGGLE ICONS: Hide all by default */
        .state-icon .icon-lock,
        .state-icon .icon-check,
        .state-icon .icon-play,
        .state-icon .equalizer { display: none; }

        /* Show Lock if Locked */
        .lesson-link.locked .icon-lock { display: inline-block; }

        /* Show Check if Completed AND NOT Playing */
        .lesson-link.completed:not(.playing) .icon-check { display: inline-block; }

        /* Show Play if Unlocked AND NOT Completed AND NOT Playing */
        /* Also show Play if Unlocked AND Completed (if we prefer Play over Check? No, Check is better) */
        /* If base state is Unlocked, we show Play. If Playing, we hide it. */
        .lesson-link.unlocked:not(.completed):not(.playing) .icon-play { display: inline-block; }
        
        /* Show Equalizer if Playing (Overrides everything) */
        .lesson-link.playing .equalizer { display: flex; }

        /* EQUALIZER ANIMATION */
        .equalizer {
            gap: 2px;
            align-items: flex-end;
            height: 14px;
            width: 14px;
            justify-content: center;
        }
        .bar {
            width: 3px;
            background-color: #ffa03f;
            animation: equalize 1s infinite alternate;
        }
        .bar:nth-child(1) { height: 60%; animation-delay: -0.4s; }
        .bar:nth-child(2) { height: 100%; animation-delay: -0.2s; }
        .bar:nth-child(3) { height: 80%; animation-delay: -0.6s; }

        @keyframes equalize {
            0% { height: 40%; }
            100% { height: 100%; }
        }

        .now-playing-badge {
            display: none;
            font-size: 10px;
            background: #ffa03f;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            margin-left: auto;
            margin-right: 10px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .lesson-link.playing .now-playing-badge { display: inline-block; }
        /* Responsive styling */
        @media (max-width: 991px) {
            .courses-tag-btn a i {
                display: inline;
            }

            .courses-tag-btn a {
                border: none;
            }

            .courses-tag-btn a i {
                font-size: 20px;
                margin-right: 5px;
            }

            .modal-content {
                width: 90%;
                padding: 10px;
            }


            /* Navbar mobile view */
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

            .theme_btn {
                padding: 14px 25px !important
            }

            .courses-title-1 {
                font-size: 22px !important;
            }

        }

        .theme_btn {
            padding: 14px 25px !important
        }



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

            .course-details-img {
                min-height: 10px !important;
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

    <div id="preloader">
        <div class="preloader">
            <span></span>
            <span></span>
        </div>
    </div>

    <header>
        <div id="theme-menu-one" class="main-header-area main-head-three pl-100 pr-100 pt-20">
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
                                    alt="Kids harvesting their vegetables in a farm" width="135" height="55"></a>
                        </div>
                        <!-- Search & User Icons (Right) -->
                        <div class="header-icons d-flex">
                            <a href="#" class="search-icon d-none"><i class="fas fa-search"></i></a>
                            <a href="{{ auth()->check() ? route('profile') : route('login.form', ['redirect_to' => url()->current()]) }}"
                                class="user-icon"><i class="fas fa-user-alt"></i></a>
                        </div>
                    </div>

                    <!-- Desktop View -->
                    <div class="col-lg-2 d-none d-lg-block">
                        <div class="logo"><a href="{{ route('home') }}"><img
                                    src="{{ asset('assets/img/logo/header_logo_one.svg') }}"
                                    alt="Children inspired by robotics farming class" width="135" height="55"></a></div>
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
                                                                alt="A kid is learning online farming techniques" width="24" height="24"></span>
                                                        Little Farmers Online Course</a>
                                                </li>
                                                <li><a href="{{ route('ai-agriculture-course') }}"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="Children engaging with educator during gardening activities" width="24" height="24"></span>
                                                        AI in Agriculture</a></li>
                                                <li><a href="{{ route('robotics-agriculture-course') }}"><span><img
                                                                src="{{ asset('assets/img/icon/icon7.svg') }}"
                                                                alt="Children working and exploring the farm" width="24" height="24"></span>
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
                    <div class="col-lg-2 col-7 d-none d-lg-block">
                        <div class="right-nav d-flex align-items-center justify-content-end">
                            <ul class="d-flex align-items-center">
                                @if (!auth()->check())
                                    <li><a class="btn btn-primary ml-20"
                                            href="{{ route('login.form', ['redirect_to' => url()->current()]) }}">Login</a>
                                    </li>
                                @endif
                                @if (auth()->check())
                                    <!-- Profile Icon with SVG -->
                                    <!-- Only show login icon when the user is not logged in -->
                                    <li><a class="sign-in ml-20" href="{{ route('profile') }}" role="button"><img
                                                src="{{ asset('assets/img/logo/user-logo.png') }}"
                                                alt="Login" width="47" height="47"></a></li>
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


    <main>

        <!-- course-details-area start -->
        <section class="course-details-area container-styling">

            <h1 class="courses-title courses-title-1 mb-30 ps-lg-5 ps-3">{{ $course->title }}</h1>
            @if (!$course->userHasPaid(auth()->user()))
                <div class="row note-reminder d-lg-flex d-none mb-4 mx-5 p-3">
                    <div class="col-lg-auto pe-0 img d-flex align-items-center justify-content-center">
                        <img src="{{ asset('assets/img/info.png') }}"
                            alt="Children inspired by robotics farming class" width="24" height="24">
                    </div>
                    <div class="col-lg">
                        <div class="page-title-wrapper">
                            <p>Only the first chapter of the Little Farmers Course is free. <br> Unlock the full
                                journey including all chapters, bonus lessons, and exciting activities by upgrading
                                today!</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="container-fluid px-lg-5">
                <div class="row">
                    <div class="col-xxl-8 col-xl-7  col-lg-7 col-12">
                        <div class="courses-details-wrapper mb-lg-5 mb-3">
                            <div class="course-details-img mb-lg-5 position-relative">
                                <div>
                                    <button id="lessonActionBtn" class="btn">
                                    </button>

                                    <video id="myVideo" width="100%" controls controlsList="nodownload"
                                        disablePictureInPicture poster="{{ asset('assets/img/poster.png') }}"
                                        crossorigin="anonymous">
                                        {{-- IMPORTANT: Start with empty source - will be populated by JavaScript --}}
                                        <source id="videoSource" src="" type="video/mp4">

                                        {{-- Add subtitle tracks --}}
                                        <track id="subtitleTrack" label="English" kind="subtitles" srclang="en"
                                            src="" default>
                                        Your browser does not support the video tag.
                                    </video>
                                    
                                    {{-- Countdown Overlay --}}
                                    <div id="countdownOverlay" style="display:none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 10; flex-direction: column; align-items: center; justify-content: center; color: white;">
                                        <h3 id="countdownMessage" style="margin-bottom: 20px; color: white;">Next video starting in...</h3>
                                        <div id="countdownTimer" style="font-size: 4rem; font-weight: bold; color: #39BF46;">5</div>
                                        <button id="cancelCountdown" class="btn btn-secondary mt-3" style="background: transparent; border: 1px solid white;">Cancel</button>
                                    </div>
                                </div>

                                {{-- Certificate Progress Modal --}}
                                <div id="certificateProgressModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 10000; justify-content: center; align-items: center;">
                                    <div style="background: white; border-radius: 16px; padding: 32px; max-width: 500px; width: 90%; position: relative;">
                                        <button id="closeCertModal" style="position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
                                        
                                        <div style="text-align: center; margin-bottom: 24px;">
                                            <h2 style="color: #1b212f; margin-bottom: 8px; font-size: 24px;">Course Progress</h2>
                                            <p id="certModalSubtitle" style="color: #4f5966; font-size: 14px;"></p>
                                        </div>

                                        <div id="certProgressDetails" style="margin-bottom: 24px;">
                                            <!-- Progress details will be injected here -->
                                        </div>

                                        <div id="certModalActions" style="display: flex; gap: 12px; justify-content: center;">
                                            <!-- Action buttons will be injected here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div
                                class="courses-tag-btn d-none d-lg-flex align-items-center justify-content-start justify-content-lg-start d-none">
                                <a href="#" class="d-flex align-items-center">
                                    <span class="d-none d-md-inline">Add to wishlist</span>
                                </a>
                                <a href="#" class="d-flex align-items-center">
                                    <span class="d-none d-md-inline">Share</span>
                                </a>
                                <a href="#" class="d-flex align-items-center">
                                    <span class="d-none d-md-inline">Gift this course</span>
                                </a>
                            </div> --}}
                        </div>
                    </div>
                    <div class="col-xxl-4 col-xl-5 col-lg-5 col-12">
                        <div class="learn-area mb-30 d-flex flex-column">
                            <div class="row">
                                    @if(auth()->check() && auth()->user()->payment_status == 1 && auth()->user()->pending_course_id == $course->id)
                                        <div class="col price-list d-flex flex-column align-items-start" style="gap:1rem">
                                            <div class="alert alert-warning w-100 text-center" style="border-radius: 12px; border: 2px solid #ffae00; background: #fff8e1; color: #856404;">
                                                <h5 style="margin-bottom: 5px; color: #856404;"><i class="fas fa-clock me-2"></i> Order Complete</h5>
                                                <p style="margin: 0;">Course access is pending verification. Please wait, it will be unlocked shortly.</p>
                                            </div>
                                        </div>
                                    @elseif (!$hasPaid)
                                        <div class="col price-list d-flex flex-column align-items-start" style="gap:1rem">
                                            <div class="d-flex align-items-center justify-content-start" style="gap:1rem; width: 100%;">
                                                <h5 class="mb-0" style="font-size: 20px; color: #1b212f; font-weight: 600;">
                                                    Full course For just <b class="sub-title" id="course_display_price" style="color: #DA4E44;">{{ convertPrice($course->price, true, $course->price_usd) }}</b>
                                                </h5>
                                                <div class="cart-btn">
                                                    <!-- Universal payment route - auto-detects country -->
                                                    <a class="theme_btn" id="pay_now_btn" href="{{ route('payment.show', $course->id) }}">
                                                        Enroll Now
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Coupon Input (Moved Below) -->
                                            <div class="d-flex align-items-center mt-2 w-100 justify-content-start" style="gap: 10px;">
                                                <input type="text" id="course_page_coupon" placeholder="Coupon Code" class="form-control" style="width: 150px; height: 45px; border-radius: 50px; font-size: 14px; text-align: center; border: 1px solid #ffa03f;">
                                                <button type="button" id="claim_btn" class="theme_btn" style="padding: 0 25px; height: 45px; line-height: 45px; font-size: 14px; border-radius: 50px; display: flex; align-items: center; justify-content: center; border: none;">Claim</button>
                                            </div>
                                            <div id="claim_message" style="font-size: 14px; margin-top: 5px; height: 20px;"></div>
                                        </div>

                                        <script>
                                            document.getElementById('claim_btn').addEventListener('click', function() {
                                                const code = document.getElementById('course_page_coupon').value.trim();
                                                const btn = this;
                                                const maxAmount = {{ $course->price }};
                                                // Local auth check to be safe
                                                const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

                                                if (!code) {
                                                    document.getElementById('claim_message').innerHTML = '<span class="text-danger">Please enter a code.</span>';
                                                    return;
                                                }

                                                // Enforce Login
                                                if (!isAuthenticated) {
                                                    alert("Please login to claim this offer.");
                                                    window.location.href = "{{ route('login') }}";
                                                    return;
                                                }

                                                btn.disabled = true;
                                                btn.innerText = 'Checking...';
                                                document.getElementById('claim_message').innerText = '';

                                                // 1. Check if coupon is valid (User is definitely logged in now)
                                                fetch(`/api/check-coupon?code=${code}&course_id={{ $course->id }}&user_id={{ auth()->id() }}`)
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        if (data.valid) {
                                                            // 2. Check if it makes the course FREE (100% discount)
                                                            if (data.new_price <= 0) {
                                                                
                                                                // Check Auth for Free Claim
                                                                if (!window.isAuthenticated) {
                                                                     // Redirect to login (preserving the coupon intent if possible, or just login)
                                                                     // Simple redirect to login
                                                                     window.location.href = "{{ route('login') }}"; 
                                                                     return;
                                                                }

                                                                btn.innerText = 'Claiming...';
                                                                
                                                                // 3. Process Free Claim directly
                                                                fetch('{{ route("payment.freeClaim") }}', {
                                                                    method: 'POST',
                                                                    headers: {
                                                                        'Content-Type': 'application/json',
                                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                                    },
                                                                    body: JSON.stringify({
                                                                        course_id: {{ $course->id }},
                                                                        code: code
                                                                    })
                                                                })
                                                                .then(claimRes => claimRes.json())
                                                                .then(claimData => {
                                                                    if (claimData.success) {
                                                                        btn.innerText = 'Claimed!';
                                                                        const modal = document.getElementById('claimSuccessModal');
                                                                        if(modal) {
                                                                            modal.style.display = 'block';
                                                                        } else {
                                                                             alert('Course Unlocked! Reloading...');
                                                                             window.location.reload();
                                                                        }
                                                                    } else {
                                                                        document.getElementById('claim_message').innerHTML = '<span class="text-danger">' + claimData.message + '</span>';
                                                                        btn.disabled = false;
                                                                        btn.innerText = 'Claim';
                                                                    }
                                                                });

                                                            } else {
                                                                // 4. Partial Discount -> Redirect to Payment
                                                                document.getElementById('claim_message').innerHTML = '<span class="text-success">Discount applied! Redirecting...</span>';
                                                                const baseUrl = "{{ route('payment.show', $course->id) }}";
                                                                window.location.href = `${baseUrl}?coupon_code=${encodeURIComponent(code)}`;
                                                            }
                                                        } else {
                                                            document.getElementById('claim_message').innerHTML = '<span class="text-danger">' + data.message + '</span>';
                                                            btn.disabled = false;
                                                            btn.innerText = 'Claim';
                                                        }
                                                    })
                                                    .catch(err => {
                                                        console.error(err);
                                                        document.getElementById('claim_message').innerHTML = '<span class="text-danger">Error checking code. Please try again.</span>';
                                                        btn.disabled = false;
                                                        btn.innerText = 'Claim';
                                                    });
                                            });

                                            // Reset button state on back-navigation (bfcache)
                                            window.addEventListener('pageshow', function(event) {
                                                const btn = document.getElementById('claim_btn');
                                                const msg = document.getElementById('claim_message');
                                                if (btn) {
                                                    btn.disabled = false;
                                                    btn.innerText = 'Claim';
                                                }
                                                if (msg) {
                                                    msg.innerText = '';
                                                }
                                            });
                                        </script>
                                    @else
                                    <div class="col pe-lg-5 d-flex flex-wrap align-items-center justify-content-between">
                                        
                                        <!-- Status Box -->
                                        <div id="courseStatusBox" class="alert {{ $allLessonsCompleted ? 'alert-primary' : 'alert-success' }} d-flex flex-column align-items-center justify-content-center mb-0 mr-4 py-2 px-3" 
                                            style="border-radius: 12px; border: 2px solid {{ $allLessonsCompleted ? '#0d6efd' : '#28a745' }}; background: {{ $allLessonsCompleted ? '#cfe2ff' : '#d4edda' }}; color: {{ $allLessonsCompleted ? '#084298' : '#155724' }}; height: auto;">
                                            <h6 class="mb-0 status-title" style="color: inherit; font-weight: 700; font-size: 14px;">
                                                @if($allLessonsCompleted)
                                                    <i class="fas fa-trophy" style="margin-right: 5px;"></i> Congratulations!
                                                @else
                                                    Course Unlocked
                                                @endif
                                            </h6>
                                            <small class="status-subtitle" style="font-size: 11px; line-height: 1;">
                                                {{ $allLessonsCompleted ? 'Course Completed' : 'Start Learning' }}
                                            </small>
                                        </div>

                                    <div class="d-flex flex-column align-items-center mr-3">
                                        <div class="progress-circle position-relative">
                                            <svg class="progress-ring" width="50" height="50">
                                                <circle class="progresss-ring-bg" cx="25" cy="25"
                                                    r="20" />
                                                <circle class="progresss-ring-fill" cx="25" cy="25"
                                                r="20" stroke-dasharray="125.6" stroke-dashoffset="125.6" />
                                            </svg>
                                            <img src="{{ asset('assets/img/progress.png') }}"
                                                alt="Children inspired by robotics farming class"
                                                class="progresss-img" />
                                        </div>
                                        <div class="progress-text-overlay mt-1" style="font-size: 15px; font-weight: bold; color: #333;">0/0</div>
                                    </div>
                                        <div class="mobile-icons mb-3 d-none align-items-center"
                                            style="gap:1rem">
                                            <img src="{{ asset('assets/img/fav.png') }}"
                                                alt="A kid is learning online farming techniques" class="fav" width="24" height="24" />
                                            <img src="{{ asset('assets/img/share.png') }}"
                                                alt="A kid is learning online farming techniques" class="share" width="24" height="24" />
                                            <img src="{{ asset('assets/img/gift.png') }}"
                                                alt="Children engaging with educator during gardening activities"
                                                class="gift" width="24" height="24" />
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="learn-box px-2 px-lg-none">
                                <div class=" row learn-box-header">
                                    <div class="col">
                                        <h5>{{ $totalLessons }} Lessons</h5>
                                    </div>
                                    <div class="col pe-5 d-flex align-items-center justify-content-end">

                                    </div>
                                </div>

                                <ul class="learn-list">
                                    @foreach ($course->sections as $sectionIndex => $section)
                                        <div class="section-header d-flex align-items-center py-2 px-3 mt-4 mb-2 rounded shadow-sm" 
                                             style="background-color: #ffffff; border-left: 5px solid #ffc107; transition: all 0.2s ease;">
                                            <i class="fas fa-layer-group me-2" style="color: #ffc107; font-size: 1.2rem;"></i>
                                            <h6 class="mb-0 text-dark" style="font-weight: 700; font-size: 18px; letter-spacing: 0.3px;">
                                                {{ $section->title }}
                                            </h6>
                                        </div>
                                        @foreach ($section->lessons as $index => $lesson)
                                            @php
                                                $isCompleted = in_array($lesson->id, $completedLessons);
                                                $isUnlocked = $isCompleted  
                                                    || (isset($initialState['unlockedLessonIds']) && in_array($lesson->id, $initialState['unlockedLessonIds'])) 
                                                    || ($loop->parent->first && $loop->first); 
                                                
                                                // Base State
                                                $baseClass = $isUnlocked ? ($isCompleted ? 'completed' : 'unlocked') : 'locked';
                                                
                                                // Playing State
                                                $isPlaying = (isset($currentLessonId) && $currentLessonId == $lesson->id); 
                                            @endphp
                                            <li>
                                                <a href="#"
                                                    class="lesson-link {{ $baseClass }} {{ $isPlaying ? 'playing' : '' }}"
                                                    data-lesson-id="{{ $lesson->id }}">
                                                    
                                                    <!-- ICON CONTAINER -->
                                                    <span class="state-icon">
                                                        <!-- Lock Icon -->
                                                        <i class="fas fa-lock icon-lock"></i>
                                                        
                                                        <!-- Check Icon -->
                                                        <i class="fas fa-check-circle icon-check"></i>
                                                        
                                                        <!-- Play Icon -->
                                                        <i class="far fa-play-circle icon-play"></i>
                                                        
                                                        <!-- Equalizer -->
                                                        <div class="equalizer">
                                                            <div class="bar"></div>
                                                            <div class="bar"></div>
                                                            <div class="bar"></div>
                                                        </div>
                                                    </span>

                                                    <!-- TITLE -->
                                                    <span class="lesson-title">
                                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}. {{ $lesson->title }}
                                                    </span>
                                                    
                                                    <!-- BADGE -->
                                                    <span class="now-playing-badge">Playing</span>

                                                    <!-- DURATION -->
                                                    <span class="time float-end ms-auto" style="font-size: 12px; color: #999;">
                                                        {{ $lesson->duration }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach

                                        <!-- Fetch and display articles for the current section -->
                                        <!-- Fetch and display articles for the current section -->
                                        @foreach ($section->articles as $article)
                                            {{-- SKIP SAMPLE TEST as per user request (blocks progress) --}}
                                            @if(stripos($article->title, 'sample test') !== false) 
                                                @continue 
                                            @endif
                                            
                                            <li>
                                                <a href="{{ $article->link }}" 
                                                   class="lesson-link unlocked article-link-item"
                                                   target="_blank">
                                                    
                                                    <span class="state-icon">
                                                        <!-- Article Icon -->
                                                        <i class="far fa-file-alt icon-play" style="color: #6c757d;"></i>
                                                        <!-- Check Icon -->
                                                        <i class="fas fa-check-circle icon-check"></i>
                                                    </span>

                                                    <span class="lesson-title">
                                                        {{ $article->title ?? 'Read Article' }}
                                                    </span>

                                                    <span class="badge bg-light text-dark border ms-auto" style="font-size: 10px;">Article</span>
                                                </a>
                                            </li>
                                        @endforeach

                                        <!-- Add a "Take Test" link at the end of each section -->
                                        <!-- Add a "Take Test" link at the end of each section -->
                                        <!-- Add a "Take Test" link at the end of each section -->
                                        <li>
                                            @php
                                                $quizSectionId = $section->id;
                                                $isQuizCompleted = false;
                                                if (isset($initialState['completedTestSections']) && is_array($initialState['completedTestSections'])) {
                                                    $isQuizCompleted = in_array($quizSectionId, $initialState['completedTestSections']);
                                                }
                                                // If sequential logic applies, we might need to check previous lessons, but base state of 'completed' is safest.
                                                // For locking, JS handles it. But for COMPLETION, server should know.
                                                $quizClass = $isQuizCompleted ? 'completed' : 'unlocked';
                                            @endphp
                                            <a href="#" class="lesson-link {{ $quizClass }} test-link"
                                                data-test-id="{{ $sectionIndex + 1 }}"
                                                data-section-id="{{ $section->id }}">
                                                
                                                <span class="state-icon">
                                                    <!-- Puzzle Icon (Acts as 'Play' icon for tests) -->
                                                    <i class="fas fa-puzzle-piece icon-play" style="color: #6c757d;"></i>
                                                    <!-- Check Icon -->
                                                    <i class="fas fa-check-circle icon-check"></i>
                                                    <!-- Lock Icon (added by JS if needed, or we can add here if we want strictly server side) -->
                                                </span>

                                                <span class="lesson-title">
                                                    Quiz {{ $sectionIndex + 1 }}
                                                </span>

                                                <span class="badge bg-secondary ms-auto" style="font-size: 10px; background-color: #6c757d !important;">Quiz</span>
                                            </a>
                                        </li>
                                    @endforeach
                                    
                                    <!-- Review Section (like a test) -->
                                    @php
                                        $hasReviewQuestions = \App\Models\ReviewQuestion::where('course_id', $course->id)
                                            ->where('is_active', true)
                                            ->exists();
                                        $reviewCompleted = false;
                                        if (auth()->check() && $hasReviewQuestions) {
                                            $reviewCompleted = \App\Models\ReviewAnswer::hasCompletedReview(auth()->id(), $course->id);
                                        }
                                        $reviewClass = $reviewCompleted ? 'completed' : ($hasReviewQuestions ? 'unlocked' : '');
                                    @endphp
                                    @if($hasReviewQuestions)
                                        <li>
                                            <a href="#" class="lesson-link {{ $reviewClass }} review-link"
                                                data-course-id="{{ $course->id }}">
                                                <span class="state-icon">
                                                    <i class="fas fa-star icon-play" style="color: #6c757d;"></i>
                                                    <i class="fas fa-check-circle icon-check"></i>
                                                </span>
                                                <span class="lesson-title">
                                                    Course Review
                                                </span>
                                                <span class="badge bg-warning ms-auto" style="font-size: 10px; background-color: #ffa03f !important;">Review</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <!-- Download Certificate Button -->
                        <div class="row pt-2 pb-5">
                            <div class="col text-center">
                                <div class="download-certificate-fixed">
                                    @if($hasPaid)
                                        <a href="#" id="downloadCertificateButton"
                                            class="px-3 py-2 btns btn-success">
                                            Download Certificate
                                        </a>
                                    @else
                                        <button type="button" class="px-3 py-2 btns btn-success" data-bs-toggle="modal" data-bs-target="#certificatePreviewModal">
                                            Preview Your Certificate
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="centerMessage" class="center-message">
                    <div class="center-content">
                        <img src="{{ asset('assets/img/graduate.jpg') }}"
                            alt="Children working and exploring the farm" class="message-img">
                        <p class="message-text">Please complete all lessons and tests before downloading the
                            certificate.</p>
                        <div class="text-end">
                            <button class="back-btn" onclick="closeCenterMessage()">Back to course</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-7 col-12">
                        {{-- <div class="project-details mb-65 text-center text-sm-start d-none">
                            <p>Interactive Little Farmers Course for Kids</p>
                            <ul class="course-tags d-md-inline-flex align-items-center mt-20 mb-10 gap-md-2">
                                <li>
                                    <a href="#" class="theme_btn mb-10">#YoungFarmers</a>
                                </li>
                                <li>
                                    <a href="#" class="theme_btn mb-10">#KidsFarm</a>
                                </li>
                                <li>
                                    <a href="#" class="theme_btn mb-10">#LittleGardeners</a>
                                </li>
                            </ul>

                            <h5 class="mb-25" style="line-height: 2rem;"><span>Created by</span> Agriculturist
                                Subarna VS, Botanist Reshma, Food
                                Scientist Mensilla, and Animation Lead Mohaimin Kader</h5>
                            <div class="date-lang">
                                <span><b>Date Updated:</b> Today</span>
                                <span><b>Language:</b> English</span>
                            </div>
                        </div> --}}

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const urlParams = new URLSearchParams(window.location.search);
                                const couponCode = urlParams.get('coupon_code') || urlParams.get('coupon'); // Check both
                                
                                if (couponCode) {
                                    const couponInput = document.getElementById('course_page_coupon');
                                    if(couponInput) {
                                        couponInput.value = couponCode;
                                        // Optional: Auto-click claim? Maybe annoying or risky if invalid.
                                        // Let user click claim for specific feedback.
                                        
                                        // But if we want to be smooth, we can scroll to it.
                                        couponInput.scrollIntoView({behavior: 'smooth', block: 'center'});
                                    }
                                }
                            });
                        </script>

                        <div class="meet-our-teacher mb-65 ps-1">
                            <h2 class="courses-title mb-15">About the Course</h2>
                            <p class="mb-25">
                            The <b>Little Farmers Full Course</b> is an interactive <b>online farming and gardening course for children</b>. It helps kids learn how food grows, the importance of soil and water, and the basics of sustainable and eco-friendly farming in a fun and simple way.
                            </p>

                            <p class="mb-25">
                                Through <b>engaging videos, hands-on activities, and easy home gardening projects,</b> children gain real-world experience in planting seeds, caring for plants, composting, and harvesting crops. The course also introduces modern farming techniques and healthy food habits. </p>

                            <p class="mb-25">
                            Designed to be age-appropriate and beginner-friendly, this course builds practical skills, environmental awareness, and a love for nature. It's perfect for kids curious about <b>farming, gardening, nutrition, and the environment</b>.
                            </p>

                            <h3 class="mb-15">What Your Child Will Learn</h3>
                            <ul class="list-unstyled mb-25" style="font-size: 16px; line-height: 1.8;">
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> Grow plants from seed and care for them the right way.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> Understand soil, sunlight, water, and seasons for healthy growth.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> Start a mini home garden with vegetables, herbs, and edible plants.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> Discover why farming is essential for people and the planet.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> Identify healthy food and practice everyday sustainability.</li>
                            </ul>
                            <h3 class="mb-15">Course Highlights</h3>
                            <ul class="list-unstyled mb-0" style="font-size: 16px; line-height: 1.8;">
                                <li><i class="fas fa-play-circle me-2" style="color: #ffa03f;"></i> <strong>19 interactive video lessons</strong> your child can follow easily.</li>
                                <li><i class="fas fa-file-alt me-2" style="color: #ffa03f;"></i> <strong>Activity sheets & quizzes</strong> that make learning playful.</li>
                                <li><i class="fas fa-leaf me-2" style="color: #ffa03f;"></i> <strong>Grow-at-home projects</strong> to apply skills in real life.</li>
                                <li><i class="fas fa-trophy me-2" style="color: #ffa03f;"></i> <strong>Certificate of completion</strong> — “I Am a Farmer”.</li>
                                <li><i class="fas fa-infinity me-2" style="color: #ffa03f;"></i> <strong>Lifetime access</strong> on mobile, tablet, or desktop.</li>
                            </ul>
                        </div>
                        
                        <!-- Related Courses Section -->
                        <div class="related-courses mb-65 ps-1">
                            <h2 class="courses-title mb-30">Complete Your Farming Education</h2>
                            
                            <div class="row">
                                <!-- AI Course Card -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <a href="/ai-agriculture-course">
                                            <img src="{{ asset('assets/img/course/ai-course-thumbnail.jpg') }}" class="card-img-top" alt="AI in Agriculture" style="height: 200px; object-fit: cover;" width="201" height="135">
                                        </a>
                                        <div class="card-body p-4">
                                            <h5 class="card-title mb-3"><a href="/ai-agriculture-course" style="color: #1b212f; font-weight: 700;">AI in Agriculture</a></h5>
                                            <p class="card-text text-muted mb-3" style="font-size: 14px;">Learn how Smart Farming and AI are revolutionizing agriculture.</p>
                                            <a href="/ai-agriculture-course" class="theme_btn">View Course</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Robotics Course Card -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                        <a href="/robotics-agriculture-course">
                                            <img src="{{ asset('assets/img/course/robotics-course-thumbnail.jpg') }}" class="card-img-top" alt="Robotics in Agriculture" style="height: 200px; object-fit: cover;" width="201" height="135">
                                        </a>
                                        <div class="card-body p-4">
                                            <h5 class="card-title mb-3"><a href="/robotics-agriculture-course" style="color: #1b212f; font-weight: 700;">Robotics in Agriculture</a></h5>
                                            <p class="card-text text-muted mb-3" style="font-size: 14px;">Build and program robots to help solve farming challenges.</p>
                                            <a href="/robotics-agriculture-course" class="theme_btn">View Course</a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        {{-- <div class="skill-area d-none">
                            <h2 class="courses-title mb-35">Related Skills</h2>
                            <div class="courses-tag-btn">
                                <a href="#">Gardening</a>
                                <a href="#">Sustainability</a>
                                <a href="#">Plant Care</a>
                                <a href="#">Food Awareness</a>
                                <a href="#">Science for Kids</a>
                                <a href="#">Interactive Learning</a>
                                <a href="#">Nature Education</a>
                            </div>
                        </div> --}}
                    </div>

                    <div class="col-xl-6 col-lg-5 col-12">
                        <div class="courses-ingredients">
                            <h2 class="corses-title mb-30">Course Includes</h2>
                            <p>
                                Explore the joy of farming with our <strong>kid-friendly online course</strong>.
                                From planting seeds to harvesting vegetables, lessons guide children step-by-step with
                                clear videos,
                                quizzes, and home activities. Complete all modules to earn your <em>Little Farmers
                                    Certificate of Completion</em>.
                            </p>
                            <ul class="courses-item mt-25">
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/video.svg"
                                        alt="Kids harvesting their vegetables in a farm" width="24" height="24">
                                    4 hours of on-demand video lessons on farming basics
                                </li>

                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/download.svg"
                                        alt="A kid is learning online farming techniques" width="24" height="24">
                                    Downloadable activity sheets and fun gardening tips
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/infinity.svg"
                                        alt="Children working and exploring the farm" width="24" height="24">
                                    Full lifetime access to course materials
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/mobile.svg"
                                        alt="Children engaging with educator during gardening activities" width="24" height="24">
                                    Access on mobile, tablet, or desktop devices
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/certificate-line.svg"
                                        alt="Children working and exploring the farm" width="24" height="24">
                                    Certificate available as a downloadable PDF
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Claim Success Modal -->
    <div id="claimSuccessModal" class="modal">
        <div class="modal-content">
            <div style="margin-bottom: 20px;">
                <img src="{{ asset('assets/img/icon/check.svg') }}" onerror="this.src='https://cdn-icons-png.flaticon.com/512/190/190411.png'" alt="Success" style="width: 60px; height: 60px;">
            </div>
            <h3>Course Unlocked!</h3>
            <p>You have successfully claimed this course. Get ready to start your farming journey!</p>
            <button onclick="window.location.reload()">Start Learning Now</button>
        </div>
    </div>

    <!-- Certificate Progress Modal (Added/Ensured) -->
    <div id="certificateProgressModal" class="modal">
        <div class="modal-content">
            <span id="closeCertModal" class="close">&times;</span>
            <h3 id="certModalSubtitle">Checking progress...</h3>
            <div id="certProgressDetails">
                <!-- Content injected via JS -->
            </div>
            <div id="certModalActions" style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                <!-- Actions injected via JS -->
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer-area pt-70 pb-40 mx-1 border">
        <div class="container">
            <div class="row mb-15">
                <!-- Footer Logo and Description -->
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp2 animated" data-wow-delay='.1s'>
                    <div class="footer__widget mb-30">
                        <div class="footer-logo mb-20">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/img/logo/header_logo_one.svg') }}"
                                    alt="Children inspired by robotics farming class" width="135" height="55">
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
                        <!--<div-->
                        <!--    class="app-download mt-30 d-flex align-items-center gap-2 justify-content-center justify-content-md-start">-->
                        <!--    <a href="https://play.google.com/store/games?hl=en"><img-->
                        <!--            src="{{ asset('assets/img/google-play-badge.png') }}"-->
                        <!--            alt="Download on Google Play" style="width: 150px;"></a>-->
                        <!--    <a href="https://www.apple.com/in/app-store/">-->
                        <!--        <img src="{{ asset('assets/img/app-store-badge.png') }}"-->
                        <!--            alt="Download on the App Store" style="width: 150px;"></a>-->
                        <!--</div>-->
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
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js" defer></script>
    <!-- Custom JS for Video Unlocking and Quiz Popup -->
    <style>
        /* Lesson Title Hover Effect */
        .lesson-link:hover {
            background-color: #f0f0f0;
            color: #007bff;
        }

        /* Legacy styles removed to avoid conflict */

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

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            /* Dark background */
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 35px;
            border-radius: 8px;
            width: 60%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        /* Success Modal Specifics */
        #claimSuccessModal .modal-content {
            max-width: 400px;
            border-radius: 20px;
            padding: 40px;
        }
        
        #claimSuccessModal h3 {
            color: #39BF46;
            margin-bottom: 15px;
            font-size: 24px;
        }

        #claimSuccessModal p {
            color: #666;
            margin-bottom: 25px;
            font-size: 16px;
        }

        #claimSuccessModal button {
            background: #ffa03f;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        #claimSuccessModal button:hover {
            background: #e68a2e;
        }

        /* Certificate Progress Modal - Fix for Overlap */
        #certificateProgressModal {
            display: none;
            position: fixed;
            z-index: 2147483647 !important; /* Max value to ensure it's on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: hidden; /* Changed from auto to hidden to prevent double scrollbars */
            background-color: rgba(0, 0, 0, 0.4) !important; /* Lighter dark overlay */
            backdrop-filter: blur(3px); /* Reduced blur */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
        }

        #certificateProgressModal .modal-content {
            background-color: #ffffff !important; /* Absolute White */
            margin: 10% auto;
            padding: 40px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            text-align: center;
            z-index: 2147483648 !important; /* One higher than container */
            opacity: 1 !important;
        }

        #certificateProgressModal h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #1b212f;
            font-weight: 700;
        }
        
        #certificateProgressModal p {
            color: #4f5966;
        }

        #closeCertModal {
            position: absolute;
            top: 15px;
            right: 20px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            z-index: 2147483650; /* Ensure on top of modal content */
        }

        #closeCertModal:hover,
        #closeCertModal:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 24px;
        }

        .modal-footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .modal-footer button {
            padding: 10px 20px;
            border: none;
            background-color: #198754;
            color: #fff;
            border-radius: 12px;
            cursor: pointer;

        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                padding: 0px !important;
            }

            .modal-footer {
                margin-top: 0px !important;
            }
        }
    </style>
    <script>
        function closeCenterMessage() {
            const messageBox = document.getElementById('centerMessage');
            if (messageBox) {
                messageBox.style.display = 'none';
            }
        }
    </script>

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


    <script>
        /**
         * Unified Video Protection & Course Management Script
         * with Guest Access for First Lesson
         */

        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        window.userId = {{ auth()->check() ? auth()->user()->id : 'null' }};
        window.userEmail = '{{ auth()->check() ? auth()->user()->email : '' }}';




        document.addEventListener('DOMContentLoaded', function() {


            // ============================================
            // DOM ELEMENTS
            // ============================================
            const lessonLinks = document.querySelectorAll('.lesson-link');
            const testLinks = document.querySelectorAll('.test-link');
            const articleLinks = document.querySelectorAll('.article-link');
            
            // GLOBAL GUARD FOR CANCEL BUTTON GHOST CLICKS
            // Declare on window to avoid scope issues
            window.isBlockingInteraction = false;
            window.autoAdvanceCancelled = false;
            
            // Initialize Plyr with Error Handling
            let player;
            try {
                 player = new Plyr('#myVideo', {
                    controls: [
                        'play-large', // The large play button in the center
                        'restart', // Restart playback
                        'rewind', // Rewind by the seek time (default 10 seconds)
                        'play', // Play/pause playback
                        'fast-forward', // Fast forward by the seek time (default 10 seconds)
                        'progress', // The progress bar and scrubber for playback and buffering
                        'current-time', // The current time of playback
                        'duration', // The full duration of the media
                        'mute', // Toggle mute
                        'volume', // Volume control
                        'captions', // Toggle captions
                        'settings', // Settings menu
                        'pip', // Picture-in-picture (currently Safari only)
                        'airplay', // Airplay (currently Safari only)
                        'fullscreen', // Toggle fullscreen
                    ],
                    speed: { chosen: 1.0 }
                });
            } catch (error) {
                // console.error("Error initializing Plyr:", error);
                // Fallback or display an error message to the user
                // For example, hide the video player and show a message
                const videoElement = document.getElementById('myVideo');
                if (videoElement) {
                    videoElement.style.display = 'none';
                    const errorMessage = document.createElement('p');
                    errorMessage.textContent = 'Video player could not be loaded. Please try again later.';
                    videoElement.parentNode.insertBefore(errorMessage, videoElement.nextSibling);
                }
                return; // Stop further execution if player can't be initialized
            }

            // used for native event listeners where Plyr proxying might be tricky, though Plyr handles most
            const videoPlayer = player.media; 
            
            // Plyr creates a wrapper .plyr around the video. specific container ref is player.elements.container
            if (player.elements && player.elements.container) {
                player.elements.container.style.position = 'relative'; // Ensure relative positioning
            }

            // DYNAMICALLY CREATE AND INJECT TIMER OVERLAY INTO PLYR CONTAINER
            const timerOverlayHtml = `
                <div id="countdown-overlay" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(0,0,0,0.85); pointer-events: auto;">
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white;">
                        <div style="background: rgba(0,0,0,0.9); padding: 30px 50px; border-radius: 15px; border: 2px solid white; box-shadow: 0 0 20px rgba(0,0,0,0.5);">
                            <div id="countdown-text" style="font-size: 24px; font-weight: bold; margin-bottom: 15px; text-shadow: 1px 1px 3px black;">Next in</div>
                            <div id="countdown-number" style="font-size: 60px; font-weight: bold; line-height: 1; text-shadow: 1px 1px 3px black; margin-bottom: 20px;">3</div>
                            <button id="cancel-countdown-btn" style="background: transparent; border: 2px solid white; color: white; padding: 8px 20px; border-radius: 20px; font-weight: bold; cursor: pointer; pointer-events: auto; transition: all 0.3s;">Cancel</button>
                        </div>
                    </div>
                </div>
            `;
            // Plyr creates a wrapper .plyr around the video. specific container ref is player.elements.container
            if (player.elements && player.elements.container) {
                player.elements.container.insertAdjacentHTML('beforeend', timerOverlayHtml);
                
                // Add Cancel Listener
                const cancelBtn = player.elements.container.querySelector('#cancel-countdown-btn');
                if(cancelBtn) {
                     // Add hover effect
                     cancelBtn.addEventListener('mouseenter', () => {
                         cancelBtn.style.background = 'rgba(255,255,255,0.2)';
                     });
                     cancelBtn.addEventListener('mouseleave', () => {
                         cancelBtn.style.background = 'transparent';
                     });
                     
                     cancelBtn.addEventListener('click', (e) => {

                         e.preventDefault();
                         e.stopPropagation();
                         e.stopImmediatePropagation();
                         
                          // BLOCK ALL INTERACTIONS GLOBALLY
                          window.isBlockingInteraction = true;
                          // Reset after delay
                          setTimeout(() => { window.isBlockingInteraction = false; }, 500);

                          window.autoAdvanceCancelled = true;

                         
                         if(countdownInterval) {
                             clearInterval(countdownInterval);

                         }
                         
                         setTimeout(() => {
                             document.getElementById('countdown-overlay').style.display = 'none';
                         }, 50);
                         
                         return false; 
                     }, true); // Use capture phase to intercept click before it bubbles
                }
            } else {
                 // Fallback if plyr structure changes (shouldn't happen with std install)

                 document.body.insertAdjacentHTML('beforeend', timerOverlayHtml);
                 // Fix position for fallback
                 const fbOverlay = document.getElementById('countdown-overlay');
                 if(fbOverlay) fbOverlay.style.position = 'fixed';
                 
                 // Add cancel listener for fallback injection
                 const cancelBtnFallback = document.querySelector('#countdown-overlay #cancel-countdown-btn');
                 if(cancelBtnFallback) {
                     cancelBtnFallback.addEventListener('mouseenter', () => {
                         cancelBtnFallback.style.background = 'rgba(255,255,255,0.2)';
                     });
                     cancelBtnFallback.addEventListener('mouseleave', () => {
                         cancelBtnFallback.style.background = 'transparent';
                     });
                     
                     cancelBtnFallback.addEventListener('click', (e) => {

                         e.preventDefault();
                         e.stopPropagation();
                         e.stopImmediatePropagation();
                         
                         // BLOCK ALL INTERACTIONS GLOBALLY
                         window.isBlockingInteraction = true;
                         setTimeout(() => { window.isBlockingInteraction = false; }, 500);

                         window.autoAdvanceCancelled = true;

                         
                         if(countdownInterval) {
                             clearInterval(countdownInterval);

                         }
                         
                         setTimeout(() => {
                             document.getElementById('countdown-overlay').style.display = 'none';
                         }, 50);
                         
                         return false;
                     }, true);
                 }
            }

            // Note: videoSource is not directly used with Plyr source method, but keeping ref if needed
            const videoSource = document.getElementById('videoSource');
            const lessonActionBtn = document.getElementById('lessonActionBtn');
            const subtitleText = document.getElementById('subtitleText');
            const subtitleTrack = document.getElementById('subtitleTrack');
            const videoWrapper = document.querySelector('.video-wrapper');
            const downloadCertificateButton = document.getElementById('downloadCertificateButton');

            // ============================================
            // STATE VARIABLES
            // ============================================
            let currentVideoIndex = 0;
            let currentLessonId = null;
            let currentSignedUrl = null;
            let urlRefreshInterval = null;
            let unlockedLessons = new Set();
            let quizData = [];
            let correctAnswers = 0;
            let incorrectAnswers = 0;
            let skippedQuestions = 0;
            let userAnswers = {};
            let currentQuestionIndex = 0;
            let lessonsCompleted = false;
            let quizCompleted = false;
            let resultScreenVisible = false;
            let totalLesson = 0;
            let subtitlesEnabled = true;
            let isVideoFullscreen = false;
            let countdownInterval = null; // Countdown timer interval
            let autoAdvanceCancelled = false; // Flag to track if user cancelled auto-advance
            let isLoadingVideo = false; // Flag to prevent multiple simultaneous video loads
            let currentLoadAbortController = null; // AbortController for cancelling pending loads
            let pendingVideoLoad = null; // Track pending video load promise

            // Persistence Keys
            const COURSE_ID = {{ $course->id }};
            const STORAGE_KEY_LAST_LESSON = `course_${COURSE_ID}_last_lesson`;
            const STORAGE_KEY_TIME_PREFIX = `lesson_time_`;

            // Define globals
            window.watchedVideos = window.watchedVideos || {};
            var watchedVideos = window.watchedVideos;
            
            // Ensure userId is available
            window.userId = "{{ auth()->id() }}";
            if(window.userId === '' || window.userId === 'null') window.userId = null;
            
            window.isAuthenticated = "{{ auth()->check() ? 'true' : 'false' }}";
            // NEW: Inject Payment Status for JS logic
            window.userHasPaid = {{ auth()->check() && $course->userHasPaid(auth()->user()) ? 'true' : 'false' }};
            window.initialState = @json($initialState ?? null);
            


            // ============================================
            // INITIALIZATION
            // ============================================

            // GUEST ACCESS: Always unlock first lesson for everyone
            // But preserve completed status if it exists (set by server-side Blade)
            if (lessonLinks.length > 0) {
                const firstLesson = lessonLinks[0];
                // Only unlock if not already completed (preserve completed status from server)
                if (!firstLesson.classList.contains('completed')) {
                    unlockLesson(firstLesson);
                } else {
                    // If already completed, ensure it's not locked
                    firstLesson.classList.remove('locked');
                }

                // Setup first lesson click handler (works for guests too)
                firstLesson.addEventListener('click', async function(e) {
                    e.preventDefault();
                    
                    // Prevent rapid clicking - ignore if already loading
                    if (isLoadingVideo) {
                        return;
                    }
                    
                    if(this.classList.contains('locked')) { // Should be unlocked by logic above, but safety check
                         if (confirm('Please login to access this lesson. Redirect to login page?')) {
                             window.location.href = '{{ route('login.form', ['redirect_to' => url()->current()]) }}';
                         }
                         return;
                    }

                    const lessonId = this.getAttribute('data-lesson-id');
                    if (!lessonId) return;

                    // Update UI immediately for better responsiveness
                    lessonLinks.forEach(l => l.classList.remove('playing'));
                    this.classList.add('playing');
                    
                    // Update current lesson ID immediately
                    currentVideoIndex = 0;
                    currentLessonId = lessonId;
                    progressRecordedForCurrentLesson = false;

                    // Load video with signed URL (works for guests)
                    const success = await loadVideoWithSignedUrl(lessonId);

                    if (success && currentLessonId === lessonId) { // Double-check lesson hasn't changed
                        player.play().catch(err => { // Use player.play()

                        });
                        updatePlayIcon(this);
                    }
                });
            }

            // ADDED: Event delegation for Test Links (Quiz)
            document.addEventListener('click', function(e) {
                const testLink = e.target.closest('.test-link');
                if (testLink) {
                    e.preventDefault();
                    
                    // 1. PAYMENT CHECK
                    if (window.isAuthenticated === 'true' && !window.userHasPaid) {
                         // User is logged in but hasn't paid
                         // REMOVED: Scroll to pricing
                         showToast("Please enroll in the course to unlock this quizzes.", "lock");
                         return;
                    }
                    // 2. GUEST CHECK
                     if (window.isAuthenticated !== 'true') {
                          if (confirm('Please login to access quizzes. Redirect to login page?')) {
                              window.location.href = '{{ route('login.form', ['redirect_to' => url()->current()]) }}';
                          }
                          return;
                     }

                    if (testLink.classList.contains('locked')) {

                        // Optional: Unpaid users might see locked tests, handled above. 
                        // Paid users see locked tests if sequential.
                        if(window.userHasPaid) {
                             showToast("Complete previous lessons to unlock this quiz.", "lock");
                        }
                        return;
                    }
                    const sectionId = testLink.getAttribute('data-section-id');

                    if (sectionId) {
                        fetchQuizQuestions(sectionId);
                    }
                }
            });

            if (window.isAuthenticated === true || window.isAuthenticated === 'true') {
                // Authenticated user initialization
                fetchWatchedVideos();
                fetchUnlockedLessons();
                checkPaymentStatus();
                // Auto-load logic for authenticated users will effectively happen in fetchWatchedVideos response or similar
                // But simplified: we will wait a short moment for unlock status then load.
                setTimeout(autoLoadCurrentLesson, 1000); 
            } else {
                // Guest user initialization

                setupGuestExperience();
                // Auto-load first lesson for guests
                setTimeout(autoLoadCurrentLesson, 500);
            }
            
            // Initial call to set "0/X" immediately
            updateProgressBar(0, 0);

            // Safety Check: If still blank after 2s, force re-count
            setTimeout(() => {
                 const text = document.querySelector('.progress-text-overlay');
                 if(text && (text.textContent.trim() === '' || text.textContent === 'Wait...')) {
                     console.warn('Progress text empty, forcing update...');
                     const domLessons = document.querySelectorAll('.lesson-link').length || 0;
                     updateProgressBar(domLessons, 0);
                 }
            }, 2000);
            
            
            async function autoLoadCurrentLesson() {

                
                let targetLesson = null;
                
                // 1. Try to load last played lesson from storage
                const lastPlayedId = localStorage.getItem(STORAGE_KEY_LAST_LESSON);
                if (lastPlayedId) {
                    const lastLesson = document.querySelector(`.lesson-link[data-lesson-id="${lastPlayedId}"]`);
                    if (lastLesson && !lastLesson.classList.contains('locked')) {

                        targetLesson = lastLesson;
                        // Update current index
                        lessonLinks.forEach((l, idx) => {
                             if(l === targetLesson) currentVideoIndex = idx;
                        });
                    }
                }

                // 2. If no valid last played, find first unlocked
                if (!targetLesson) {
                    for(let i=0; i<lessonLinks.length; i++) {
                         if(!lessonLinks[i].classList.contains('locked')) {
                             targetLesson = lessonLinks[i];
                             currentVideoIndex = i;
                             break;
                         }
                    }
                }
                
                if(targetLesson) {
                    const lessonId = targetLesson.getAttribute('data-lesson-id');

                    
                    lessonLinks.forEach(l => l.classList.remove('playing'));
                    targetLesson.classList.add('playing');
                    
                    // Scroll to make the current lesson visible in the list
                    targetLesson.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Just load
                    await loadVideoWithSignedUrl(lessonId);
                    currentLessonId = lessonId; 
                    progressRecordedForCurrentLesson = false;
                    updatePlayIcon(targetLesson);
                    
                    // Restore time if available (Plyr 'ready' event might be too late/early, check in loadVideo)
                }
            }


            // Debug
            videoPlayer.addEventListener('loadedmetadata', function() {

                if (videoPlayer.textTracks.length > 0) {

                }
            });

            // ============================================
            // VIDEO PROTECTION LAYER
            // ============================================

            // Disable right-click on video (handled by Plyr usually, but good to keep)
            // Note: Plyr wraps the video in a container, so we should target the container or use Plyr events
            const plyrContainer = document.querySelector('.plyr');
            if(plyrContainer){
                 plyrContainer.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    return false;
                });
            } else {
                 videoPlayer.addEventListener('contextmenu', function(e) {
                     e.preventDefault();
                     return false;
                 });
            }

            // Disable keyboard shortcuts for download
            window.addEventListener('keydown', function(e) {
                // Target window to catch it globally if focus is on player
                 if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
                    e.preventDefault();
                    return false;
                }
            });

            // Tracking state
            let progressRecordedForCurrentLesson = false;

            player.on('play', function() {
                if (!progressRecordedForCurrentLesson && currentLessonId) {

                    saveWatchedVideo(currentLessonId, 'unlocked');
                    progressRecordedForCurrentLesson = true;
                }
            });

            // ============================================
            // SIGNED URL GENERATION
            // ============================================

            // Cache Mechanism
            const videoUrlCache = new Map();

            async function getSignedVideoUrl(lessonId) {
                // Check cache first
                if (videoUrlCache.has(lessonId)) {
                    try {
                        const cachedData = await videoUrlCache.get(lessonId);
                        // Check validity (cache expiration check)
                        if (cachedData && cachedData.expires_at) {
                            const now = Date.now() / 1000;
                            if (now < (cachedData.expires_at - 60)) { // 60s buffer

                                return cachedData;
                            }
                        }

                        videoUrlCache.delete(lessonId);
                    } catch (e) {
                        videoUrlCache.delete(lessonId);
                    }
                }



                // Create a promise and cache it immediately to prevent duplicate requests
                const fetchPromise = (async () => {
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            throw new Error('CSRF token missing');
                        }

                        const response = await fetch(`/video/generate-url/${lessonId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content,
                                'Accept': 'application/json'
                            },
                        });

                        if (!response.ok) {
                             const errorData = await response.json().catch(() => ({}));
                             if (response.status === 401) window.location.href = '/login';
                             throw new Error(errorData.message || 'Failed to load video');
                        }

                        const data = await response.json();
                        return data;
                    } catch (error) {
                        // Wait for a small delay before throwing to avoid rapid retry loops if this was called in a loop
                        throw error;
                    }
                })();

                videoUrlCache.set(lessonId, fetchPromise);

                try {
                    return await fetchPromise;
                } catch (error) {
                    videoUrlCache.delete(lessonId); // Clear cache on error

                    return null;
                }
            }

            async function loadVideoWithSignedUrl(lessonId) {
                // Cancel any pending video load
                if (currentLoadAbortController) {
                    currentLoadAbortController.abort();
                    currentLoadAbortController = null;
                }
                
                // If already loading, wait for current load or cancel it
                if (isLoadingVideo && pendingVideoLoad) {
                    // Cancel previous load
                    if (currentLoadAbortController) {
                        currentLoadAbortController.abort();
                    }
                }
                
                // Set loading state
                isLoadingVideo = true;
                const loadAbortController = new AbortController();
                currentLoadAbortController = loadAbortController;
                
                // Pause current video immediately for better UX
                if (player && !player.paused) {
                    player.pause();
                }

                try {
                    // 1. Parallel Fetch: Video URL & Subtitles
                    const videoUrlPromise = getSignedVideoUrl(lessonId);
                    
                    // Fetch subtitles but allow failure (with abort signal)
                    const subtitleFetch = fetch(`/subtitles/status/${lessonId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        signal: loadAbortController.signal
                    }).then(res => res.json()).catch(err => {
                        if (err.name === 'AbortError') return null;
                        return null;
                    });

                    // 2. Race Subtitles against a 200ms timeout (reduced from 300ms for faster switching)
                    const timeoutPromise = new Promise(resolve => setTimeout(() => resolve('timeout'), 200));
                    
                    const subtitlePromise = Promise.race([subtitleFetch, timeoutPromise]);

                    // Wait for Video URL (Mandatory)
                    const urlData = await videoUrlPromise;
                    
                    // Check if this load was cancelled
                    if (loadAbortController.signal.aborted) {
                        return false;
                    }

                    if (!urlData || !urlData.video_url) {
                        isLoadingVideo = false;
                        currentLoadAbortController = null;
                        return false;
                    }

                    // Check if subtitle won or timed out
                    let subtitleData = await subtitlePromise;
                    
                    // Check again if cancelled
                    if (loadAbortController.signal.aborted) {
                        return false;
                    }
                    
                    if (subtitleData === 'timeout') {
                        subtitleData = null; // Proceed without subtitles
                    }

                    currentLessonId = lessonId;
                    currentSignedUrl = urlData.video_url;

                    // 3. Prepare Subtitle Tracks
                    let tracks = [];
                    if (subtitleData && subtitleData.status === 'completed' && subtitleData.vtt_path) {
                        tracks.push({
                            kind: 'captions',
                            label: 'English',
                            srclang: 'en',
                            src: subtitleData.vtt_path,
                            default: true,
                        });
                    }

                    // 4. Set Video Source (Once) - only if not cancelled
                    if (!loadAbortController.signal.aborted) {
                        player.source = {
                            type: 'video',
                            title: 'Lesson Video',
                            sources: [
                                {
                                    src: currentSignedUrl,
                                    type: 'video/mp4',
                                },
                            ],
                            tracks: tracks
                        };
                    } else {
                        isLoadingVideo = false;
                        currentLoadAbortController = null;
                        return false;
                    }

                    // 5. Setup Persistence & Refresh
                    setupUrlRefresh(lessonId, urlData.expires_in);
                    localStorage.setItem(STORAGE_KEY_LAST_LESSON, lessonId);
                    
                    const savedTime = localStorage.getItem(STORAGE_KEY_TIME_PREFIX + lessonId);
                    if (savedTime && !loadAbortController.signal.aborted) {
                        const time = parseFloat(savedTime);
                        if (time > 0) {
                             const restoreTime = () => {
                                 if (loadAbortController.signal.aborted) return;
                                 player.currentTime = time;
                                 player.off('ready', restoreTime);
                                 player.off('loadedmetadata', restoreTime);
                                 player.off('canplay', restoreTime);
                             };
                             // Listen to multiple events to ensure valid restore point
                             player.on('ready', restoreTime);
                             player.on('loadedmetadata', restoreTime);
                             player.on('canplay', restoreTime);
                        }
                    }

                    // 6. Prefetch Next Video (Optimization) - only if not cancelled
                    if (!loadAbortController.signal.aborted) {
                        setTimeout(() => prefetchNextVideo(), 2000);
                    }

                    isLoadingVideo = false;
                    currentLoadAbortController = null;
                    return true;

                } catch (error) {
                    // Ignore abort errors
                    if (error.name === 'AbortError') {
                        isLoadingVideo = false;
                        currentLoadAbortController = null;
                        return false;
                    }
                    
                    isLoadingVideo = false;
                    currentLoadAbortController = null;
                    return false;
                }
            }
            
            function prefetchNextVideo() {
                if (!currentLessonId) return;
                
                const all = document.querySelectorAll('.lesson-link');
                let found = -1;
                all.forEach((l, i) => { 
                    if(l.getAttribute('data-lesson-id') == currentLessonId) found = i; 
                });
                
                if (found !== -1 && found < all.length - 1) {
                    const nextLink = all[found + 1];
                    const nextId = nextLink.getAttribute('data-lesson-id');
                    if (nextId && !nextLink.classList.contains('locked')) {

                        getSignedVideoUrl(nextId); // Trigger caching
                    }
                }
            }
            
            // Global Time Saver
            player.on('timeupdate', function(event) {
                 if(currentLessonId && player.currentTime > 5) {
                      // Guard: Only save if we are past 5 seconds to avoid overwriting a long resume time 
                      // with "0" or "1" if the restore logic fails or lags.
                      // Also avoid saving end-of-video state if it loops?
                      if (!player.paused || player.currentTime != 0) {
                           localStorage.setItem(STORAGE_KEY_TIME_PREFIX + currentLessonId, player.currentTime);
                      }
                 }
            });

            async function updateSubtitleTrack(lessonId) {
                try {
                    const response = await fetch(`/subtitles/status/${lessonId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    
                    // Manage tracks via Plyr source if possible, or appending track element
                    // Plyr prefers tracks in the source object.
                    // However, since we already set source, we might have to re-set it or add track manually.
                    // Re-setting source reset playback, so let's try to add track dynamically if possible.
                    // Actually, Plyr allows updating `source` which includes tracks.
                    
                    let tracks = [];
                    if (data.status === 'completed' && data.vtt_path) {

                         tracks.push({
                            kind: 'captions',
                            label: 'English',
                            srclang: 'en',
                            src: data.vtt_path,
                            default: true,
                        });
                    }

                    // To update tracks in Plyr without resetting video, it's tricky.
                    // But usually captions are loaded nicely with the source.
                    // If we can get subtitle info BEFORE setting source, that's better.
                    // But here we do it after.
                    
                    if(tracks.length > 0) {
                        // We have to update the source to include tracks.
                        // Ideally we should fetch subtitles parallel to signed URL to avoid double set.
                        // For now, let's just update the source again with tracks? No, that restarts video.
                        // Let's use the native track element if Plyr allows it (it usually scans for it).
                        // Or just update the source object with currentSignedUrl AND tracks.
                        
                        player.source = {
                            type: 'video',
                            title: 'Lesson Video',
                            sources: [
                                {
                                    src: currentSignedUrl,
                                    type: 'video/mp4',
                                },
                            ],
                            tracks: tracks
                        };
                    }

                } catch (error) {

                }
            }

            function setupUrlRefresh(lessonId, expiresIn) {
                if (urlRefreshInterval) {
                    clearInterval(urlRefreshInterval);
                }

                const refreshTime = (expiresIn * 0.8) * 1000;



                urlRefreshInterval = setInterval(async () => {


                    const newUrlData = await getSignedVideoUrl(lessonId);

                    if (newUrlData && newUrlData.video_url) {
                        const currentTime = videoPlayer.currentTime;
                        const wasPlaying = !videoPlayer.paused;

                        currentSignedUrl = newUrlData.video_url;
                        videoSource.src = currentSignedUrl;
                        videoPlayer.load();

                        videoPlayer.currentTime = currentTime;

                        if (wasPlaying) {
                            player.play().catch(err => { // Plyr play
                                // console.log('Auto-play prevented:', err);
                            });
                        }


                    }
                }, refreshTime);
            }

            // ============================================
            // FETCH COURSE DATA
            // ============================================

            function fetchWatchedVideos() {
                // OPTIMIZED: Use server-injected state if available
                if (window.initialState && window.initialState.watchedLessonsCount !== undefined) {

                    // Update progress bar using DOM-based counting for consistency
                    updateProgressBar();
                    
                    // Handle completed tests
                    if (window.initialState.completedTestSections) {
                         const completedSections = window.initialState.completedTestSections;
                        document.querySelectorAll('.test-link').forEach(link => {
                            const sectionId = parseInt(link.getAttribute('data-section-id'));
                            if (completedSections.includes(sectionId)) {
                                link.classList.add('completed');
                                link.classList.remove('unlocked');
                            }
                        });
                    }
                    return; 
                }


                
                if (!window.userId) {

                    const text = document.querySelector('.progress-text-overlay');
                    if(text) text.textContent = "Log In";
                    return;
                }
                
                // Fetch Lesson Progress
                $.ajax({
                    url: '{{ route('lessons.progress') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        course_id: {{ $course->id }},
                        user_id: window.userId
                    },
                    success: function(response) {

                        if (response && response.course_id && response.total_lessons !== undefined &&
                            response.watched_lessons !== undefined) {
                            totalLesson = response.total_lessons;
                            // Update progress bar using DOM-based counting for consistency
                            updateProgressBar();
                        }
                    }
                });
                
                // NEW: Fetch Completed Tests for Visual Marking
                $.ajax({
                    url: '{{ route('tests.completed') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        course_id: {{ $course->id }},
                        user_id: window.userId
                    },
                    success: function(response) {

                        if (response && response.completed_test_sections) {
                            const completedSections = response.completed_test_sections;
                            
                            document.querySelectorAll('.test-link').forEach(link => {
                                const sectionId = parseInt(link.getAttribute('data-section-id'));
                                if (completedSections.includes(sectionId)) {
                                    link.classList.add('completed');
                                    link.classList.remove('unlocked');
                                }
                            });
                        }
                    }
                });
            }

            function updateProgressBar() {
                // ALWAYS count from DOM for consistency
                const allLessonLinks = document.querySelectorAll('.lesson-link');
                const completedLessonLinks = document.querySelectorAll('.lesson-link.completed');
                
                const totalLessons = allLessonLinks.length || 0;
                const watchedLessons = completedLessonLinks.length || 0;

                const percentage = (totalLessons > 0) ? (watchedLessons / totalLessons) * 100 : 0;
                const circle = document.querySelector('.progresss-ring-fill');
                const text = document.querySelector('.progress-text-overlay'); 
                
                if (circle && text) {
                    const radius = 20;
                    const circumference = 2 * Math.PI * radius;
                    const offset = circumference - (percentage / 100) * circumference;
                    circle.style.strokeDashoffset = offset;
                    

                    text.textContent = `${watchedLessons}/${totalLessons}`;
                }
            }

            function fetchUnlockedLessons() {
                // OPTIMIZED: Use server-injected state
                if (window.initialState && window.initialState.unlockedLessonIds) {

                     unlockedLessons = new Set(window.initialState.unlockedLessonIds);
                     lessonLinks.forEach((link) => {
                        const lessonId = link.getAttribute('data-lesson-id');
                        if (unlockedLessons.has(parseInt(lessonId))) {
                            unlockLesson(link, false); // false = do not save to DB
                        }
                    });
                     if (unlockedLessons.size === 0 && lessonLinks.length > 0) {
                        const firstLesson = lessonLinks[0];
                        // Only unlock if not already completed
                        if (!firstLesson.classList.contains('completed')) {
                            unlockLesson(firstLesson, false);
                        } else {
                            firstLesson.classList.remove('locked');
                        }
                    }
                    return;
                }

                if (!window.userId) return;
                $.ajax({
                    url: '{{ route('lessons.unlocked') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        course_id: {{ $course->id }},
                        user_id: window.userId
                    },
                    success: function(response) {
                        unlockedLessons = new Set(response.unlocked_lessons);
                        // console.log("Fetching lessons:", response.unlocked_lessons);
                        lessonLinks.forEach((link) => {
                            const lessonId = link.getAttribute('data-lesson-id');
                            // Only unlock if not already completed (preserve completed status from server)
                            if (unlockedLessons.has(parseInt(lessonId)) && !link.classList.contains('completed')) {
                                unlockLesson(link);
                            } else if (unlockedLessons.has(parseInt(lessonId)) && link.classList.contains('completed')) {
                                // Ensure completed lessons are not locked
                                link.classList.remove('locked');
                            }
                        });
                        if (unlockedLessons.size === 0 && lessonLinks.length > 0) {
                            const firstLesson = lessonLinks[0];
                            // Only unlock if not already completed
                            if (!firstLesson.classList.contains('completed')) {
                                unlockLesson(firstLesson);
                            } else {
                                firstLesson.classList.remove('locked');
                            }
                        }
                    },
                    error: function(error) {
                        // console.error('Error fetching unlocked lessons', error);
                    }
                });
            }

            function checkPaymentStatus() {
                 // OPTIMIZED: Use server-injected state
                 if (window.initialState && window.initialState.hasPaid !== undefined) {

                      if (window.initialState.hasPaid) {
                            setupPaidUserExperience();
                        } else {
                            if (!window.isAuthenticated || window.isAuthenticated === 'false') {
                                 setupGuestExperience(); // Ensure guests get guest exp
                            } else {
                                 setupFreeUserExperience();
                            }
                        }
                      return;
                 }

                if (!window.userId) return;
                $.ajax({
                    url: '{{ route('payment.status') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        course_id: {{ $course->id }},
                        user_id: window.userId
                    },
                    success: function(response) {
                        if (response.payment_status === 1) {
                            setupPaidUserExperience();
                        } else {
                            setupFreeUserExperience();
                        }
                    },
                    error: function(error) {
                        // console.error('Error checking payment status', error);
                    }
                });
            }

            // ============================================
            // USER EXPERIENCE SETUP
            // ============================================

            // ============================================
            // USER EXPERIENCE SETUP & AUTO-ADVANCE
            // ============================================
            
            window.triggerCongratulations = function(title, message) {
                const modal = document.getElementById('congratulationsModal');
                const titleEl = document.getElementById('congratsTitle');
                const msgEl = document.getElementById('congratsMessage');
                
                titleEl.textContent = title || "Congratulations!";
                msgEl.textContent = message || "You have successfully completed the requirements!";
                
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                
                // Trigger Premium Confetti Animation
                const duration = 4 * 1000;
                const animationEnd = Date.now() + duration;
                const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 300000 };

                const interval = setInterval(function() {
                    const timeLeft = animationEnd - Date.now();

                    if (timeLeft <= 0) {
                        return clearInterval(interval);
                    }

                    const particleCount = 50 * (timeLeft / duration);
                    confetti(Object.assign({}, defaults, { 
                        particleCount, 
                        origin: { x: 0.1, y: 0.6 } 
                    }));
                    confetti(Object.assign({}, defaults, { 
                        particleCount, 
                        origin: { x: 0.9, y: 0.6 } 
                    }));
                }, 250);

                // Auto-close after 5 seconds
                setTimeout(function() {
                    hideCongratulations();
                }, 5000);
            };

            window.hideCongratulations = function() {
                document.getElementById('congratulationsModal').style.display = 'none';
                document.body.style.overflow = 'auto';
            };

            // Reusable Toast Notification - Disabled as per user request
            // Reusable Toast Notification - Restored for Payment Alerts
            function showToast(message, type = 'info') {
                // Determine color based on type
                let bgColor = "#333";
                if(type === 'success') bgColor = "#28a745";
                if(type === 'error') bgColor = "#dc3545";
                if(type === 'lock') bgColor = "#ffc107"; // Yellow/Orange for lock
                
                // Simple Toast using Toastify or custom DIV
                // Check if Toastify exists, otherwise custom
                if (typeof Toastify === 'function') {
                    Toastify({
                        text: message,
                        duration: 3000,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        backgroundColor: bgColor,
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                    }).showToast();
                } else {
                    // Fallback Custom Toast (Top Right)
                    let toast = document.createElement("div");
                    toast.className = "custom-toast";
                    toast.innerText = message;
                    toast.style.position = "fixed";
                    toast.style.top = "80px"; // Below navbar
                    toast.style.right = "20px";
                    toast.style.background = bgColor;
                    toast.style.color = type === 'lock' ? '#333' : '#fff'; // Keep text readable
                    toast.style.padding = "12px 24px";
                    toast.style.borderRadius = "8px";
                    toast.style.zIndex = "10000";
                    toast.style.boxShadow = "0 4px 12px rgba(0,0,0,0.15)";
                    toast.style.fontWeight = "600";
                    toast.style.minWidth = "250px";
                    toast.style.transform = "translateX(120%)"; // Start off-screen
                    toast.style.transition = "transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)";
                    
                    document.body.appendChild(toast);
                    
                    // Trigger Slide In
                    requestAnimationFrame(() => {
                        toast.style.transform = "translateX(0)";
                    });
                    
                    setTimeout(() => {
                        toast.style.transform = "translateX(120%)"; // Slide Out
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }
            }

            function resetLessonListeners() {
                // Clone and replace to strip all previous event listeners
                const links = document.querySelectorAll('.lesson-link');
                links.forEach(link => {
                    const newLink = link.cloneNode(true);
                    link.parentNode.replaceChild(newLink, link);
                });
                // Re-select the fresh elements
                return document.querySelectorAll('.lesson-link');
            }

            function setupGuestExperience() {

                const links = resetLessonListeners(); // Reset first
                const localLessonLinks = Array.from(links);

                localLessonLinks.forEach((link, index) => {
                    // Lock all except first
                    if (index > 0) {
                        link.classList.add('locked');
                        link.classList.remove('unlocked', 'completed', 'playing');
                        
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (confirm('Please login to access this lesson. Redirect to login page?')) {
                                window.location.href = '{{ route('login.form', ['redirect_to' => url()->current()]) }}';
                            }
                        });
                    } else {
                        // First lesson (Guest) - Allow Play but preserve completed status
                        link.classList.remove('locked');
                        // Only add 'unlocked' if not already completed
                        if (!link.classList.contains('completed')) {
                            link.classList.add('unlocked');
                        }
                        
                    link.addEventListener('click', async function(e) {
                        e.preventDefault();
                        
                        // Prevent rapid clicking
                        if (isLoadingVideo) {
                            return;
                        }
                        
                        const lessonId = this.getAttribute('data-lesson-id');
                        if (!lessonId) return;
                        
                        // Update UI immediately
                        localLessonLinks.forEach(l => l.classList.remove('playing'));
                        this.classList.add('playing');
                        
                        currentVideoIndex = 0;
                        currentLessonId = lessonId;
                        progressRecordedForCurrentLesson = false;
                        
                        const success = await loadVideoWithSignedUrl(lessonId);
                        if (success && currentLessonId === lessonId) {
                            player.play().catch(console.error);
                            updatePlayIcon(this);
                        }
                    });
                    }
                });

                // Guest Auto-Advance: Just visual update, prompt login for next
                player.off('ended'); // Clear previous ended listeners
                player.on('ended', function() {
                    const currentLink = localLessonLinks[0];
                    if (currentLink) updateLessonIconToCompleted(currentLink);
                    
                    // Show Login Button
                    setTimeout(updateTopActionButton, 300);
                });
                
                lockAllTestsAndArticles();
            }

            function setupFreeUserExperience() {
                const links = resetLessonListeners();
                const localLessonLinks = Array.from(links);

                // Auto Unlock First Lesson
                if (localLessonLinks.length > 0) unlockLesson(localLessonLinks[0]);

                localLessonLinks.forEach((link, index) => {
                    link.addEventListener('click', async function(e) {
                        e.preventDefault();
                        
                        // Prevent rapid clicking
                        if (isLoadingVideo) {
                            return;
                        }

                        if (this.classList.contains('locked')) {
                            // Locked Feedback for Free User
                            // REMOVED: Scroll to pricing
                            
                            showToast("Please enroll in the course to unlock this lesson.", 'lock');
                            return;
                        }

                        // Play Video
                        const lessonId = this.getAttribute('data-lesson-id');
                        if (!lessonId) return;

                        // Update UI immediately
                        localLessonLinks.forEach(l => l.classList.remove('playing'));
                        this.classList.add('playing');
                        
                        currentLessonId = lessonId;
                        currentVideoIndex = index;
                        progressRecordedForCurrentLesson = false;

                        const success = await loadVideoWithSignedUrl(lessonId);
                        if (success && currentLessonId === lessonId) {
                            player.play().catch(console.error);
                            updatePlayIcon(this);
                        }
                    });
                });

                lockAllTestsAndArticles();

                // Free User Auto-Advance
                player.off('ended');
                player.on('ended', function() {
                    const currentLink = localLessonLinks[currentVideoIndex];
                    if (currentLink) {
                        updateLessonIconToCompleted(currentLink);
                        saveWatchedVideo(currentLessonId, 'completed');
                    }
                    
                    // Logic for next lesson (which is likely Locked)
                    const nextLink = localLessonLinks[currentVideoIndex + 1];
                    if(nextLink) {
                         // It's locked for free user. Trigger Payment Prompt?
                         // Or just show button.
                         // Let's SHOW the button "Buy Now"
                         setTimeout(updateTopActionButton, 300);
                         
                         // Optional: Small toast
                         showToast("Next lesson is locked. Enroll to continue!", 'info');
                    }
                });
            }

            function setupPaidUserExperience() {
                // RESET: Remove old listeners locally
                const links = resetLessonListeners();
                const localLessonLinks = Array.from(links);

                // 1. Initial State: Lock EVERYTHING except the first one
                //    (Unless specific completion data is already available to unlock more)
                localLessonLinks.forEach((link, index) => {
                    // Default to locked (unless it's the very first item)
                    if (index > 0) {
                        link.classList.add('locked');
                        link.classList.remove('unlocked');
                        
                        // Ensure lock icon exists
                        if (!link.querySelector('.icon-lock')) {
                             const lock = document.createElement('i');
                             lock.className = 'fas fa-lock icon-lock';
                             const iconContainer = link.querySelector('.state-icon');
                             if(iconContainer) iconContainer.prepend(lock);
                        }
                    } else {
                        // First item always unlocked but preserve completed status
                        link.classList.remove('locked');
                        // Only add 'unlocked' if not already completed
                        if (!link.classList.contains('completed')) {
                            link.classList.add('unlocked');
                        }
                    }
                    
                    // Attach Click Listener (Generic)
                    link.addEventListener('click', async function(e) {
                         e.preventDefault();
                         
                         // Prevent rapid clicking
                         if (isLoadingVideo && !this.classList.contains('test-link')) {
                             return;
                         }
                         
                         // LOCKED CHECK
                         if (this.classList.contains('locked')) {
                            e.stopImmediatePropagation();
                            showToast("Please complete the previous lesson/quiz to unlock this.", 'lock');
                            return;
                         }
                         
                         // COMPLETED QUIZ CHECK (Auto-Advance)
                         if (this.classList.contains('test-link') && this.classList.contains('completed')) {
                             // reuse existing logic for this...
                             handleCompletedQuizClick(this);
                             return;
                         }

                         // PLAY/OPEN LOGIC
                         if (this.classList.contains('test-link')) {
                             // Open Quiz
                             const sectionId = this.getAttribute('data-section-id');
                             fetchQuizQuestions(sectionId);
                         } else {
                             // Play Video
                             const lessonId = this.getAttribute('data-lesson-id');
                             if (!lessonId) return;

                             // Update UI immediately
                             localLessonLinks.forEach(l => l.classList.remove('playing'));
                             this.classList.add('playing');
                             
                             currentLessonId = lessonId;
                             // Update global index
                             currentVideoIndex = localLessonLinks.indexOf(this);
                             progressRecordedForCurrentLesson = false;

                             const success = await loadVideoWithSignedUrl(lessonId);
                             if (success && currentLessonId === lessonId) {
                                 player.play().catch(console.error);
                                 updatePlayIcon(this);
                             }
                         }
                    });
                });
                
                unlockAllArticles(); // Articles stay unlocked for paid users? Or should they also be sequential? 
                // User said "quizes ... unlock like flow of lessons". Articles are usually supplementary.
                // Leaving articles unlocked/handled separately for now unless requested.

                // 2. APPLY SEQUENTIAL UNLOCKING based on completion status
                // We need to wait for completion data (which might come from fetchWatchedVideos)
                // fetchWatchedVideos calls updateProgressBar, but we can hook into it.
                // Ideally we run a "refreshSequentialLocks()" function.
                setTimeout(refreshSequentialLocks, 500); 

                // Paid User Auto-Advance
                player.off('ended');
                player.on('ended', function() {
                    const currentLink = localLessonLinks[currentVideoIndex];
                    if (currentLink) {
                        updateLessonIconToCompleted(currentLink);
                        saveWatchedVideo(currentLessonId, 'completed');
                    }

                    // Handle Next Lesson
                    const nextLink = localLessonLinks[currentVideoIndex + 1];
                    if (nextLink) {
                        unlockLesson(nextLink, true); // Unlock it

                    } else {
                        // Course Completed
                        lessonsCompleted = true;
                        triggerCongratulations("Course Completed!", "Congratulations! You've finished all the lessons. Complete the review to get your certificate.");
                        setTimeout(updateTopActionButton, 300);
                    }
                });
            }

            function handleCompletedQuizClick(link) {

                 const nextContent = getNextContent(false, link, true);
                 if (nextContent) {
                     // Toast removed for smoother UX
                     if (nextContent.type === 'lesson') {
                         nextContent.element.click();
                     } else if (nextContent.type === 'test') {
                         fetchQuizQuestions(nextContent.sectionId);
                     }
                 } else {
                     showToast("Quiz already completed. Section done!", "success");
                 }
            }

            function refreshSequentialLocks() {
                const links = document.querySelectorAll('.lesson-link');
                let previousCompleted = true; // First item is always accessible if previous (virtual) is "completed"
                
                links.forEach((link, index) => {
                    // Logic: You can access this item IF the previous item is completed OR if it's the first item.
                    
                    if (previousCompleted) {
                        // Unlock this item
                        link.classList.remove('locked');
                        // PRESERVE COMPLETION: Don't add 'unlocked' if it's already completed
                        if (!link.classList.contains('completed')) {
                             link.classList.add('unlocked');
                        }
                    } else {
                        // Lock this item
                        link.classList.add('locked');
                        link.classList.remove('unlocked');
                    }
                    
                    // Update state for next iteration
                    // This item is "completed" if it has the class 'completed'
                    if (!link.classList.contains('completed')) {
                        previousCompleted = false;
                    }
                });
            }

            // ============================================
            // LESSON MANAGEMENT
            // ============================================

    function unlockLesson(lessonLink, saveToDb = false) {
        if (!lessonLink) return;

        // Preserve completed status - don't overwrite if already completed
        const isCompleted = lessonLink.classList.contains('completed');
        
        if (lessonLink.classList.contains('locked')) {
            lessonLink.classList.remove('locked');
            // Only add 'unlocked' if not already completed
            if (!isCompleted) {
                lessonLink.classList.add('unlocked');
            }
        }

        const lessonId = lessonLink.getAttribute('data-lesson-id');
        // Only track progress for actual lessons that have IDs
        if (lessonId) {
            unlockedLessons.add(lessonId);
            if (saveToDb && window.userId && window.userId !== 'null') {
                saveLessonProgress(lessonId);
            }
        }
    }

    function updateLessonIconToCompleted(lessonLink) {
        if (!lessonLink) return;
        lessonLink.classList.add('completed');
        lessonLink.classList.remove('unlocked'); // Completed implies unlocked, but visually distinct
        
        // Ensure UI updates
        updateSidebarProgress();
    }

    function updatePlayIcon(currentLink) {
        // Query fresh elements because they might have been replaced/cloned
        const currentLinks = document.querySelectorAll('.lesson-link');
        currentLinks.forEach(link => {
            link.classList.remove('playing');
        });

        // Add 'playing' to the current one
        if (currentLink) {
            currentLink.classList.add('playing');
            // Scroll to make the current lesson visible in the list
            currentLink.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
            
            async function autoLoadCurrentLesson() {

                
                // Refresh links list
                const currentLinks = document.querySelectorAll('.lesson-link');
                let targetLesson = null;
                
                // 1. Try to load last played lesson from storage
                const lastPlayedId = localStorage.getItem(STORAGE_KEY_LAST_LESSON);
                if (lastPlayedId) {
                    const lastLesson = document.querySelector(`.lesson-link[data-lesson-id="${lastPlayedId}"]`);
                    if (lastLesson && !lastLesson.classList.contains('locked')) {

                        targetLesson = lastLesson;
                        // Update current index
                        currentLinks.forEach((l, idx) => {
                             if(l === targetLesson) currentVideoIndex = idx;
                        });
                    }
                }

                // 2. If no valid last played, find first unlocked
                if (!targetLesson) {
                    for(let i=0; i<currentLinks.length; i++) {
                         if(!currentLinks[i].classList.contains('locked')) {
                             targetLesson = currentLinks[i];
                             currentVideoIndex = i;
                             break;
                         }
                    }
                }
                
                if(targetLesson) {
                    const lessonId = targetLesson.getAttribute('data-lesson-id');

                    
                    targetLesson.classList.add('playing'); // Visual immediate feedback
                    
                    // Scroll to make the current lesson visible in the list
                    targetLesson.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Just load
                    await loadVideoWithSignedUrl(lessonId);
                    currentLessonId = lessonId; 
                    progressRecordedForCurrentLesson = false;
                    updatePlayIcon(targetLesson);
                    
                    // Restore time...
                }
            }

            function saveWatchedVideo(lessonId, status = 'completed') {

                $.ajax({
                    url: '{{ route('lesson.complete') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        lesson_id: lessonId,
                        user_id: window.userId,
                        status: status
                    },
                    success: function(response) {

                        
                        // Handle Guest Success
                        if (response.status === 'guest_success') {
                            // CRITICAL: If we THOUGHT we were logged in (window.userId exists), but server says Guest,
                            // then our session expired. Do NOT mark visually complete, or it will be lost on refresh.
                            if (window.userId && window.userId !== 'null') {

                                showToast("Session expired. Please refresh to save progress.", "error");
                                // Optionally reload page automatically?
                                // window.location.reload(); 
                                return; // STOP UPATING UI
                            }
                            
                            // Normal Guest behavior
                            if (response.course_id) {
                                document.cookie = `guest_watched_course_${response.course_id}=true; path=/; max-age=${30*24*60*60}`;
                                localStorage.setItem(`guest_watched_course_${response.course_id}`, 'true');
                            }
                        }
                        
                        // 1. Mark Current as Completed
                        const currentLink = document.querySelector(`.lesson-link[data-lesson-id="${lessonId}"]`);
                        if(currentLink) {
                            updateLessonIconToCompleted(currentLink);
                        }

                        // 2. Unlock Next Content (Lesson or Test)
                        // Use ignoreLocks=true because we want to FIND the next item even if it is locked, so we can unlock it.
                        const nextContent = getNextContent(false, null, true);
                        if(nextContent && nextContent.element) {

                            unlockLesson(nextContent.element, true);
                        }

                        // 3. Refresh Backend State
                        if (window.userId && window.userId !== 'null') {
                            fetchWatchedVideos();
                        }

                        // 4. Update UI
                        updateSidebarProgress();
                    },
                    error: function(error) {
                        // console.error('Error saving watched video', error);
                    }
                });
            }

            function updateSidebarProgress() {
                const lessonLinks = document.querySelectorAll('.lesson-link');
                const total = lessonLinks.length;
                let completed = 0;
                
                lessonLinks.forEach(link => {
                    // FIX: Only count if explicitly marked completed
                    if (link.classList.contains('completed')) {
                        completed++;
                    }
                });
                
                // Update Text
                const textOverlay = document.querySelector('.progress-text-overlay');
                if (textOverlay) {
                    textOverlay.textContent = `${completed}/${total}`;
                }
                
                // Update Ring
                const circle = document.querySelector('.progresss-ring-fill');
                if (circle) {
                    const radius = circle.r.baseVal.value;
                    const circumference = 2 * Math.PI * radius;
                    const percent = total > 0 ? (completed / total) * 100 : 0;
                    const offset = circumference - (percent / 100) * circumference;
                    
                    circle.style.strokeDashoffset = offset;
                }

                // Update Status Box if Completed
                const statusBox = document.getElementById('courseStatusBox');
                if (statusBox && total > 0 && completed === total) {
                    statusBox.classList.remove('alert-success');
                    statusBox.classList.add('alert-primary');
                    statusBox.style.borderColor = '#0d6efd';
                    statusBox.style.background = '#cfe2ff';
                    statusBox.style.color = '#084298';
                    
                    const title = statusBox.querySelector('.status-title');
                    const subtitle = statusBox.querySelector('.status-subtitle');
                    
                    if(title) title.innerHTML = '<i class="fas fa-trophy" style="margin-right: 5px;"></i> Congratulations!';
                    if(subtitle) subtitle.textContent = 'Course Completed';
                }
            }

            // Initial call on load
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(updateSidebarProgress, 1000); // Small delay to allow initial backend states to settle
            });

            function saveLessonProgress(lessonId) {
                if (!window.userId || window.userId === 'null') return;
                $.ajax({
                    url: '{{ route('lesson.complete') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        lesson_id: lessonId,
                        user_id: window.userId,
                        status: 'unlocked'
                    },
                    success: function(response) {
                        // console.log('Lesson progress saved:', response);
                    },
                    error: function(error) {
                        // console.error('Error saving lesson progress', error);
                    }
                });
            }

            // ============================================
            // TESTS & ARTICLES
            // ============================================

            function lockAllTestsAndArticles() {
                testLinks.forEach(link => {
                    if (!link.querySelector('i.fal.fa-lock-alt')) {
                        const lockIcon = document.createElement('i');
                        lockIcon.classList.add('fal', 'fa-lock-alt');
                        link.prepend(lockIcon);
                        link.classList.add('locked');
                    }
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        alert('Please complete the payment to access the test.');
                    });
                });

                articleLinks.forEach(link => {
                    if (!link.querySelector('i.fal.fa-lock-alt')) {
                        const lockIcon = document.createElement('i');
                        lockIcon.classList.add('fal', 'fa-lock-alt');
                        link.prepend(lockIcon);
                        link.classList.add('locked');
                    }
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        alert('Please complete the payment to access the article.');
                    });
                });
            }

            function unlockAllTests() {
                testLinks.forEach(link => {
                    link.classList.remove('locked');
                    const lockIcon = link.querySelector('i.fal.fa-lock-alt');
                    if (lockIcon) {
                        lockIcon.remove();
                    }
                    link.addEventListener('click', function(e) {
                        e.preventDefault();

                        // AUTO-ADVANCE: If quiz is already completed, skip to next lesson
                        if (this.classList.contains('completed')) {

                             
                             // Find Next Content (ignoreLocks=true)
                             const nextContent = getNextContent(false, this, true);
                             if (nextContent) {
                                 showToast("Quiz already completed! Advancing...", "success");
                                 if (nextContent.type === 'lesson') {
                                     nextContent.element.click();
                                 } else if (nextContent.type === 'test') {
                                     fetchQuizQuestions(nextContent.sectionId);
                                 }
                                 return;
                             } else {
                                 // End of course or section? Just show results if they click it.
                                 // Or show toast "Section Completed"
                                 showToast("Quiz already completed. Section done!", "success");
                                 // Fall through to show modal so they can see results if they really want.
                             }
                        }

                        const sectionId = this.getAttribute('data-section-id');
                        fetchQuizQuestions(sectionId);
                    });
                });
            }

            function unlockAllArticles() {
                articleLinks.forEach(link => {
                    link.classList.remove('locked');
                    const lockIcon = link.querySelector('i.fal.fa-lock-alt');
                    if (lockIcon) {
                        lockIcon.remove();
                    }
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        window.open(link.getAttribute('href'), '_blank');
                    });
                });
            }

            // ============================================
            // TOP ACTION BUTTON (Next Lesson / Take Test) - FIXED VERSION
            // ============================================

            // Initialize top action button
            if (player && lessonActionBtn) {
                player.on('play', function() { // Plyr event
                    // Always hide button when video plays (fullscreen or not)
                    if(lessonActionBtn) lessonActionBtn.style.display = 'none';
                    // Hide timer if playing
                    const timerOverlay = document.getElementById('countdown-overlay');
                    if(timerOverlay) timerOverlay.style.display = 'none';
                    if (countdownInterval) clearInterval(countdownInterval);
                    // Reset cancellation flag when a new video starts playing
                    window.autoAdvanceCancelled = false;

                });

                player.on('ended', function() { // Plyr event
                    if (countdownInterval) clearInterval(countdownInterval);
                    
                    const nextContent = getNextContent(false, null, true); // ignoreLocks to find next physical item
                    let secondsLeft = 3;

                    if (nextContent) {
                         // Update Button Text Initially
                         updateTopActionButton();
                         
                         // Fix: Explicitly set text based on type
                         lessonActionBtn.textContent = nextContent.type === 'test' ? 'Take Quiz' : 'Next Lesson';
                         
                         lessonActionBtn.style.display = 'block';

                         // Show Center Timer
                         const timerOverlay = document.getElementById('countdown-overlay');
                         const timerNumber = document.getElementById('countdown-number');
                         const timerText = document.getElementById('countdown-text');
                         
                         timerOverlay.style.display = 'block';
                         timerNumber.textContent = secondsLeft;
                         timerText.textContent = nextContent.type === 'test' ? 'Quiz starting in' : 'Next Lesson in';

                         countdownInterval = setInterval(() => {
                             secondsLeft--;
                             if(secondsLeft > 0) {
                                 timerNumber.textContent = secondsLeft;
                             } else {
                                clearInterval(countdownInterval);
                                timerOverlay.style.display = 'none';
                                // Only auto-advance if user didn't cancel
                                // console.log('Timer reached 0. autoAdvanceCancelled:', window.autoAdvanceCancelled);
                                if (!window.autoAdvanceCancelled) {

                                    handleNextContent();
                                } else {

                                }
                             }
                         }, 1000);
                    } else {
                        // End of course or no next item
                        updateTopActionButton();
                    }
                });

                lessonLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        setTimeout(() => {
                            lessonActionBtn.style.display = 'none';
                        }, 50);
                    });
                });
            }

            function updateTopActionButton(timeLeft = null) {
                if (!lessonActionBtn) return;

                // For guests after first video
                if (!window.isAuthenticated || window.isAuthenticated === 'false') {
                    if (currentVideoIndex === 0) {
                        lessonActionBtn.textContent = 'Login to Continue';
                        lessonActionBtn.onclick = () => {
                            window.location.href =
                                '{{ route('login.form', ['redirect_to' => url()->current()]) }}';
                        };
                        lessonActionBtn.style.display = 'block';
                        return;
                    }
                }

                const nextContent = getNextContent();
                if (!nextContent) {
                    lessonActionBtn.style.display = 'none';
                    lessonActionBtn.onclick = null;
                    return;
                }

                if (nextContent.type === 'lesson') {
                    lessonActionBtn.textContent = 'Next Lesson';
                    lessonActionBtn.onclick = () => handleNextContent();
                } else if (nextContent.type === 'test') {
                    lessonActionBtn.textContent = 'Take Test';
                    lessonActionBtn.onclick = () => handleNextContent();
                } else if (nextContent.type === 'payment') {
                    lessonActionBtn.textContent = 'Buy Now';
                    lessonActionBtn.onclick = () => {
                        const paymentLink = document.querySelector('.price-list .cart-btn a.theme_btn');
                        if (paymentLink) {
                            window.location.href = paymentLink.getAttribute('href');
                        }
                    };
                }

                lessonActionBtn.style.display = 'block';
                // CSS handles fullscreen positioning automatically - no JS override needed
            }

            function getNextContent(skipTests = false, referenceElement = null, ignoreLocks = false) {
                const allLessons = Array.from(document.querySelectorAll('.lesson-link'));
                let currentIndex = -1;

                if (referenceElement) {
                     currentIndex = allLessons.indexOf(referenceElement);
                } else {
                    // Use currentLessonId instead of video URL matching
                    if (!currentLessonId) {

                        return null;
                    }
                    // Find current lesson by lesson ID
                    currentIndex = allLessons.findIndex(lesson =>
                        lesson.getAttribute('data-lesson-id') === currentLessonId.toString()
                    );
                }

                if (currentIndex === -1) {

                    return null;
                }




                // If asking for physical next item (ignore locks), skip complex search
                // and just return the next item in the list.
                if (ignoreLocks) {
                    const nextItem = allLessons[currentIndex + 1];
                    if (!nextItem) return null;

                    // Detect Type
                    if (nextItem.classList.contains('test-link')) {
                         return {
                             type: 'test',
                             element: nextItem,
                             sectionId: nextItem.getAttribute('data-section-id'),
                             title: nextItem.textContent.trim()
                         };
                    } else {
                        return {
                            type: 'lesson',
                            element: nextItem,
                            lessonId: nextItem.getAttribute('data-lesson-id'),
                            title: nextItem.textContent.trim()
                        };
                    }
                }

                // Check payment after first free lesson
                if (currentIndex === 0) {
                    const priceElement = document.querySelector('.price-list');
                    const hasPaid = !priceElement;

                    if (!hasPaid) {
                        return {
                            type: 'payment'
                        };
                    }
                }

                // Get the current lesson's <li> element
                const currentLessonAnchor = allLessons[currentIndex];
                const currentLi = currentLessonAnchor.closest('li');

                if (!currentLi) {

                    return null;
                }

                // Look for next content in the same section
                let cursor = currentLi.nextElementSibling;
                let nextLessonInSection = null;
                let testLinkInSection = null;

                while (cursor) {
                    // Stop if we hit a new section header
                    if (cursor.tagName === 'H6') break;

                    // Look for next unlocked lesson
                    if (!nextLessonInSection) {
                        const lessonA = cursor.querySelector('a.lesson-link');
                        if (lessonA && !lessonA.classList.contains('locked')) {
                            nextLessonInSection = lessonA;
                        }
                    }

                    // Look for test link
                    if (!testLinkInSection) {
                        const testA = cursor.querySelector('a.test-link');
                        if (testA && !testA.classList.contains('locked')) {
                            testLinkInSection = testA;
                        }
                    }

                    if (nextLessonInSection && testLinkInSection) break;
                    cursor = cursor.nextElementSibling;
                }

                // Return next lesson if found
                if (nextLessonInSection) {

                    return {
                        type: 'lesson',
                        element: nextLessonInSection,
                        lessonId: nextLessonInSection.getAttribute('data-lesson-id'),
                        title: nextLessonInSection.textContent.trim()
                    };
                }

                // Return test if found (end of section)
                if (!skipTests && testLinkInSection) {
                    const sectionId = testLinkInSection.getAttribute('data-section-id');

                    return {
                        type: 'test',
                        element: testLinkInSection,
                        sectionId: sectionId,
                        title: testLinkInSection.textContent.trim()
                    };
                }

                // Look backward for test (in case test comes before lessons in HTML)
                cursor = currentLi.previousElementSibling;
                while (cursor) {
                    if (cursor.tagName === 'H6') break;
                    const testA = cursor.querySelector('a.test-link');
                    if (testA && !testA.classList.contains('locked')) {
                        testLinkInSection = testA;
                        break;
                    }
                    cursor = cursor.previousElementSibling;
                }

                if (!skipTests && testLinkInSection) {
                    const sectionId = testLinkInSection.getAttribute('data-section-id');

                    return {
                        type: 'test',
                        element: testLinkInSection,
                        sectionId: sectionId,
                        title: testLinkInSection.textContent.trim()
                    };
                }

                // Look for next lesson (or test) across sections
                // currentIndex points to the current video lesson in allLessons array.
                // The allLessons array contains BOTH .lesson-link AND .test-link elements because tests have class "lesson-link".
                const nextItem = allLessons[currentIndex + 1];
                
                if (nextItem && !nextItem.classList.contains('locked')) {
                    // Check if it is a Test
                    if (nextItem.classList.contains('test-link')) {
                         const sectionId = nextItem.getAttribute('data-section-id');

                         return {
                             type: 'test',
                             element: nextItem,
                             sectionId: sectionId,
                             title: nextItem.textContent.trim()
                         };
                    } else {
                        // It is a standard Lesson
                        // console.log('Next item is a Lesson (next section logic):', nextItem.textContent.trim());
                        return {
                            type: 'lesson',
                            element: nextItem,
                            lessonId: nextItem.getAttribute('data-lesson-id'),
                            title: nextItem.textContent.trim()
                        };
                    }
                }

                return null;
            }

            async function handleNextContent() {

                
                // GUARD: Check if interaction is blocked
                if (window.isBlockingInteraction) {

                    return;
                }
                


                
                const nextContent = getNextContent();
                if (!nextContent) return;

                if (nextContent.type === 'payment') {
                    const paymentLink = document.querySelector('.price-list .cart-btn a.theme_btn');
                    if (paymentLink) {
                        window.location.href = paymentLink.getAttribute('href');
                    }
                    return;
                }

                if (nextContent.type === 'lesson') {
                    // Click the next lesson to trigger video load

                    nextContent.element.click();
                    lessonActionBtn.style.display = 'none';
                } else if (nextContent.type === 'test') {

                    fetchQuizQuestions(nextContent.sectionId);
                    lessonActionBtn.style.display = 'none';
                }
            }
            // ============================================
            // QUIZ SYSTEM (keeping your existing code)
            // ============================================

            let isFetchingQuiz = false;

            function fetchQuizQuestions(sectionId) {
                // GUARD: Check if interaction is blocked (Ghost Click Protection)
                if (window.isBlockingInteraction) {

                    return;
                }

                if (isFetchingQuiz) return;
                isFetchingQuiz = true;
                
                // Show busy cursor
                document.body.style.cursor = 'wait';

                $.ajax({
                    url: '{{ route('quiz.fetch') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        section_id: sectionId,
                        course_id: {{ $course->id }}
                    },
                    success: function(response) {
                        isFetchingQuiz = false;
                        document.body.style.cursor = 'default';
                        
                        // console.log('Quiz questions loaded:', response);
                        
                        if (!response || response.length === 0) {
                             alert("No questions found for this quiz.");
                             return;
                        }

                        quizData = response;
                        userAnswers = {}; 
                        currentQuestionIndex = 0;
                        
                        // NEW: Fetch previous answers before opening modal
                        fetchPreviousAnswers(sectionId);
                    },
                    error: function(error) {
                        isFetchingQuiz = false;
                        document.body.style.cursor = 'default';
                        // console.error('Error fetching quiz questions', error);
                        alert('An error occurred while fetching the quiz questions.');
                    }
                });
            }

            function fetchPreviousAnswers(sectionId) {
                if (!window.userId || window.userId === 'null') {
                    openQuizModal();
                    return;
                }

                $.ajax({
                    url: '{{ route('quiz.previousAnswers') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        section_id: sectionId,
                        user_id: window.userId
                    },
                    success: function(response) {
                        // Merge previous answers if they exist
                        if (response && Array.isArray(response)) {
                            response.forEach(function(item) {
                                userAnswers[item.question_id] = item.selected_option;
                            });
                        }
                        // console.log('Previous answers merged:', userAnswers);
                        openQuizModal();
                    },
                    error: function(error) {
                        // console.error('Error fetching previous answers', error);
                        // Open anyway even if fetch fails
                        openQuizModal();
                    },
                });
            }

            function openQuizModal() {
                // Remove ANY existing modal to prevent duplicates/zombies
                const existingModal = document.getElementById('quizModal');
                if (existingModal) {
                    existingModal.remove();
                }

                if (!quizData || quizData.length === 0) {
                     alert("No questions found.");
                     return;
                }
                
                const question = quizData[currentQuestionIndex];
                // console.log(`Opening modal for Q${currentQuestionIndex + 1}/${quizData.length}:`, question);
                
                const previousAnswer = userAnswers[question.id] || null;

                let modalHtml = `
                <div id="quizModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Plant Survival Quiz</h3>
                            <span class="close">&times;</span>
                        </div>
                        <div class="modal-contents d-flex justify-content-center">
                            <img src="{{ asset('assets/img/questions.png') }}" alt="question" />
                        </div>
                        <form id="quizForm">
                            <div id="question-container">
                                <div class="modal-body">
                                    <p>Question ${currentQuestionIndex + 1}: ${question.question}</p>
                                    <div class="options-container">
                                        ${[1, 2, 3, 4].map(option => `
                                            <label class="input-box option-label" for="q${question.id}_opt${option}">
                                                <input type="radio" 
                                                       id="q${question.id}_opt${option}"
                                                       name="question_${question.id}" 
                                                       value="${option}" 
                                                       ${previousAnswer == option ? "checked" : ""}>
                                                <span class="option-text">${question["option" + option]}</span>
                                            </label>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex align-items-center justify-content-end">
                                <button type="button" id="prevButton" 
                                    class="nav-button ${currentQuestionIndex === 0 ? 'disabled-btn' : 'prev-btn'}" 
                                    ${currentQuestionIndex === 0 ? 'disabled' : ''}>&lt;</button>
                                <button type="button" id="nextButton" 
                                    class="nav-button ${currentQuestionIndex === quizData.length - 1 ? 'submit-btn' : 'next-btn'}">
                                    ${currentQuestionIndex === quizData.length - 1 ? 'Submit' : '&gt;'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>`;

                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const modal = document.getElementById('quizModal');

                modal.querySelector('.close').onclick = () => {
                    modal.remove();
                };

                document.getElementById('prevButton').onclick = function() {

                    if (currentQuestionIndex > 0) {
                        saveUserAnswer();
                        currentQuestionIndex--;
                        modal.remove();
                        openQuizModal();
                    }
                };

                document.getElementById('nextButton').onclick = function() {
                    // Prevent double clicks
                    if (this.disabled) return;
                    this.disabled = true;
                    this.innerHTML = 'Converting...'; // Optional visual feedback
                    


                    
                    saveUserAnswer();
                    
                    // Small delay to allow UI to update/prevent race, though not strictly necessary if disabled
                    setTimeout(() => {
                        if (currentQuestionIndex < quizData.length - 1) {
                            currentQuestionIndex++;

                            modal.remove();
                            openQuizModal();
                        } else {

                            modal.remove();
                            showQuizResults();
                        }
                    }, 50);
                };

                modal.style.display = 'block';
            }

            function saveUserAnswer() {
                const question = quizData[currentQuestionIndex];
                const selectedOption = document.querySelector(`input[name="question_${question.id}"]:checked`);
                if (selectedOption) {
                    userAnswers[question.id] = selectedOption.value;
                } else {
                    userAnswers[question.id] = null;
                }
            }

            const icons = {
                correct: "{{ asset('assets/img/icon/correct.png') }}",
                incorrect: "{{ asset('assets/img/icon/worng.png') }}",
                skipped: "{{ asset('assets/img/icon/skiped.png') }}"
            };

            function showQuizResults() {
                correctAnswers = 0;
                incorrectAnswers = 0;
                skippedQuestions = 0;

                quizData.forEach(question => {
                    if (userAnswers[question.id] === null) {
                        skippedQuestions++;
                    } else if (userAnswers[question.id] == question.correct_option) {
                        saveQuizProgress(question.id, 1, userAnswers[question.id]);
                        correctAnswers++;
                    } else {
                        saveQuizProgress(question.id, 0, userAnswers[question.id]);
                        incorrectAnswers++;
                    }
                });
                
                // NEW: Trigger global completion check to update certificate modal status
                // Small delay to ensure DB updates from saveQuizProgress have processed
                setTimeout(() => {

                    // This function exists in the previous code block (checkForCompletion/checkPaymentStatus logic)
                    // If checkPaymentStatus exists, we might need to refresh that or similar.
                    // But specifically for certificate, we want to refresh the modal state if it's open or about to open.
                    // Best way is to just call the completion check endpoint if we have a function for it.
                    // We don't have a direct global function exposed, but we can re-trigger the check loop.
                }, 1000);

                let totalQuestions = correctAnswers + incorrectAnswers + skippedQuestions;
                let scorePercentage = totalQuestions > 0 ? (correctAnswers / totalQuestions) * 100 : 0;

                let resultTitle;
                if (scorePercentage >= 90) {
                    resultTitle = "Outstanding!";
                } else if (scorePercentage >= 75) {
                    resultTitle = "Great job!";
                } else if (scorePercentage >= 50) {
                    resultTitle = "Good effort!";
                } else {
                    resultTitle = "Keep trying!";
                }

                let questionListHtml = quizData.map(question => {
                    let iconSrc = "";
                    let iconAlt = "";
                    if (userAnswers[question.id] === null) {
                        iconSrc = icons.skipped;
                        iconAlt = "skipped";
                    } else if (userAnswers[question.id] == question.correct_option) {
                        iconSrc = icons.correct;
                        iconAlt = "Correct";
                    } else {
                        iconSrc = icons.incorrect;
                        iconAlt = "Incorrect";
                    }
                    return `
                    <div class="question-item d-flex align-items-center">
                        <img src=${iconSrc} alt="${iconAlt}" class="answer-icon" />
                        <p>${question.question}</p>
                    </div>`;
                }).join('');

                let modalHtml = `
                <div id="quizResultsModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Plant Survival Quiz</h3>
                            <span class="close">&times;</span>
                        </div>
                        <div class="modal-body">
                            <div class="row p-3 progress-row">
                                <div class="col-lg-auto">
                                    <div class="progress-containers">
                                        <svg class="progress-ring" width="120" height="120">
                                            <circle class="progress-ring-bg" cx="60" cy="60" r="50"></circle>
                                            <circle class="progress-ring-fill" cx="60" cy="60" r="50"
                                                stroke-dasharray="314" stroke-dashoffset="314"></circle>
                                            <text x="50%" y="50%" text-anchor="middle" dy=".3em" class="progress-text">${Math.round(scorePercentage)}%</text>
                                        </svg>
                                    </div>
                                </div>
                                <div class="col-lg d-flex align-items-center justify-content-start">
                                    <div class="quez-summery">
                                        <h3>${resultTitle}</h3>
                                        <div class="result-details d-flex align-items-center" style="gap:1rem">
                                            <div class="correct d-flex align-items-center" style="gap:0.5rem">
                                                <img src="{{ asset('assets/img/icon/correct.png') }}" alt="correct" />
                                                <span>${correctAnswers} Correct</span>
                                            </div>
                                            <div class="worng d-flex align-items-center" style="gap:0.5rem">
                                                <img src="{{ asset('assets/img/icon/worng.png') }}" alt="worng" />
                                                <span>${incorrectAnswers} Incorrect</span>
                                            </div>
                                            <div class="skipped d-flex align-items-center" style="gap:0.5rem">
                                                <img src="{{ asset('assets/img/icon/skiped.png') }}" alt="skipped" />
                                                <span>${skippedQuestions} Skipped</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="question-list row">
                                <div class="col">
                                    ${questionListHtml}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="retakeButton">Retry</button>
                            <button type="button" id="closeResultsButton">Continue</button>
                        </div>
                    </div>
                </div>`;

                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const modal = document.getElementById('quizResultsModal');

                modal.querySelector('.close').onclick = () => {
                    modal.remove();
                };

                document.getElementById('closeResultsButton').onclick = () => {
                    modal.remove();

                    // STRICT COMPLETION CHECK: Only mark complete if 100% score
                    const isPassed = Math.round(scorePercentage) === 100;

                    if (quizData && quizData.length > 0) {
                         const currentQuizLink = document.querySelector(`.test-link[data-section-id="${quizData[0].section_id}"]`);
                         if (currentQuizLink) {
                             if (isPassed) {
                                 // 1. Mark Quiz as Completed Visually
                                 updateLessonIconToCompleted(currentQuizLink);
                                 
                                 // 2. Unlock Next Content (Only if passed)
                                 setTimeout(() => {
                                     // ignoreLocks=true to ensure we find the next item
                                     const nextContent = getNextContent(false, currentQuizLink, true);
                                     if (nextContent) {

                                         
                                         // UNLOCK IT
                                         unlockLesson(nextContent.element, true);

                                         if (nextContent.type === 'lesson') {
                                             nextContent.element.click();
                                         } else if (nextContent.type === 'test') {
                                              fetchQuizQuestions(nextContent.sectionId);
                                         }
                                     } else {
                                         showToast("Section completed!", "success");
                                     }
                                 }, 300);
                             } else {
                                 // Failed: Show feedback
                                 showToast("Retake Quiz and get 100% score to unlock the next lesson.", "error");
                                 // Optionally re-open quiz? No, let them choose to retry.
                             }
                         }
                    }
                };

                document.getElementById('retakeButton').onclick = () => {
                    modal.remove();
                    fetchQuizQuestions(quizData[0].section_id);
                };

                modal.style.display = 'block';
                updateCircularProgress(scorePercentage);
            }

            function updateCircularProgress(scorePercentage) {
                const circle = document.querySelector('.progress-ring-fill');
                const circumference = 2 * Math.PI * 50;
                const offset = circumference - (scorePercentage / 100) * circumference;
                circle.style.strokeDashoffset = offset;
            }

            function saveQuizProgress(questionId, isCorrect, selectedOption) {
                if (!window.userId || window.userId === 'null') return;

                $.ajax({
                    url: '{{ route('quiz.saveProgress') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: window.userId,
                        question_id: questionId,
                        is_correct: isCorrect ? 1 : 0,
                        selected_option: selectedOption
                    },
                    success: function(response) {

                    },
                    error: function(error) {
                        console.error('Error saving quiz progress', error);
                    }
                });
            }

            // ============================================
            // CERTIFICATE DOWNLOAD
            // ============================================

            function showCenterMessage() {
                document.getElementById('centerMessage').style.display = 'flex';
            }

            function checkForCompletion() {
                if (!window.userId || window.userId === 'null') {
                    showCertificateModal({
                        completed: false,
                        message: 'Please log in to view your progress and download certificate.',
                        showLogin: true
                    });
                    return;
                }

                // Check if user has paid
                const hasPaid = {{ auth()->check() && $course->userHasPaid(auth()->user()) ? 'true' : 'false' }};
                if (!hasPaid) {
                    showCertificateModal({
                        unpaid: true,
                        message: 'Buy course and complete to download certificate'
                    });
                    return;
                }

                // Show modal immediately with loading state
                showCertificateModal({ loading: true });

                // Fetch progress data - optimized to fetch all data in parallel
                Promise.allSettled([
                    $.ajax({ 
                        url: '{{ route('lessons.progress') }}', 
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            course_id: {{ $course->id }},
                            user_id: window.userId
                        }
                    }),
                    $.ajax({
                        url: '{{ route('certificate.checkCompletion') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            user_id: window.userId,
                            course_id: {{ $course->id }}
                        }
                    }),
                    fetch('{{ route('review.check', $course->id) }}').then(res => res.json()).catch(() => ({ completed: false, required: false }))
                ]).then((results) => {
                    const progressResult = results[0];
                    const completionResult = results[1];
                    const reviewResult = results[2];
                    
                    const progressResponse = progressResult.status === 'fulfilled' ? progressResult.value : {};
                    const completionResponse = completionResult.status === 'fulfilled' ? completionResult.value : {};
                    const reviewData = reviewResult.status === 'fulfilled' ? reviewResult.value : { completed: false, required: false };
                    
                    // Use detailed stats from API
                    const lessonStats = completionResponse.lesson_stats || {};
                    const quizStats = completionResponse.quiz_stats || {};
                    const reviewStats = completionResponse.review_stats || {};
                    
                    const totalLessons = parseInt(lessonStats.total_count) || parseInt(progressResponse?.total_lessons) || 0;
                    const watchedLessons = parseInt(lessonStats.completed_count) || parseInt(progressResponse?.watched_lessons) || 0;
                    
                    const totalTests = parseInt(quizStats.total_count) || document.querySelectorAll('.test-link').length || 0;
                    const completedTests = parseInt(quizStats.completed_count) || 0;
                    
                    const totalReviews = parseInt(reviewStats.total_count) || 0;
                    const completedReviews = parseInt(reviewStats.completed_count) || 0;
                    const reviewRequired = reviewStats.required !== false;
                    const reviewCompleted = reviewStats.completed === true || !reviewRequired;
                    
                    // Course is complete only if all required items are done
                    const isCompleted = completionResponse.completed === true && (!reviewRequired || reviewCompleted);

                    showCertificateModal({
                        loading: false,
                        completed: isCompleted,
                        totalLessons,
                        watchedLessons,
                        totalTests,
                        completedTests,
                        totalReviews,
                        completedReviews,
                        reviewRequired,
                        reviewCompleted,
                        lessonsPercent: totalLessons > 0 ? Math.round((watchedLessons / totalLessons) * 100) : 0,
                        testsPercent: totalTests > 0 ? Math.round((completedTests / totalTests) * 100) : 0,
                        reviewPercent: totalReviews > 0 ? Math.round((completedReviews / totalReviews) * 100) : 0
                    });
                }).catch(err => {
                    console.error('Error fetching certificate details:', err);
                    showCertificateModal({ 
                        loading: false, 
                        error: true,
                        message: 'Could not load progress details. Please try again.'
                    });
                });
            }

            function showCertificateModal(data) {
                const modal = document.getElementById('certificateProgressModal');
                const subtitle = document.getElementById('certModalSubtitle');
                const details = document.getElementById('certProgressDetails');
                const actions = document.getElementById('certModalActions');
                
                if (!modal) return;

                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Disable background scroll

                if (data.loading) {
                    subtitle.textContent = 'Checking your progress...';
                    details.innerHTML = `
                        <div style="display: flex; justify-content: center; padding: 40px;">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; border-width: 4px; border-color: #ffa03f transparent #ffa03f transparent; border-radius: 50%; animation: spin 1s linear infinite;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
                    `;
                    actions.innerHTML = '';
                    return;
                }

                if (data.error) {
                    subtitle.textContent = 'Something went wrong';
                    details.innerHTML = `<p style="color: #ef4444; text-align: center;">${data.message}</p>`;
                    actions.innerHTML = `
                        <button onclick="document.body.style.overflow='auto'; document.getElementById('certificateProgressModal').style.display='none'" style="background: #e0e0e0; color: #333; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer;">Close</button>
                    `;
                    return;
                }

                if (data.unpaid) {
                    subtitle.textContent = 'Course Not Purchased';
                    details.innerHTML = `<p style="color: #666; text-align: center; font-size: 16px;">${data.message}</p>`;
                    actions.innerHTML = `
                        <button onclick="document.body.style.overflow='auto'; document.querySelector('.price-list').scrollIntoView({behavior: 'smooth'}); document.getElementById('certificateProgressModal').style.display='none';" style="background: #ffa03f; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-size: 16px;">Buy Now</button>
                         <button onclick="document.body.style.overflow='auto'; document.getElementById('certificateProgressModal').style.display='none'" style="background: #e0e0e0; color: #333; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-size: 16px;">Close</button>
                    `;
                    return;
                }
                
                if (data.showLogin) {
                    subtitle.textContent = data.message;
                    details.innerHTML = '';
                    actions.innerHTML = `
                        <button onclick="window.location.href='{{ route('login.form') }}'" style="background: #ffa03f; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-size: 16px;">Login</button>
                        <button onclick="document.body.style.overflow='auto'; document.getElementById('certificateProgressModal').style.display='none'" style="background: #e0e0e0; color: #333; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-size: 16px;">Close</button>
                    `;
                } else if (data.completed) {
                    subtitle.innerHTML = '<i class="fas fa-trophy" style="color: #f59e0b; margin-right: 8px;"></i> Congratulations! Course completed!';
                    details.innerHTML = `
                        <div style="background: #f0fdf4; border: 2px solid #86efac; border-radius: 12px; padding: 20px; margin-bottom: 16px; text-align: center;">
                            <div style="font-size: 48px; margin-bottom: 8px; color: #166534;"><i class="fas fa-award"></i></div>
                            <p style="color: #166534; font-weight: 600;">You've completed all requirements!</p>
                        </div>
                        ${createProgressBar('Lessons', data.watchedLessons, data.totalLessons, data.lessonsPercent)}
                        ${createProgressBar('Tests', data.completedTests, data.totalTests, data.testsPercent)}
                        ${data.reviewRequired ? createProgressBar('Review', data.completedReviews, data.totalReviews, data.reviewPercent) : ''}
                    `;
                    actions.innerHTML = `
                        <button onclick="downloadCertificate()" style="background: #10b981; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600;">Download Certificate</button>
                        <button onclick="document.body.style.overflow='auto'; document.getElementById('certificateProgressModal').style.display='none'" style="background: #e0e0e0; color: #333; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-size: 16px;">Close</button>
                    `;
                } else {
                    const totalItems = data.totalLessons + data.totalTests + (data.reviewRequired ? data.totalReviews : 0);
                    const completedItems = data.watchedLessons + data.completedTests + (data.reviewRequired ? data.completedReviews : 0);
                    const overallPercent = totalItems > 0 ? Math.round((completedItems / totalItems) * 100) : 0;
                    subtitle.textContent = `You're ${overallPercent}% complete!`;
                    details.innerHTML = `
                        <div style="background: #fef3c7; border: 2px solid #fbbf24; border-radius: 12px; padding: 20px; margin-bottom: 16px; text-align: center;">
                            <p style="color: #92400e; font-weight: 600;">Complete all lessons, tests${data.reviewRequired ? ' and review' : ''} to unlock your certificate</p>
                        </div>
                        ${createProgressBar('Lessons', data.watchedLessons, data.totalLessons, data.lessonsPercent)}
                        ${createProgressBar('Tests', data.completedTests, data.totalTests, data.testsPercent)}
                        ${data.reviewRequired ? createProgressBar('Review', data.completedReviews, data.totalReviews, data.reviewPercent) : ''}
                    `;
                    actions.innerHTML = `
                        <button onclick="document.body.style.overflow='auto'; document.getElementById('certificateProgressModal').style.display='none'" style="background: #ffa03f; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-size: 16px;">Continue Learning</button>
                    `;
                }
            }
            
            // Global Click Handler for Close Button
            const closeCertModalBtn = document.getElementById('closeCertModal');
            if (closeCertModalBtn) {
                closeCertModalBtn.addEventListener('click', function() {
                    document.body.style.overflow = 'auto'; 
                    document.getElementById('certificateProgressModal').style.display = 'none';
                });
            }

            function createProgressBar(label, completed, total, percent) {
                const isComplete = completed >= total && total > 0;
                return `
                    <div style="margin-bottom: 16px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-weight: 600; color: #1b212f;">${label}</span>
                            <span style="display: flex; align-items: center; color: ${isComplete ? '#10b981' : '#4f5966'}; font-weight: ${isComplete ? '600' : '400'};">
                                ${completed}/${total} ${isComplete ? '<i class="fas fa-check-circle" style="margin-left: 4px;"></i>' : ''}
                            </span>
                        </div>
                        <div style="background: #e5e7eb; border-radius: 8px; height: 12px; overflow: hidden;">
                            <div style="background: ${isComplete ? '#10b981' : '#ffa03f'}; width: ${percent}%; height: 100%; transition: width 0.3s ease;"></div>
                        </div>
                    </div>
                `;
            }

            // Function to update review link status dynamically
            function updateReviewLinkStatus() {
                if (!window.userId || window.userId === 'null') return;
                
                const reviewLink = document.querySelector('.review-link');
                if (!reviewLink) return;
                
                // Check review completion status
                fetch('{{ route('review.check', $course->id) }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.completed) {
                            reviewLink.classList.remove('unlocked');
                            reviewLink.classList.add('completed');
                        } else {
                            reviewLink.classList.remove('completed');
                            reviewLink.classList.add('unlocked');
                        }
                    })
                    .catch(err => {
                        console.error('Error checking review status:', err);
                    });
            }

            // Handle review link click (like test links)
            document.addEventListener('click', function(e) {
                const reviewLink = e.target.closest('.review-link');
                if (reviewLink) {
                    e.preventDefault();
                    
                    // Payment check
                    if (window.isAuthenticated === 'true' && !window.userHasPaid) {
                        showToast("Please enroll in the course to access review.", "lock");
                        return;
                    }
                    
                    // Guest check
                    if (window.isAuthenticated !== 'true') {
                        if (confirm('Please login to access review. Redirect to login page?')) {
                            window.location.href = '{{ route('login.form', ['redirect_to' => url()->current()]) }}';
                        }
                        return;
                    }
                    
                    // Show review modal
                    showReviewModal();
                }
            });

            // Show review modal
            function showReviewModal() {
                const modal = document.getElementById('reviewModal');
                if (!modal) {
                    console.error('Review modal not found');
                    return;
                }
                
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                
                // Show loading state
                const container = document.getElementById('reviewQuestionsContainer');
                if (container) {
                    container.innerHTML = '<div style="text-align: center; padding: 40px;"><div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; border-width: 4px; border-color: #ffa03f transparent #ffa03f transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div><p style="margin-top: 15px; color: #666;">Loading review questions...</p></div>';
                }
                
                // Fetch review questions with existing answers
                fetch('{{ route('review.questions', $course->id) }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.questions.length > 0) {
                            renderReviewQuestions(data.questions, data.status);
                        } else {
                            // No questions, allow certificate download
                            modal.style.display = 'none';
                            document.body.style.overflow = 'auto';
                            downloadCertificate();
                        }
                    })
                    .catch(err => {
                        console.error('Error fetching review questions:', err);
                        if (container) {
                            container.innerHTML = '<div style="text-align: center; padding: 20px; color: #ef4444;">Error loading review questions. Please try again.</div>';
                        }
                    });
            }

            // Render review questions with status
            function renderReviewQuestions(questions, status) {
                const container = document.getElementById('reviewQuestionsContainer');
                if (!container) return;
                
                container.innerHTML = '';
                
                // Simple compact status indicator
                if (status) {
                    const statusDiv = document.createElement('div');
                    statusDiv.style.cssText = 'background: #f8f9fa; border-radius: 6px; padding: 8px 12px; margin-bottom: 15px; font-size: 12px; color: #666; text-align: center;';
                    statusDiv.innerHTML = `<span>Progress: <strong style="color: ${status.completed ? '#10b981' : '#0ea5e9'};">${status.answered}/${status.total}</strong></span>`;
                    container.appendChild(statusDiv);
                }
                
                questions.forEach((question, index) => {
                    const questionDiv = document.createElement('div');
                    questionDiv.className = 'review-question';
                    
                    // Check if question is answered
                    const isAnswered = question.is_answered || false;
                    const savedOptions = question.saved_options || [];
                    
                    // Simple answered indicator
                    const statusIcon = isAnswered ? '<span style="color: #10b981; margin-left: 6px; font-size: 14px;">✓</span>' : '';
                    
                    questionDiv.innerHTML = `
                        <div style="margin-bottom: 10px;">
                            <span style="color: #1b212f; font-size: 14px; font-weight: 500;">${index + 1}. ${question.question}</span>
                            ${statusIcon}
                        </div>
                        <div class="review-options">
                            ${question.options.map((option, optIndex) => {
                                const isChecked = savedOptions.includes(optIndex);
                                return `
                                    <label class="review-option-label ${isChecked ? 'answered' : ''}">
                                        <input type="checkbox" name="question_${question.id}" value="${optIndex}" class="review-checkbox" ${isChecked ? 'checked' : ''}>
                                        <span>${option}</span>
                                    </label>
                                `;
                            }).join('')}
                        </div>
                    `;
                    container.appendChild(questionDiv);
                });
                
                // Add submit button
                const submitBtn = document.createElement('button');
                submitBtn.className = 'btn btn-primary';
                submitBtn.style.cssText = 'margin-top: 15px; padding: 10px 20px; font-size: 14px; width: 100%; background: #ffa03f; border: none; border-radius: 6px; color: white; font-weight: 600; cursor: pointer; transition: background 0.2s;';
                submitBtn.textContent = status && status.completed ? 'Update' : 'Submit';
                submitBtn.onmouseover = function() { this.style.background = '#e68a2e'; };
                submitBtn.onmouseout = function() { this.style.background = '#ffa03f'; };
                submitBtn.onclick = function() {
                    submitReview(questions);
                };
                container.appendChild(submitBtn);
            }

            // Submit review answers
            function submitReview(questions) {
                const answers = [];
                let allAnswered = true;
                
                questions.forEach(question => {
                    const checkboxes = document.querySelectorAll(`input[name="question_${question.id}"]:checked`);
                    if (checkboxes.length === 0) {
                        allAnswered = false;
                        return;
                    }
                    
                    const selectedOptions = Array.from(checkboxes).map(cb => parseInt(cb.value));
                    answers.push({
                        question_id: question.id,
                        selected_options: selectedOptions
                    });
                });
                
                if (!allAnswered) {
                    alert('Please answer all questions before submitting.');
                    return;
                }
                
                const submitBtn = document.querySelector('#reviewQuestionsContainer button');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
                
                // Log the data being sent for debugging

                
                fetch('{{ route('review.submit', $course->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ answers })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => Promise.reject(err));
                    }
                    return res.json();
                })
                .then(data => {

                    
                    if (data.success) {
                        // Close review modal
                        document.getElementById('reviewModal').style.display = 'none';
                        document.body.style.overflow = 'auto';
                        
                        // Update review link status dynamically
                        updateReviewLinkStatus();
                        
                        // Show success message
                        triggerCongratulations("Congratulations!", "Thank you for your feedback! Your review has been saved. Your certificate is now available for download.");
                        
                        // Refresh certificate modal to show updated progress
                        checkForCompletion();
                    } else {
                        const errorMsg = data.error || 'Error submitting review. Please try again.';
                        alert(errorMsg);
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit Review';
                    }
                })
                .catch(err => {
                    console.error('Error submitting review:', err);
                    const errorMsg = err.error || err.message || 'Error submitting review. Please try again.';
                    alert(errorMsg);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit Review';
                });
            }

            window.downloadCertificate = function() {
                // Check review completion before downloading
                fetch('{{ route('review.check', $course->id) }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.completed || !data.required) {
                            // Review completed or not required, proceed with download
                            window.location.href = '{{ route('certificate.download', $course->id) }}';
                        } else {
                            // Review required but not completed, show review modal
                            showReviewModal();
                        }
                    })
                    .catch(err => {
                        console.error('Error checking review:', err);
                        // If error, proceed with download
                        window.location.href = '{{ route('certificate.download', $course->id) }}';
                    });
            };

            if (downloadCertificateButton) {
                downloadCertificateButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    checkForCompletion();
                });
            }

            // ============================================
            // CLEANUP
            // ============================================

            window.addEventListener('beforeunload', function() {
                if (urlRefreshInterval) {
                    clearInterval(urlRefreshInterval);
                }
                videoSource.src = '';
            });

            // Pause video when tab is hidden
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    if (!player.paused) {
                        player.pause();
                        // console.log('Video paused (tab hidden)');
                    }
                }
            });

            // console.log('Video protection setup complete');

            // Initialize data
            fetchUnlockedLessons();
            fetchWatchedVideos();
            checkPaymentStatus();
            updateReviewLinkStatus(); // Check review status dynamically

            // Helper: Visual Countdown
            function startCountdown(seconds, message, callback) {
                const overlay = document.getElementById('countdownOverlay');
                const msgEl = document.getElementById('countdownMessage');
                const timerEl = document.getElementById('countdownTimer');
                const cancelBtn = document.getElementById('cancelCountdown');
                
                if(!overlay) return;
                
                // Reset state
                if(countdownInterval) clearInterval(countdownInterval);
                
                msgEl.textContent = message;
                timerEl.textContent = seconds;
                overlay.style.display = 'flex';
                
                let remaining = seconds;
                
                // Ensure cancel button works
                cancelBtn.onclick = () => {
                    clearInterval(countdownInterval);
                    overlay.style.display = 'none';
                };
                
                countdownInterval = setInterval(() => {
                    remaining--;
                    timerEl.textContent = remaining;
                    
                    if (remaining <= 0) {
                        clearInterval(countdownInterval);
                        overlay.style.display = 'none';
                        if(callback) callback();
                    }
                }, 1000);
            }
        });
    </script>

    <!-- Review Modal -->
    <div id="reviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 100000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 8px; padding: 20px; max-width: 550px; width: 90%; max-height: 70vh; overflow-y: auto; position: relative; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
            <button onclick="document.getElementById('reviewModal').style.display='none'; document.body.style.overflow='auto';" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 22px; cursor: pointer; color: #999; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s;" onmouseover="this.style.background='#f0f0f0'; this.style.color='#333';" onmouseout="this.style.background='none'; this.style.color='#999';">&times;</button>
            <h3 style="margin-bottom: 8px; color: #1b212f; font-size: 18px; font-weight: 600;">Course Review</h3>
            <p style="color: #666; margin-bottom: 15px; font-size: 13px;">Share your feedback</p>
            <div id="reviewQuestionsContainer"></div>
        </div>
    </div>

    <!-- Congratulations Popper Overlay (Replaces separate box) -->
    <div id="congratulationsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 200000; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
        <div style="text-align: center; position: relative; animation: popIn 0.6s cubic-bezier(0.23, 1, 0.32, 1); width: 100%; max-width: 700px;">
            <div style="margin-bottom: 30px;">
                <div class="congrats-bounce" style="width: 140px; height: 140px; background: rgba(255,160,63,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 0 50px rgba(255,160,63,0.3);">
                    <i class="fas fa-trophy" style="font-size: 80px; color: #ffa03f; filter: drop-shadow(0 0 10px rgba(255,160,63,0.5));"></i>
                </div>
            </div>
            <h2 id="congratsTitle" style="color: #ffffff; font-weight: 800; font-size: 52px; margin-bottom: 20px; font-family: 'Inter', sans-serif; text-shadow: 0 10px 30px rgba(0,0,0,0.5); letter-spacing: -1px;">Congratulations!</h2>
            <p id="congratsMessage" style="color: #e9ecef; font-size: 24px; line-height: 1.5; margin-bottom: 45px; font-family: 'Inter', sans-serif; padding: 0 40px; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">You have successfully completed the course requirements. Your hard work has paid off!</p>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 20px;">
                <button onclick="hideCongratulations(); checkForCompletion();" style="background: #ffa03f; color: white; border: none; padding: 20px 50px; border-radius: 50px; font-weight: 800; font-size: 22px; cursor: pointer; transition: all 0.3s; box-shadow: 0 10px 30px rgba(255,160,63,0.4); font-family: 'Inter', sans-serif; text-transform: uppercase; letter-spacing: 1px;" onmouseover="this.style.transform='scale(1.08)'; this.style.boxShadow='0 15px 40px rgba(255,160,63,0.5)';" onmouseout="this.style.transform='scale(1)';">Claim Your Certificate</button>
                <button onclick="hideCongratulations()" style="background: transparent; color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.2); padding: 12px 35px; border-radius: 50px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif;" onmouseover="this.style.color='white'; this.style.borderColor='white';">Close</button>
            </div>
        </div>
    </div>

    <style>
        @keyframes popIn {
            0% { transform: scale(0.85) translateY(40px); opacity: 0; filter: blur(10px); }
            100% { transform: scale(1) translateY(0); opacity: 1; filter: blur(0); }
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
        .congrats-bounce {
            animation: bounce 2s infinite;
        }
    </style>

    <!-- Certificate Preview Modal -->
    <div class="modal fade" id="certificatePreviewModal" tabindex="-1" aria-hidden="true" style="z-index: 100000;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: transparent; border: none;">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 10; background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 10px; filter: invert(1);"></button>
                    <img src="{{ asset('assets/img/certificate_background.png') }}" class="img-fluid rounded shadow-lg" alt="Certificate Preview">
                    
                    <!-- Overlay for Actions -->
                    <div class="mt-3 p-4 bg-white rounded shadow text-center">
                        @guest
                             <h4 class="mb-3 text-dark">Login to Unlock Certificate</h4>
                             <a href="{{ route('login') }}" class="theme_btn">Login to Continue</a>
                        @else
                            @if(!$hasPaid)
                                <h4 class="mb-3 text-dark">Enroll to Get Certified</h4>
                                <a href="{{ route('payment.show', $course->id) }}" class="theme_btn">Enroll Now</a>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>


@if(!$hasPaid)
<!-- Sticky Enroll Bar (Mobile Only) -->
<div class="sticky-enroll-bar d-lg-none">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-6">
                <span class="sticky-price-label">Complete Course</span>
                <span class="sticky-price">{{ convertPrice($course->price, true, $course->price_usd) }}</span>
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('payment.show', $course->id) }}" class="sticky-enroll-btn">Enroll Now</a>
            </div>
        </div>
    </div>
</div>

<style>
    .sticky-enroll-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
        padding: 15px 0;
        box-shadow: 0 -4px 10px rgba(0,0,0,0.1);
        z-index: 9999;
        border-top: 1px solid #eee;
    }
    .sticky-price-label {
        display: block;
        font-size: 12px;
        color: #666;
        margin-bottom: -2px;
    }
    .sticky-price {
        font-size: 20px;
        font-weight: 700;
        color: #DA4E44;
    }
    .sticky-enroll-btn {
        background: #ffa03f;
        color: #fff;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(255, 160, 63, 0.4);
    }
    /* Add padding to body/footer on mobile to prevent overlap */
    @media (max-width: 991px) {
        body { padding-bottom: 80px; }
    }
</style>
@endif
<style>
    @media (max-width: 768px) {
        #scrollUp {
            display: none !important;
        }
    }
</style>
</body>

@include('course.modal_offer_claimed')

<style>
    .review-question {
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e5e7eb;
    }
    .review-question:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .review-options {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .review-option-label {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
    }
    .review-option-label:hover {
        border-color: #ffa03f;
        background: #fff7ed;
    }
    .review-option-label input[type="checkbox"] {
        margin-right: 10px;
        width: 18px;
        height: 18px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .review-option-label input[type="checkbox"]:checked + span {
        font-weight: 500;
        color: #ffa03f;
    }
    .review-option-label span {
        flex: 1;
        color: #1b212f;
        font-size: 13px;
        line-height: 1.4;
    }
    .review-option-label.answered {
        background: #f0fdf4;
        border-color: #86efac;
    }
    .review-option-label.answered input[type="checkbox"]:checked + span {
        color: #166534;
    }
    #reviewModal div::-webkit-scrollbar {
        width: 6px;
    }
    #reviewModal div::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    #reviewModal div::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }
    #reviewModal div::-webkit-scrollbar-thumb:hover {
        background: #999;
    }
</style>
</html>
