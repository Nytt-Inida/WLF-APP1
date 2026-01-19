@extends('main')
@push('head')
    <title>Little Farmers Academy - Online Farming Courses for Kids</title>

    <meta name="title" content="Robotics Farming Sessions for Kids | Farm Activities & Training">
    <meta name="description" content=" Interactive robotics farming sessions for kids with expert guidance.">
@endpush
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .learn-box {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
            margin-right: -10px;
        }

        .learn-box::-webkit-scrollbar {
            width: 0px;
        }

        .learn-box h6 {
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        /* Styling for booking button */
        .booking-btn .theme_btn {
            display: inline-block;
            background-color: #ffa03f;
            /* Custom blue color */
            border: none;
            color: #fff;
            text-align: center;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 100%;
            /* Make button full width */
        }

        .booking-btn .theme_btn:hover {
            background-color: #f6ad10;
            /* Darker shade on hover */
            transform: scale(1.05);
        }

        /* Page title area styling */
        .page-title-area {
            margin-top: 50px;
        }

        /* Additional text section */
        .course-description {
            margin-top: 20px;
            font-size: 18px;
            color: #555;
            line-height: 1.6;
        }

        /* Teacher bio styling */
        .teacher-bios {
            margin-top: 30px;
        }

        .teacher-bios p {
            font-size: 16px;
            color: #333;
        }

        .teacher-bios .teacher {
            margin-bottom: 15px;
        }

        /* Related skills styling */
        .skill-area {
            margin-top: 35px;
        }

        .courses-title {
            margin-bottom: 35px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        /* Custom styles for course details and content sections */
        .courses-ingredients {
            margin-bottom: 30px;
        }

        .courses-ingredients ul.courses-item li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            font-size: 16px;
            color: #333;
        }

        /* Custom styling for modals, buttons, and content */
        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .slider-area {
            padding-top: 100px !important;
        }

        .robotics_banner img {
            max-width: 1536px;
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        #submitBtn {
            display: block;
            width: 100%;
            margin-top: 1rem;
        }

        .theme_btn {
            padding: 14px 25px !important
        }


        /* Responsive styling for mobile view */
        @media (max-width: 768px) {

            .modal-content {
                margin: 10px auto;
                width: 95%;
                padding: 15px;
            }

            .slider-area {
                padding-top: 2rem !important;
            }
        }
    </style>

    <main>
        <section class="slider-area pt-xs-150  pb-xs-35">

        </section>
        <!-- Course Overview Area -->
        <section class="page-title-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12 robotics_banner">
                        <img src="{{ asset('assets/img/robotics_banner.png') }}"
                            alt="Children working and exploring the farm" width="636" height="358">
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="page-title-wrapper mb-50">
                            <h1 class="page-title mb-25" id="course_name">Robotics in Farming for Kids | Live Online Course
                            </h1>
                            <div class="course-description">
                                <p>Welcome to our "Robotics in Agriculture" course, designed to introduce you to the latest
                                    robotic technologies transforming modern farming. Learn how robots can enhance
                                    efficiency, automate complex tasks, and support sustainable agricultural practices. Open
                                    to students aged 10 and above, this course offers hands-on sessions led by experts in
                                    robotics and agriculture.</p>
                            </div>
                            <div class="teacher-bios">
                                <p class="teacher"><b>Praveen P</b>, Robotics Expert - Specializing in AI-driven robotics
                                    for agricultural applications.</p>
                                <p class="teacher"><b>Subarna V</b>, Agriculturist - Integrating robotics into traditional
                                    farming for increased productivity.</p>
                                <p class="teacher"><b>Mensilla M</b>, Food Scientist - Exploring the role of robotics in
                                    post-harvest processing and quality control.</p>
                            </div>
                            <div class="booking-btn d-flex align-items-center justify-content-center">
                                <a class="theme_btn my-4 w-50 py-3" data-bs-toggle="modal"
                                    data-bs-target="#bookingModal">Book a Live
                                    Session</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Booking Form Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Book a Live Session</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <div id="alertContainer"></div>

                        <form id="bookingForm">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required pattern="[A-Za-z\s]+" title="Must contain only letters and spaces">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-2">
                                <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-2">
                                <label for="school_name" class="form-label">School/College/Organization <span class="text-danger">*</span></label>
                                <input type="text" name="school_name" id="school_name" class="form-control" required pattern="[A-Za-z\s]+" title="Must contain only letters and spaces">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-2">
                                <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                                <input type="number" name="age" id="age" class="form-control" min="1"
                                    max="100" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-2">
                                <label for="date" class="form-label">Next Live Session Date <span class="text-danger">*</span></label>
                                <input type="date" id="preferred_date" name="date" class="form-control" readonly>
                                <div class="invalid-feedback"></div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <span id="submitText">Submit Booking</span>
                                <span id="loadingText" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Submitting..
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Details Area -->
        <section class="course-details-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-7">
                        <!--<div class="project-details mb-65 text-center text-sm-start">-->
                        <!--    <p>Live "Robotics in Agriculture" Course for All Ages</p>-->
                            
                        <!--    <h5 class="mb-25" style="line-height: 2rem;"><span>Created by</span> Robotics Expert Praveen-->
                        <!--        P, Agriculturist Subarna V,-->
                        <!--        and Food Scientist Mensilla M</h5>-->
                        <!--    <div class="date-lang">-->
                        <!--        <span><b>Date Updated:</b> Today</span>-->
                        <!--        <span><b>Language:</b> English</span>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="meet-our-teacher mb-65">
                            <h2 class="courses-title mb-30">About the Course</h2>
                            <p class="mb-15">
                                The <b>"Robotics in Agriculture course"</b> introduces students to how <b>robotics and automation are transforming modern farming</b>. Designed for learners <b>aged 10 and above</b>, the course offers hands-on sessions guided by experts in robotics and agriculture.
                                </p>

                            <p class="mb-15">
                                Through <b>interactive lessons and practical demonstrations,</b> students learn how farm robots use sensors, mechanical design, and programming to plant seeds, water crops, and assist in harvesting. The course also covers smart farming technologies and agri-bots used in real agricultural tasks.</p>

                            <p class="mb-15">
                                By the end of the course, learners gain practical experience by <b>building simple robotic simulations and small farm-robot prototypes</b>, helping them understand how robotics is shaping the future of agriculture.</p>


                            <h3 class="mb-15">What Your Child Will Learn</h3>
                            <ul class="list-unstyled mb-25" style="font-size: 16px; line-height: 1.8;">
                                <li><i class="fas fa-robot me-2" style="color: #ffa03f;"></i> Understand how <strong>robots work in farming</strong> — from soil preparation to harvesting.</li>
                                <li><i class="fas fa-cogs me-2" style="color: #ffa03f;"></i> Explore <strong>robot components</strong> like motors, sensors, controllers, and actuators.</li>
                                <li><i class="fas fa-laptop-code me-2" style="color: #ffa03f;"></i> Learn how <strong>automation and coding</strong> improve precision in irrigation and crop care.</li>
                                <li><i class="fas fa-tractor me-2" style="color: #ffa03f;"></i> Discover real-world <strong>agri-bots</strong> used for seeding, weeding, and monitoring crops.</li>
                                <li><i class="fas fa-battery-full me-2" style="color: #ffa03f;"></i> Build simple robotic simulations and small-scale farm robot prototypes at home.</li>
                            </ul>

                            <h3 class="mb-15">Course Highlights</h3>
                            <ul class="list-unstyled mb-0" style="font-size: 16px; line-height: 1.8;">
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>Live interactive sessions</strong> with step-by-step guidance and demonstrations.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>Hands-on activities</strong> to simulate robot behavior in agriculture.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>STEM-based lessons</strong> connecting robotics, coding, and farming technology.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>Downloadable worksheets</strong> and build guides for home practice.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>Certificate of Completion</strong> for Robotics in Agriculture.</li>
                            </ul>
                        </div>
                        <div class="skill-area">
                            <h2 class="courses-title mb-35">Related Skills</h2>
                            <div class="courses-tag-btn">
                                <a >Robotics</a>
                                <a >Automation</a>
                                <a >STEM for Kids</a>
                                <a >Farming Technology</a>
                                <a >Programming Basics</a>
                                <a >Sensors & Motors</a>
                                <a >AgriBots</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-5">
                        <div class="courses-ingredients">
                            <h2 class="corses-title mb-30">Course Includes</h2>
                            <p>
                                Discover how <strong>robotics is changing modern agriculture</strong>. Kids explore how
                                robots help farmers
                                save time, reduce effort, and improve yields. The course combines <strong>mechanical
                                    systems, sensors, and
                                    coding</strong> to teach children the logic behind automated farming. Ideal for young
                                tech enthusiasts and
                                future engineers!
                            </p>
                            <ul class="courses-item mt-25">
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/video.svg"
                                        alt="Children engaging with educator during gardening activities" width="24" height="24">
                                    Live and recorded classes with real-world robotics demonstrations
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/newspaper.svg"
                                        alt="Children engaging with educator during gardening activities" width="24" height="24">
                                    Illustrated tutorials on robotics concepts and agricultural automation
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/download.svg"
                                        alt="Kids harvesting their vegetables in a farm" width="24" height="24">
                                    Downloadable worksheets, build guides, and simulation tools
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/infinity.svg"
                                        alt="Children inspired by robotics farming classs" width="24" height="24">
                                    Full lifetime access to course content and updates
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/mobile.svg"
                                        alt="Children inspired by robotics farming class" width="24" height="24">
                                    Accessible on mobile, tablet, and desktop devices
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/certificate-line.svg"
                                        alt="Children engaging with educator during gardening activities" width="24" height="24">
                                    Certificate of Completion downloadable as a PDF
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function getThirdSaturday(year, month) {
                let date = new Date(year, month, 1);
                let saturdayCount = 0
                while (date.getMonth() == month) {
                    if (date.getDay() === 6) {
                        saturdayCount++;
                        if (saturdayCount === 3) {
                            return date
                        }
                    }
                    date.setDate(date.getDate() + 1);
                }
            }

            function setNextAvailableThirdSaturday() {
                let today = new Date();
                let year = today.getFullYear();
                let month = today.getMonth();

                let thirdSaturday = getThirdSaturday(year, month);

                if (today > thirdSaturday) {
                    month++;
                    if (month > 11) {
                        month = 0;
                        year++;
                    }
                    thirdSaturday = getThirdSaturday(year, month);
                }

                let yyyy = thirdSaturday.getFullYear();
                let mm = String(thirdSaturday.getMonth() + 1).padStart(2, '0');
                let dd = String(thirdSaturday.getDate()).padStart(2, '0');
                let formattedDate = `${yyyy}-${mm}-${dd}`;

                document.getElementById("preferred_date").value = formattedDate;
                document.getElementById("preferred_date").min = formattedDate;
            }

            setNextAvailableThirdSaturday();


            const form = document.getElementById('bookingForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');
            const alertContainer = document.getElementById('alertContainer');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                clearValidationErrors();
                setLoadingState(true);
                alertContainer.innerHTML = "";

                const formData = {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    school_name: document.getElementById('school_name').value,
                    age: document.getElementById('age').value,
                    date: document.getElementById('preferred_date').value,
                    course_name: document.getElementById('course_name').textContent,
                };


                fetch("{{ route('booking.submit') }}", {
                        method: 'POST',
                        body: JSON.stringify(formData),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => Promise.reject({
                                status: response.status,
                                ...data
                            }));
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            form.reset();
                            setNextAvailableThirdSaturday(); // Reset the date after form reset
                            setTimeout(() => {
                                const modal = bootstrap.Modal.getInstance(document
                                    .getElementById('bookingModal'));
                                if (modal) modal.hide();
                            }, 2000);
                        } else {
                            showAlert('danger', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error from Form submit:-', error);
                        if (error.status === 401) {
                            const loginUrl =
                                "{{ route('login.form', ['redirect_to' => url()->current()]) }}";
                            showAlert('warning',
                                `Please login to book a live session. <a href="${loginUrl}" class="alert-link">Login</a>`
                            );
                        } else if (error.status === 409) {
                            showAlert('warning', error.message ||
                                'You have already enrolled for this course.');
                        } else if (error.errors) {
                            displayValidationErrors(error.errors);
                            showAlert('danger', 'Please correct the errors below.');
                        } else {
                            showAlert('danger', error.message ||
                                'An error occurred. Please try again.'); // Fixed: missing showAlert
                        }
                    })
                    .finally(() => {
                        setLoadingState(false);
                    });
            });

            function setLoadingState(isLoading) {
                submitBtn.disabled = isLoading;
                if (isLoading) {
                    submitText.style.display = 'none';
                    loadingText.style.display = 'inline-block';
                } else {
                    submitText.style.display = 'inline-block';
                    loadingText.style.display = 'none';
                }
            }

            function showAlert(type, message) {
                const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
                alertContainer.innerHTML = alertHtml;
            }

            function displayValidationErrors(errors) {
                for (const [field, messages] of Object.entries(errors)) {
                    const input = document.getElementById(field);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = messages[0];
                        }
                    }
                }
            }

            function clearValidationErrors() {
                const inputs = form.querySelectorAll('.form-control');
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                    const feedback = input.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = "";
                    }
                });
            }

            form.addEventListener('input', function(e) {
                if (e.target.classList.contains('is-invalid')) {
                    e.target.classList.remove('is-invalid');
                    const feedback = e.target.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = '';
                    }
                }
            });
        });
    </script>
@endsection
