<style>
    @media (min-width: 768px) {
        .footer-social a {
            margin-right: 15px !important;
        }

        .footer-social a:last-child {
            margin-right: 0 !important;
        }
    }
</style>
<!--footer-area start-->
<footer class="footer-area pt-70 pb-40">
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
                    <p>Little Farmers Academy provides fun and educational farming courses for kids, teaching them how
                        to grow plants and understand the importance of nature.</p>
                    <div class="social-media footer__social mt-30 text-center text-md-start">
                        <!-- <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.twitter.com"><i class="fab fa-twitter"></i></a> -->
                        <a href="https://www.linkedin.com/in/we-little-farmer-4bbb18380"><i
                                class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/welittlefarmer/?igsh=ODB0eHE5eXBsajF3#"><i
                                class="fab fa-instagram"></i></a>
                    </div>
                    <!-- App Download Icons -->
                    <!--<div-->
                    <!--    class="app-download mt-30 d-flex align-items-center gap-2 justify-content-center justify-content-md-start">-->
                    <!--    <a href="https://play.google.com/store/games?hl=en"><img-->
                    <!--            src="{{ asset('assets/img/google-play-badge.png') }}" alt="Download on Google Play"-->
                    <!--            style="width: 150px;"></a>-->
                    <!--    <a href="https://www.apple.com/in/app-store/"><img-->
                    <!--            src="{{ asset('assets/img/app-store-badge.png') }}" alt="Download on the App Store"-->
                    <!--            style="width: 150px;"></a>-->
                    <!--</div>-->
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
                        <br>
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
                        <h5>© 2024 <a href="{{ route('home') }}">Little Farmers Academy</a>. All Rights Reserved.</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-area end -->

<!-- JS here -->
<!-- JS Scripts -->
<script src="{{ asset('assets/js/vendor/modernizr-3.5.0.min.js') }}" defer></script>
<script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}" defer></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('assets/js/owl.carousel.min.js') }}" defer></script>
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

<!-- Responsive CSS -->
<style>
    @media (max-width: 768px) {
        .footer__widget {
            margin-bottom: 20px;
        }

        .widget-title {
            font-size: 18px;
        }

        .fot-list {
            padding-left: 0;
        }

        .fot-list li {
            margin-bottom: 10px;
        }

        .footer__social a {
            margin-right: 10px;
        }

        .footer__contact ul {
            padding-left: 0;
        }

        .footer__contact ul li {
            margin-bottom: 10px;
        }

        .copyright h5 {
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .row>[class*="col-"] {
            margin-bottom: 20px;
        }
    }
</style>

<!-- Floating Mobile Course Menu Button -->
<div class="mobile-course-fab d-lg-none">
    <a href="{{ route('courses.index') }}" class="course-fab-btn">
        <i class="fas fa-book-open"></i>
        <span>Courses</span>
    </a>
</div>

<style>
    .mobile-course-fab {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 9998; /* Below WhatsApp (10000) */
    }
    .course-fab-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #1B212F;
        color: #fff !important;
        padding: 12px 20px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        transition: transform 0.2s;
        font-weight: 600;
    }
    .course-fab-btn:active {
        transform: scale(0.95);
    }
    .course-fab-btn i {
        color: #ffa03f !important;
        font-size: 18px;
    }
    /* Ensure it doesn't overlap with WhatsApp on very small screens if they are close */
    @media (max-width: 380px) {
        .mobile-course-fab {
            bottom: 85px; /* Stack above if needed, or adjust left/right */
        }
    }
</style>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/971543202013" class="float-whatsapp" target="_blank">
    <span class="whatsapp-text">Questions about courses? Chat now</span>
    <i class="fab fa-whatsapp my-float"></i>
</a>

<style>
    .float-whatsapp {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 90px;
        right: 20px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 2px 2px 3px #999;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .my-float {
        margin-top: 0px;
    }

    .whatsapp-text {
        visibility: hidden;
        width: 240px;
        background-color: #fff;
        color: #333;
        text-align: center;
        border-radius: 6px;
        padding: 5px 10px;
        position: absolute;
        z-index: 10001;
        right: 70px; /* Position to the left of the button */
        bottom: 12px;
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
    }
    
    .whatsapp-text::after {
        content: "";
        position: absolute;
        top: 50%;
        right: -10px; /* Arrow pointing to the button */
        margin-top: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent transparent #fff;
    }

    .float-whatsapp:hover {
        background-color: #128C7E;
        color: #FFF;
        width: 60px; /* Keep button circular on hover */
    }

    .float-whatsapp:hover .whatsapp-text {
        visibility: visible;
        opacity: 1;
    }
    
    /* Mobile responsive adjustments if needed */
    @media (max-width: 768px) {
        .float-whatsapp {
            bottom: 15px;
            right: 15px;
            width: 50px;
            height: 50px;
            font-size: 25px;
        }
        .whatsapp-text {
            bottom: 8px;
            right: 60px;
            width: 200px;
            font-size: 12px;
        }
        /* Hide scroll to top button on mobile */
        #scrollUp {
            display: none !important;
        }
    }
</style>

    @stack('scripts')
</body>

</html>
