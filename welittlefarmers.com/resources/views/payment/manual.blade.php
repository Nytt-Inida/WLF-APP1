<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Enrollment - {{ $course->title }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
            width: 100%;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: #fdfbf7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1B212F;
        }

        .payment-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            max-width: 1000px;
            width: 100%;
            display: flex;
            overflow: hidden;
            position: relative;
        }

        /* Left Side - Visuals */
        .card-visual {
            background: #EAFBF3;
            flex: 0.8;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-right: 1px solid rgba(0,0,0,0.03);
        }

        .course-badge {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 15px;
            display: inline-block;
        }

        .course-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 5px;
            line-height: 1.3;
            color: #1B212F;
        }

        .price-tag {
            font-size: 42px;
            font-weight: 800;
            color: #1B212F;
            margin: 20px 0;
            letter-spacing: -1px;
        }
        
        .price-tag .original {
            font-size: 18px;
            color: #999;
            text-decoration: line-through;
            font-weight: 500;
            margin-right: 10px;
        }

        .qr-frame {
            background: white;
            padding: 15px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            object-fit: contain;
            display: block;
        }

        .scan-hint {
            font-size: 14px;
            color: #555;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Right Side - Actions */
        .card-action {
            flex: 1.2;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .timer-pill {
            background: #FFF4E5;
            color: #B95000;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            align-self: flex-start;
            margin-bottom: 30px;
        }

        .timer-pill i {
            animation: pulse 2s infinite;
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .steps-list {
            list-style: none;
            margin-bottom: 30px;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            background: #F3F4F6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #1B212F;
            font-size: 16px;
            flex-shrink: 0;
        }
        
        .step-icon.active {
            background: #ffa03f;
            color: white;
        }

        .step-content h4 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .step-content p {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }

        .coupon-box {
            background: #F9FAFB;
            border: 1px dashed #D1D5DB;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .coupon-input {
            border: none;
            background: transparent;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            flex: 1;
            outline: none;
            color: #1B212F;
        }

        .coupon-btn {
            font-size: 13px;
            font-weight: 700;
            color: #ffa03f;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
        }
        
        .coupon-btn:disabled {
            color: #ccc;
            cursor: default;
        }

        .btn-complete {
            background: #1B212F;
            color: white;
            border: none;
            width: 100%;
            padding: 18px;
            border-radius: 14px;
            font-size: 18px;
            font-weight: 700;
            font-family: 'Manrope', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-complete:hover {
            transform: translateY(-2px);
            background: #000;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .btn-complete:disabled {
            background: #ccc;
            transform: none;
            cursor: not-allowed;
            box-shadow: none;
        }

        .secure-note {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        /* States for Payment */
        .state-box {
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            margin-top: 20px;
        }
        
        .state-pending {
            background: #FFF8E1;
            border: 1px solid #FFE082;
            color: #B00020;
        }
        
        .state-success {
            background: #E8F5E9;
            border: 1px solid #A5D6A7;
            color: #2E7D32;
        }

        /* Utility */
        .hidden { display: none; }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Mobile */
        @media (max-width: 900px) {
            .payment-card {
                flex-direction: column;
                margin: 20px;
                max-width: 500px;
            }
            .card-visual {
                padding: 30px;
                flex: none;
            }
            .card-action {
                padding: 30px;
                flex: none;
            }
            .qr-code {
                width: 150px;
                height: 150px;
            }
            .price-tag {
                font-size: 32px;
            }
            .back-link {
                top: 15px; 
                left: 15px;
            }
        }
        
        .back-link {
            position: absolute;
            top: 25px;
            left: 30px;
            text-decoration: none;
            color: #666;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            z-index: 20;
            padding: 5px 10px;
            border-radius: 8px;
        }
        .back-link:hover {
            color: #1B212F;
            background: rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>

    <!-- Back Link -->
    <a href="{{ route('course.details', ['id' => $course->id]) }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Course
    </a>

    <div class="payment-card" id="main-card">
        <!-- Expired overlay -->
        <div id="expired-overlay" style="display: none; position: absolute; inset: 0; background: rgba(255,255,255,0.95); z-index: 10; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
            <h2 style="color: #D32F2F;">Session Expired</h2>
            <p style="color: #555; margin-bottom: 20px;">We released your spot. Redirecting you back...</p>
        </div>

        <!-- Left: Visuals -->
        <div class="card-visual">
            <span class="course-badge">Enrolling Now</span>
            <h1 class="course-title">{{ $course->title }}</h1>
            
            <div class="price-tag">
                <span id="display-price">
                    {{ convertPrice($course->price) }}
                </span>
            </div>

            <div class="qr-frame">
                <img src="{{ asset('assets/img/payment-qr.jpg') }}" alt="Payment QR Code" class="qr-code">
            </div>

            <div class="scan-hint">
                <i class="fas fa-camera"></i> Scan with any UPI App
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="card-action" id="payment-content">
            <div class="timer-pill">
                <i class="far fa-clock"></i> We're holding your spot for <span id="timer" style="margin-left:5px">05:00</span>
            </div>

            <h2>Complete Your Order</h2>

            <ul class="steps-list">
                <li class="step-item">
                    <div class="step-icon active"><i class="fas fa-qrcode"></i></div>
                    <div class="step-content">
                        <h4>Scan & Pay</h4>
                        <p>Use GPay, PhonePe, or Paytm to pay <strong id="instruction-price">{{ convertPrice($course->price) }}</strong></p>
                    </div>
                </li>
                <li class="step-item">
                    <div class="step-icon"><i class="fas fa-check"></i></div>
                    <div class="step-content">
                        <h4>Click 'I Have Paid'</h4>
                        <p>Once the payment is successful in your app, click the button below.</p>
                    </div>
                </li>
            </ul>

            <!-- Coupon -->
            <div class="coupon-box">
                <i class="fas fa-tag" style="color: #9CA3AF;"></i>
                <input type="text" id="coupon_code" class="coupon-input" placeholder="Have a referral code?">
                <button id="apply-coupon-btn" class="coupon-btn">Apply</button>
                <button id="remove-coupon-btn" class="coupon-btn" style="display: none; color: #D32F2F;">Remove</button>
            </div>
            <div id="coupon-message" style="margin-top: -20px; margin-bottom: 20px; font-size: 13px; padding-left: 5px;"></div>

            <!-- Pre-existing Status Check -->
            @if (auth()->user()->payment_status == 1 && auth()->user()->pending_course_id == $course->id)
                <div class="state-box state-pending">
                    <h4><i class="fas fa-hourglass-half"></i> Verification Pending</h4>
                    <p style="font-size: 14px; margin-top: 5px;">We are verifying your payment. Access will be granted within 24 hours.</p>
                </div>
            @elseif(auth()->user()->payment_status == 2 && auth()->user()->pending_course_id == $course->id)
                <div class="state-box state-success">
                    <h4><i class="fas fa-check-circle"></i> Already Verified!</h4>
                    <p style="font-size: 14px; margin-top: 5px;">Redirecting you to the course...</p>
                </div>
                <script>setTimeout(() => { window.location.href = "{{ route('course.details', ['id' => $course->id]) }}"; }, 2000);</script>
            @else
                <!-- Main Action Button -->
                <form id="complete-payment-form" action="{{ route('payment.complete') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-complete" id="complete-btn">
                        <span id="btn-text">I Have Paid</span>
                        <span class="spinner" id="spinner" style="display: none;"></span>
                    </button>
                </form>

                <div class="secure-note" style="margin-top: 20px;">
                    <div class="secure-badge" style="display: flex; align-items: center; justify-content: center; gap: 8px; color: #555; font-size: 12px; flex-wrap: wrap;">
                        <span><i class="fas fa-lock" style="color: #4CAF50;"></i> Secure Payment</span>
                        <span style="color: #ddd;">|</span>
                        <span><i class="fas fa-shield-alt" style="color: #4CAF50;"></i> 30-Day Guarantee</span>
                        <span style="color: #ddd;">|</span>
                        <span><i class="fas fa-infinity" style="color: #4CAF50;"></i> Lifetime Access</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Timer Logic
        let timeLeft = 5 * 60; // 5 mins
        const timerEl = document.getElementById('timer');
        const expiredOverlay = document.getElementById('expired-overlay');

        function formatTime(s) {
            const m = Math.floor(s / 60);
            const sc = s % 60;
            return `${String(m).padStart(2, '0')}:${String(sc).padStart(2, '0')}`;
        }

        const countdown = setInterval(() => {
            timeLeft--;
            timerEl.textContent = formatTime(timeLeft);
            if(timeLeft <= 0) {
                clearInterval(countdown);
                handleExpiry();
            }
        }, 1000);

        function handleExpiry() {
            expiredOverlay.style.display = 'flex';
            setTimeout(() => {
                window.location.href = "{{ route('course.details', ['id' => $course->id]) }}";
            }, 3000);
        }

        // Coupon Logic
        let originalPrice = {{ number_format($course->price, 2, '.', '') }};
        let currentPrice = originalPrice;
        let appliedCoupon = null;

        const couponInput = document.getElementById('coupon_code');
        const applyBtn = document.getElementById('apply-coupon-btn');
        const removeBtn = document.getElementById('remove-coupon-btn');
        const msgDiv = document.getElementById('coupon-message');
        const displayPriceEl = document.getElementById('display-price');
        const instructionPriceEl = document.getElementById('instruction-price');

        // Check URL/Session for coupon
        const urlParams = new URLSearchParams(window.location.search);
        const urlCoupon = urlParams.get('coupon_code');
        const sessionCoupon = "{{ $referralCode ?? '' }}"; 
        const effectiveCoupon = urlCoupon || sessionCoupon;

        if (effectiveCoupon) {
            couponInput.value = effectiveCoupon;
            setTimeout(() => applyBtn.click(), 500);
        }

        applyBtn.addEventListener('click', function() {
            const code = couponInput.value.trim();
            if(!code) return;

            applyBtn.disabled = true;
            applyBtn.innerText = '...';
            msgDiv.innerHTML = '';

            fetch(`/api/check-coupon?code=${code}&course_id={{ $course->id }}&user_id={{ auth()->id() }}`)
                .then(res => res.json())
                .then(data => {
                    applyBtn.disabled = false;
                    applyBtn.innerText = 'Apply';

                    if(data.valid) {
                        // 100% OFF Logic
                        if (data.new_price <= 0) {
                            applyBtn.innerText = 'Claiming...';
                            applyBtn.disabled = true;
                            // Auto-claim free
                            fetch("{{ route('payment.freeClaim') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ course_id: {{ $course->id }}, code: code })
                            })
                            .then(res => res.json())
                            .then(claimData => {
                                if(claimData.success) {
                                    msgDiv.innerHTML = `<span style="color: green;"><b>${claimData.message}</b> Redirecting...</span>`;
                                    setTimeout(() => window.location.href = "{{ route('course.details', ['id' => $course->id]) }}", 1000);
                                } else {
                                     msgDiv.innerHTML = `<span style="color: red;">${claimData.message}</span>`;
                                     applyBtn.disabled = false;
                                     applyBtn.innerText = 'Apply';
                                }
                            });
                            return;
                        }

                        // Apply Discount
                        appliedCoupon = code;
                        currentPrice = data.new_price;
                        
                        const fmtPrice = '{{ getCurrencySymbol() }}' + new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2 }).format(currentPrice);
                        const fmtOriginal = '{{ getCurrencySymbol() }}' + new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2 }).format(originalPrice);

                        displayPriceEl.innerHTML = `<span class="original">${fmtOriginal}</span> ${fmtPrice}`;
                        instructionPriceEl.innerText = fmtPrice; // Update instruction text
                        msgDiv.innerHTML = `<span style="color: green;">${data.message}</span>`;
                        
                        couponInput.disabled = true;
                        applyBtn.style.display = 'none';
                        removeBtn.style.display = 'block';
                    } else {
                        msgDiv.innerHTML = `<span style="color: #D32F2F;">${data.message}</span>`;
                    }
                })
                .catch(() => {
                    applyBtn.disabled = false;
                    applyBtn.innerText = 'Apply';
                    msgDiv.innerHTML = `<span style="color: #D32F2F;">Error checking coupon.</span>`;
                });
        });

        removeBtn.addEventListener('click', function() {
            appliedCoupon = null;
            currentPrice = originalPrice;
            const fmtOriginal = '{{ getCurrencySymbol() }}' + new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2 }).format(originalPrice);
            
            displayPriceEl.innerText = fmtOriginal;
            instructionPriceEl.innerText = fmtOriginal;
            msgDiv.innerHTML = '';
            
            couponInput.value = '';
            couponInput.disabled = false;
            applyBtn.style.display = 'block';
            removeBtn.style.display = 'none';
        });

        // Form Submission
        const form = document.getElementById('complete-payment-form');
        if(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('complete-btn');
                const btnText = document.getElementById('btn-text');
                const spinner = document.getElementById('spinner');

                btn.disabled = true;
                btnText.style.display = 'none';
                spinner.style.display = 'block';

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        course_id: {{ $course->id }},
                        user_id: {{ auth()->user()->id }},
                        coupon_code: appliedCoupon
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        clearInterval(countdown);
                        alert('Order placed successfully! Verification may take up to 24 hours.');
                        window.location.href = "{{ route('course.details', ['id' => $course->id]) }}";
                    } else {
                        alert(data.message || 'Something went wrong.');
                        btn.disabled = false;
                        btnText.style.display = 'block';
                        spinner.style.display = 'none';
                    }
                })
                .catch(() => {
                    alert('An error occurred.');
                    btn.disabled = false;
                    btnText.style.display = 'block';
                    spinner.style.display = 'none';
                });
            });
        }

        // Prevent Accidental Leave
        window.addEventListener('beforeunload', function(e) {
            if (timeLeft > 0 && !document.getElementById('expired-overlay').style.display) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // BFCache Fix
        window.addEventListener('pageshow', function() {
            const btn = document.getElementById('apply-coupon-btn');
            if(btn && !btn.disabled) {
                btn.disabled = false;
                btn.innerText = 'Apply';
            }
        });
    </script>
</body>
</html>
