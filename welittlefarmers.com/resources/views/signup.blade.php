@extends('main')

@push('head')
    <link rel="preload" as="image" href="{{ asset('assets/img/signin/signin.jpeg') }}">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Apply Sans-serif font globally */
        body,
        input,
        button,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: sans-serif;
        }

        /* Signup button hover effect */
        .theme_btn:hover {
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s ease;
        }

        /* Sign-in Image */
        .signin-img img {
            object-fit: cover;
            width: 100%;
            max-width: 665px;
            height: 731px;
            max-height: 731px !important;
            margin-top: 1rem !important;
        }

        /* Form Wrapper */
        .contact-form-wrapper {
            background-color: #ECFBCA;
            border-radius: 23px;
            max-width: 595px !important;
            width: 100% !important;
            height: auto !important;
            min-height: 731px;
            padding: 30px !important;
            margin-top: 1rem !important;
        }

        /* Sign-in Head */
        .signin-head h2 {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 32px;
        }

        /* Input Labels */
        .sigin-input label {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 16px;
        }

        /* Input Fields */
        .sigin-input input {
            width: 100%;
            height: 61px;
            border-radius: 10px;
            background-color: #FFFFFF;
            padding-left: 1rem;
            border: none;
        }

        /* Sign-in Button */
        .form-button button {
            margin-top: 1rem;
            height: 50px;
            width: 100%;
            max-width: 130px;
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            font-size: 20px;
            color: #FFFFFF;
            background-color: #ffa03f;
            border: none;
            border-radius: 10px;
            text-align: center;
        }

        .login-singin a {
            color: #ffa03f;
            text-decoration: none;
        }

        /* Responsive Fixes */
        @media (max-width: 991px) {

            .signin-img,
            .signin-head h2 {
                display: none;
            }

            .contact-form-wrapper {
                width: 100%;
                max-width: 100%;
                height: auto;
                background-color: transparent;
            }

            .sigin-input label {
                font-family: 'Manrope', sans-serif !important;
                font-weight: 200;
                font-size: 20px;
                color: black;
            }

            .sigin-input input {
                height: 48px;
                border-radius: 8px;
                background-color: #FFFFFF;
                border: 1px solid #D9D9D9;
            }

            .form-button button {
                width: 100%;
                max-width: 104px;
                height: 45px;
                border-radius: 23px;
                font-weight: 600;
                font-size: 20px;
                font-family: 'Manrope', sans-serif !important;
                color: #FFFFFF;
                margin-top: 3rem;
            }
        }

        /* Select2 Custom Styling to Match Theme */
        .sigin-input .select2-container {
            width: 100% !important;
            display: block;
        }

        .select2-container--default .select2-selection--single {
            height: 61px !important;
            border-radius: 10px;
            border: none;
            background-color: #FFFFFF;
            display: flex !important;
            align-items: center !important; 
            outline: none;
            width: 100%;
            padding: 0 !important;
            position: relative;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border: none; 
            outline: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 1rem !important;
            color: #495057;
            font-size: 16px;
            font-family: 'Manrope', sans-serif !important;
            font-weight: 600;
            width: 100%;
            line-height: normal !important;
            height: auto !important;
            margin-top: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 15px; 
            position: absolute;
            top: 0;
            display: flex;
            align-items: center;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0 4px;
            height: 0;
            left: 50%;
            margin-left: -4px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0;
        }

        .select2-dropdown {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            font-family: 'Manrope', sans-serif !important;
        }

        .select2-results__option {
            padding: 10px 1rem;
            font-size: 15px;
        }

        @media (max-width: 991px) {
            .select2-container--default .select2-selection--single {
                height: 48px !important;
                border-radius: 10px;
                border: none !important;
            }
            
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                 font-size: 16px; 
                 color: black;
                 font-weight: 200;
                 line-height: normal !important;
            }
        }

        /* Hide conflicting nice-select dropdown for country and age fields */
        .country .nice-select,
        .age .nice-select {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <section class="contact-form-area pt-100 pt-xs-150 pb-md-70 pb-xs-70">
        <div class="container-fluid">
            <div class="row">
                <!-- Sign-in Image (Hidden on Small Screens) -->
                <div class="col-lg-5 d-none d-lg-block m-0 p-0">
                    <div class="signin-img">
                        <img src="{{ asset('assets/img/signin/signin.jpeg') }}"
                            alt="Children inspired by robotics farming class">
                    </div>
                </div>

                <!-- Sign-in Form -->
                <div class="col-lg-7 col-12 d-flex justify-content-center">
                    <div class="contact-form-wrapper w-lg-80">
                        <div class="container text-start px-lg-4 py-lg-4 p-1">
                            <form action="{{ route('signup') }}" method="POST">
                                @csrf
                                <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                                <div class="signin-head text-start">
                                    <h2>Register</h2>
                                </div>

                                <div class="sigin-input mt-3">
                                    {{-- Username --}}
                                    <div class="username mb-2">
                                        <label for="name">Username <span class="text-danger">*</span></label>
                                        <input id="name" type="text" name="name"
                                            value="{{ old('name') }}" required pattern="[A-Za-z\s]+"
                                            title="Must contain only letters and spaces"
                                            class="@error('name') is-invalid @enderror">
                                        @error('name')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="email mt-3 mb-2">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input id="email" type="email" name="email"
                                            value="{{ old('email') }}" required
                                            class="@error('email') is-invalid @enderror">
                                        @error('email')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- School Name --}}
                                    <div class="scl-name mt-3 mb-2">
                                        <label for="school_name">School Name <span class="text-danger">*</span></label>
                                        <input id="school_name" type="text" name="school_name"
                                            value="{{ old('school_name') }}" required pattern="[A-Za-z\s]+"
                                            title="Must contain only letters and spaces"
                                            class="@error('school_name') is-invalid @enderror">
                                        @error('school_name')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Country --}}
                                    <div class="country mt-3 mb-2">
                                        <label for="country">Country <span class="text-danger">*</span></label>
                                        <select id="country" name="country" class="form-control @error('country') is-invalid @enderror" required>
                                            <option value="">Select Country</option>
                                            <option value="Afghanistan">Afghanistan</option>
                                            <option value="Albania">Albania</option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="Andorra">Andorra</option>
                                            <option value="Angola">Angola</option>
                                            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Armenia">Armenia</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Azerbaijan">Azerbaijan</option>
                                            <option value="Bahamas">Bahamas</option>
                                            <option value="Bahrain">Bahrain</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Barbados">Barbados</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="Belize">Belize</option>
                                            <option value="Benin">Benin</option>
                                            <option value="Bhutan">Bhutan</option>
                                            <option value="Bolivia">Bolivia</option>
                                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="Brunei">Brunei</option>
                                            <option value="Bulgaria">Bulgaria</option>
                                            <option value="Burkina Faso">Burkina Faso</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="Cabo Verde">Cabo Verde</option>
                                            <option value="Cambodia">Cambodia</option>
                                            <option value="Cameroon">Cameroon</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Central African Republic">Central African Republic</option>
                                            <option value="Chad">Chad</option>
                                            <option value="Chile">Chile</option>
                                            <option value="China">China</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Comoros">Comoros</option>
                                            <option value="Congo (Democratic Republic of the)">Congo (Democratic Republic of the)</option>
                                            <option value="Congo (Republic of the)">Congo (Republic of the)</option>
                                            <option value="Costa Rica">Costa Rica</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Cuba">Cuba</option>
                                            <option value="Cyprus">Cyprus</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Djibouti">Djibouti</option>
                                            <option value="Dominica">Dominica</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="East Timor">East Timor</option>
                                            <option value="Ecuador">Ecuador</option>
                                            <option value="Egypt">Egypt</option>
                                            <option value="El Salvador">El Salvador</option>
                                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                                            <option value="Eritrea">Eritrea</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Eswatini">Eswatini</option>
                                            <option value="Ethiopia">Ethiopia</option>
                                            <option value="Fiji">Fiji</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="Gabon">Gabon</option>
                                            <option value="Gambia">Gambia</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ghana">Ghana</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Grenada">Grenada</option>
                                            <option value="Guatemala">Guatemala</option>
                                            <option value="Guinea">Guinea</option>
                                            <option value="Guinea-Bissau">Guinea-Bissau</option>
                                            <option value="Guyana">Guyana</option>
                                            <option value="Haiti">Haiti</option>
                                            <option value="Honduras">Honduras</option>
                                            <option value="Hungary">Hungary</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="India">India</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="Iran">Iran</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Ivory Coast">Ivory Coast</option>
                                            <option value="Jamaica">Jamaica</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Jordan">Jordan</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Kiribati">Kiribati</option>
                                            <option value="Korea (North)">Korea (North)</option>
                                            <option value="Korea (South)">Korea (South)</option>
                                            <option value="Kosovo">Kosovo</option>
                                            <option value="Kuwait">Kuwait</option>
                                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                                            <option value="Laos">Laos</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lebanon">Lebanon</option>
                                            <option value="Lesotho">Lesotho</option>
                                            <option value="Liberia">Liberia</option>
                                            <option value="Libya">Libya</option>
                                            <option value="Liechtenstein">Liechtenstein</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Madagascar">Madagascar</option>
                                            <option value="Malawi">Malawi</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Maldives">Maldives</option>
                                            <option value="Mali">Mali</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Mauritania">Mauritania</option>
                                            <option value="Mauritius">Mauritius</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Micronesia">Micronesia</option>
                                            <option value="Moldova">Moldova</option>
                                            <option value="Monaco">Monaco</option>
                                            <option value="Mongolia">Mongolia</option>
                                            <option value="Montenegro">Montenegro</option>
                                            <option value="Morocco">Morocco</option>
                                            <option value="Mozambique">Mozambique</option>
                                            <option value="Myanmar">Myanmar</option>
                                            <option value="Namibia">Namibia</option>
                                            <option value="Nauru">Nauru</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherlands">Netherlands</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Nicaragua">Nicaragua</option>
                                            <option value="Niger">Niger</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="North Macedonia">North Macedonia</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Oman">Oman</option>
                                            <option value="Pakistan">Pakistan</option>
                                            <option value="Palau">Palau</option>
                                            <option value="Palestine">Palestine</option>
                                            <option value="Panama">Panama</option>
                                            <option value="Papua New Guinea">Papua New Guinea</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Philippines">Philippines</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Qatar">Qatar</option>
                                            <option value="Romania">Romania</option>
                                            <option value="Russia">Russia</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                            <option value="Saint Lucia">Saint Lucia</option>
                                            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                            <option value="Samoa">Samoa</option>
                                            <option value="San Marino">San Marino</option>
                                            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                            <option value="Senegal">Senegal</option>
                                            <option value="Serbia">Serbia</option>
                                            <option value="Seychelles">Seychelles</option>
                                            <option value="Sierra Leone">Sierra Leone</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="Solomon Islands">Solomon Islands</option>
                                            <option value="Somalia">Somalia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="South Sudan">South Sudan</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="Sudan">Sudan</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Syria">Syria</option>
                                            <option value="Taiwan">Taiwan</option>
                                            <option value="Tajikistan">Tajikistan</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Togo">Togo</option>
                                            <option value="Tonga">Tonga</option>
                                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                            <option value="Tunisia">Tunisia</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Turkmenistan">Turkmenistan</option>
                                            <option value="Tuvalu">Tuvalu</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="United Arab Emirates">United Arab Emirates</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="United States">United States</option>
                                            <option value="Uruguay">Uruguay</option>
                                            <option value="Uzbekistan">Uzbekistan</option>
                                            <option value="Vanuatu">Vanuatu</option>
                                            <option value="Vatican City">Vatican City</option>
                                            <option value="Venezuela">Venezuela</option>
                                            <option value="Vietnam">Vietnam</option>
                                            <option value="Yemen">Yemen</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Zimbabwe">Zimbabwe</option>
                                        </select>
                                        @error('country')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Age --}}
                                    <div class="age mt-3 mb-2">
                                        <label for="age">Age <span class="text-danger">*</span></label>
                                        <select id="age" name="age" class="form-control @error('age') is-invalid @enderror" required>
                                            <option value="">Select or Type Age</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                        </select>
                                        @error('age')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>



                                    {{-- Referral Code (Optional) --}}
                                    <div class="referral-code mt-3 mb-2">
                                        <label for="referral_code">Referral Code (Optional)</label>
                                        <input id="referral_code" type="text" name="referral_code"
                                            value="{{ old('referral_code') }}" placeholder="Enter referral code"
                                            class="@error('referral_code') is-invalid @enderror">
                                        @error('referral_code')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-button text-start">
                                        <button type="submit">Sign up</button>
                                    </div>

                                    <div class="login-singin mt-4 text-start">
                                        <p>Already have an account? <a href="{{ route('login.form', ['redirect_to' => request('redirect_to')]) }}">Login</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 on country
            $('#country').select2({
                placeholder: "Select a country",
                allowClear: false,
                width: '100%'
            });

            // Initialize Select2 on Age with tagging (custom typing)
            $('#age').select2({
                placeholder: "Select or Type Age",
                allowClear: false,
                width: '100%',
                tags: true, // Allow custom values
                createTag: function (params) {
                    // Don't allow empty tags
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: term,
                        newTag: true // add additional parameters
                    }
                }
            });

            // Auto-fill referral code from URL
            const urlParams = new URLSearchParams(window.location.search);
            const refCode = urlParams.get('ref') || urlParams.get('referral_code'); // Support both
            
            if (refCode) {
                const refInput = document.getElementById('referral_code');
                if (refInput) {
                    refInput.value = refCode;
                    refInput.style.borderColor = '#4CAF50';
                    refInput.style.backgroundColor = '#e8f5e9';
                }
            }
        });

        //handle errors
        @if (session('error'))
            alert(@json(session('error')));
        @endif

        @if ($errors->any())
            let error = @json($errors->all());
            alert(error.join("\n"));
        @endif
    </script>
@endpush
