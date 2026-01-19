@extends('main')

@push('head')
    <link rel="preload" as="image" href="{{ asset('assets/img/login/loginImg.jpeg') }}">
    <style>
        .login-img img {
            object-fit: cover;
            width: 100%;
            max-width: 613px;
            height: 100vh;
            max-height: 707px;
            border-radius: 34px;
            opacity: 92%;
        }

        .contact-form-wrapper {
            width: 100%;
            max-width: 700px;
            height: 100vh;
            max-height: 381px;

        }

        .login-head h2 {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 700;
            font-size: 64px;
        }

        .login-input label {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 300;
            font-size: 24px;
            color: #3C3232;
        }

        .login-input input {
            width: 100%;
            max-width: 474px;
            height: 61px;
            border-radius: 7px;
            background-color: #FFFFFF;
            padding-left: 1rem;
            border: 1px solid #00000040;
        }

        .form-button button {
            width: 100%;
            max-width: 138px;
            height: 64px;
            border-radius: 55px;
            font-weight: 700;
            font-size: 24px;
            font-family: 'Manrope', sans-serif !important;
            color: #FFFFFF;
            text-align: center;
            background-color: #ffa03f;
            border: none;
        }

        .login-input p {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 300;
            font-size: 20px;
        }

        .login-input p a {
            font-weight: 600px;
            color: #ffa03f;
        }

        /* Responsive Fixes */
        @media (max-width: 991px) {
            .login-img {
                display: none;
            }

            .login-head h2 {
                font-family: 'Manrope', sans-serif !important;
                font-weight: 600;
                font-size: 40px;
            }

            .login-input {
                width: 100%;
                height: 100vh;
                max-height: 401px;
                background: #E6F2CA;
                box-shadow: 0px 10px 20px #BCC7A5;
                border-radius: 15px;
                padding: 30px 20px
            }

            .login-input label {
                font-weight: 400;
                font-size: 18px;
            }

            .login-input input {
                height: 48px;
                max-width: 700px !important;
                border: 1px solid #D9D9D9;
                margin-top: 2rem;

            }

            .form-button button {
                width: 100% !important;
                max-width: none !important;
                height: 40px !important;
                text-align: center;
            }

            .login-input p {
                font-weight: 400;
                font-size: 15px;
            }

            .login-input p a {
                font-weight: 400;
                color: #ffa03f;
                font-size: 15px;
            }

            .contact-form-wrapper {
                max-width: 650px !important;
            }

            .form-button button {
                font-weight: normal;
                font-size: 18px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="contact-form-area pt-100 pt-xs-150 pb-md-70 pb-xs-70">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-lg-6 d-md-block m-0 p-0 text-center">
                    <div class="login-img">
                        <img src="{{ asset('assets/img/login/loginImg.jpeg') }}"
                            alt="Kids harvesting their vegetables in a farm">
                    </div>
                </div>
                <div
                    class="col-lg-6 col-12 d-flex justify-content-lg-start align-items-center justify-content-center">
                    <div class="contact-form-wrapper">
                        <div class="container text-start p-0 px-lg-4">
                            <form id="login-form" action="{{ route('login') }}" method="POST">
                                @csrf
                                <input type="hidden" name="redirect_to"
                                    value="{{ request('redirect_to', route('course.details', ['id' => 1])) }}">
                                <!-- Capture pending referral code from signup flow (URL or Session) -->
                                <input type="hidden" name="referral_code" value="{{ request('referral_code') ?? session('pending_referral_code') }}">
                                <div class="login-head">
                                    <h2>Login</h2>
                                </div>
                                
                                @if(session('success'))
                                    <div class="alert alert-success mt-3" role="alert" style="border-radius: 10px;">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger mt-3" role="alert" style="border-radius: 10px;">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                
                                <div class="login-input mt-4">
                                    <div class="email">
                                        <label for="email">
                                            Email
                                        </label> <br />
                                        <input type="email" name="email" value="{{ session('email') ?? old('email') }}" required>
                                    </div>
                                    <div class="otp mt-3" id="otp-input" style="display: none;">
                                        <label for="otp">
                                            Enter OTP
                                        </label> <br>
                                        <input type="text" name="otp" required />
                                    </div>
                                    <div class="form-button text-lg-start mt-4 w-100">
                                        <button id="get-otp-btn" type="button" onclick="sendOtp()">
                                            Get OTP
                                        </button>
                                        <button id="login-btn" type="submit"
                                            style="display: none;">Login</button>
                                    </div>
                                    <p class="login-singin mt-4" data-signup-url="{{ route('signup.form') }}">
                                        Don't have an account? <a href="{{ route('signup.form', ['redirect_to' => request('redirect_to')]) }}">Register</a>
                                    </p>
                                </div>
                            </form>
                            @if ($errors->has('otp'))
                                <script>
                                    alert('{{ $errors->first('otp') }}');
                                </script>
                            @endif
                            @if ($errors->has('email'))
                                <script>
                                    alert('{{ $errors->first('email') }}');
                                </script>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @if (session('otp_sent'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Auto-show OTP field if redirected from successful signup
            document.getElementById('otp-input').style.display = 'block';
            document.getElementById('get-otp-btn').style.display = 'none';
            document.getElementById('login-btn').style.display = 'block';
        });
    </script>
    @endif

    <script>
        function sendOtp() {
            var email = document.querySelector('input[name="email"]').value;
            if (!email) {
                alert('Please enter your email address first.');
                return;
            }

            fetch('{{ route('send.otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.code === 200) {
                        alert(data.message);

                        // Show the OTP input and login button
                        document.getElementById('otp-input').style.display = 'block';
                        document.getElementById('login-btn').style.display = 'block';

                        // Hide the Get OTP button
                        document.getElementById('get-otp-btn').style.display = 'none';
                    } else if (data.code === 404) {
                        if(confirm(data.message)) {
                            window.location.href = "{{ route('signup.form') }}";
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function updateSignupText() {
            let paragraph = document.querySelector('.login-singin');
            let button = document.getElementById('get-otp-btn');

            if (paragraph) {
                let signupUrl = paragraph.getAttribute('data-signup-url') || "#"; // Fallback URL
                if (window.innerWidth <= 768) {
                    paragraph.innerHTML = `New user? <a href="${signupUrl}">Sign up</a>`;
                } else {
                    paragraph.innerHTML = `Don't have an account? <a href="${signupUrl}">Register</a>`;
                }
            }

            if (button) {
                button.textContent = window.innerWidth <= 768 ? 'Generate OTP' : 'Get OTP';
            }
        }

        // Run on page load
        window.addEventListener('DOMContentLoaded', updateSignupText);
        // Run on screen resize
        window.addEventListener('resize', updateSignupText);
    </script>
@endpush
