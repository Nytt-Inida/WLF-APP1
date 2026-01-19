@extends('main')
@push('head')
    <title>AI in Agriculture for Kids | Smart Farming Live Course</title>

    <meta name="title" content="Agricultural AI Classes for Kids & Live Online session">
    <meta name="description"
        content=" Agricultural AI classes for kids with live online sessions to learn farming with robotics and innovative agriculture techniques.">
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

        .booking-btn .theme_btn {
            display: inline-block;
            background-color: #ffa03f;
            border: none;
            color: #fff;
            text-align: center;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 100%;
        }

        .booking-btn .theme_btn:hover {
            background-color: #f6ad10;
            transform: scale(1.05);
        }

        .page-title-area {
            margin-top: 50px;
        }

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

        .ai_course_banner img {
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
        }
    </style>

    <main>
        <!--slider-area start-->
        <section class="slider-area pt-100 pb-xs-35">

        </section>
        <!-- page-title-area end -->
        <!-- Course Overview Area -->
        <section class="page-title-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12 ai_course_banner p-0">
                        <img src="{{ asset('assets/img/agriculture_ai_banner.png') }}"
                            alt="Children engaging with educator during gardening activities" width="636" height="358">
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="page-title-wrapper mb-50">
                            <h1 class="page-title mb-25" id="course_name">AI in Agriculture for Kids | Smart Farming Live
                                Course
                            </h1>
                            <div class="course-description">
                                <p>Welcome to our exclusive "AI in Agriculture" course, where you’ll dive into the world of
                                    technology-driven farming! Our live sessions are led by industry experts and offer
                                    hands-on experience in AI applications for agriculture. Join us to learn how AI can
                                    optimize crop production, automate farm operations, and create a sustainable
                                    agricultural environment. Open to learners aged 10 and above.</p>
                            </div>
                            <div class="teacher-bios">
                                <p class="teacher"><b>Praveen P</b>, Data Scientist - Bringing cutting-edge AI solutions to
                                    agriculture.</p>
                                <p class="teacher"><b>Subarna V</b>, Agriculturist - Focusing on sustainable and
                                    technology-integrated farming.</p>
                                <p class="teacher"><b>Mensilla M</b>, Food Scientist - Bridging the gap between farm produce
                                    and food technology.</p>
                            </div>
                            <div class="booking-btn d-flex align-items-center justify-content-center">
                                <a class="theme_btn my-4 w-50 py-3" data-bs-toggle="modal"
                                    data-bs-target="#bookingModal">Book a
                                    Live
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
                                <input type="text" name="name" id="name" class="form-control" required
                                    pattern="[A-Za-z\s]+" title="Must contain only letters and spaces">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-2">
                                <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" required
                                    class="@error('email') is-invalid @enderror">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-2">
                                <label for="school_name" class="form-label">School/College/Organization <span class="text-danger">*</span></label>
                                <input type="text" name="school_name" id="school_name" class="form-control" required
                                    pattern="[A-Za-z\s]+" title="Must contain only letters and spaces">
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
                        <!--    <p>Live "AI in Agriculture" Course for All Ages</p>-->
                            
                        <!--    <h5 class="mb-25" style="line-height: 2rem;"><span>Created by</span> Data Scientist Praveen-->
                        <!--        P, Agriculturist Subarna V, and-->
                        <!--        Food Scientist Mensilla M</h5>-->
                        <!--    <div class="date-lang">-->
                        <!--        <span><b>Date Updated:</b> Today</span>-->
                        <!--        <span><b>Language:</b> English</span>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="meet-our-teacher mb-65">
                            <h2 class="courses-title mb-30">About the Course</h2>
                            <p class="mb-15">
                            The <b>"AI in Agriculture course"</b> introduces children to how <b>artificial intelligence is transforming modern farming</b>. Learners discover how AI makes agriculture smarter, more efficient, and eco-friendly, from crop management to resource optimization.
                            </p>
                            <p class="mb-15">Through <b>live expert sessions, interactive lessons, and hands-on demonstrations,</b> students learn how AI helps monitor soil health, predict weather, improve crop growth, automate farming tasks, and support precision farming.</p>

                            <p class="mb-15">
                            Open to learners <b>aged 10 and above</b>, this course builds a strong foundation in <b>AI-driven agriculture and sustainable farming practices</b>. By the end, students gain practical knowledge and future-ready skills to understand how technology is shaping the future of farming.
                            </p>

                            <h3 class="mb-15">What Your Child Will Learn</h3>
                            <ul class="list-unstyled mb-25" style="font-size: 16px; line-height: 1.8;">
                                <li><i class="fas fa-robot me-2" style="color: #ffa03f;"></i> Understand how <strong>AI and machine learning</strong> support modern farming.</li>
                                <li><i class="fas fa-thermometer-half me-2" style="color: #ffa03f;"></i> Work with <strong>sensor data</strong> (temperature, humidity, soil moisture, light) to spot patterns.</li>
                                <li><i class="fas fa-camera me-2" style="color: #ffa03f;"></i> Use <strong>Image AI</strong> (leaf images) for basic plant health checks and pest/disease flags.</li>
                                <li><i class="fas fa-brain me-2" style="color: #ffa03f;"></i> Learn simple data concepts: datasets, labels, training vs. testing, accuracy.</li>
                                <li><i class="fas fa-chart-bar me-2" style="color: #ffa03f;"></i> Build mini dashboards to visualize sensor readings and plant images.</li>
                            </ul>
                            <h3 class="mb-15">Course Highlights</h3>
                            <ul class="list-unstyled mb-0" style="font-size: 16px; line-height: 1.8;">
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>Live online sessions</strong> with interactive Q&A and coding walk-throughs (no-code & beginner-friendly code).</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>Hands-on projects</strong>: sensor logs + image sets for plant health exploration.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>Ready-to-use datasets</strong> & worksheets for practice at home or school.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>Starter notebooks</strong> (block-based or Python) for Image AI experiments.</li>
                                <li><i class="fas fa-check-circle me-2" style="color: #28a745;"></i> <strong>Certificate of Completion</strong> for the AI in Agriculture course.</li>
                            </ul>
                        </div>
                        <div class="skill-area">
                            <h2 class="courses-title mb-35">Related Skills</h2>
                            <div class="courses-tag-btn">
                                <a >AI in Agriculture</a>
                                <a >Smart Farming</a>
                                <a >Sensor Data</a>
                                <a >Image AI</a>
                                <a >Computer Vision</a>
                                <a >Data Visualization</a>
                                <a >STEM for Kids</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-5">
                        <div class="courses-ingredients">
                            <h2 class="corses-title mb-30">Course Includes</h2>
                            <p>
                                Explore how <strong>AI transforms farming</strong> using real-world
                                <strong>sensor data</strong> and <strong>plant images</strong>. Kids learn by doing:
                                cleaning data, labeling images, and testing simple models to understand how AI detects plant
                                stress and supports healthy growth.
                            </p>
                            <ul class="courses-item mt-25">
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/video.svg"
                                        alt="Children inspired by robotics farming class" width="24" height="24">
                                    Live online classes + on-demand replays (beginner-friendly)
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/newspaper.svg"
                                        alt="Children inspired by robotics farming class" width="24" height="24">
                                    Step-by-step guides on sensor logging & Image AI basics
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/download.svg"
                                        alt="Kids harvesting their vegetables in a farm" width="24" height="24">
                                    Downloadable datasets, activity sheets, and starter notebooks
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/infinity.svg"
                                        alt="Children inspired by robotics farming classs" width="24" height="24">
                                    Full lifetime access to course materials and updates
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/mobile.svg"
                                        alt="A kid is learning online farming techniques" width="24" height="24">
                                    Access on mobile, tablet, or desktop devices
                                </li>
                                <li>
                                    <img src="https://welittlefarmers.com/assets/img/icon/certificate-line.svg"
                                        alt="Children engaging with educator during gardening activities" width="24" height="24">
                                    Certificate of Completion (AI in Agriculture)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
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
                            setNextAvailableThirdSaturday();
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
                                'An error occurred. Please try again.');
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
