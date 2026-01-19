<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - {{ $course->title }}</title>
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=USD"></script>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e9f4d36b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }

        .header {
            background: #e9f4d36b;
            color: #1B212F;
            padding: 30px;
            text-align: center;
        }

        .course-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .price {
            font-size: 36px;
            font-weight: bold;
            margin-top: 10px;
        }

        .content {
            padding: 30px;
        }

        .payment-method-badge {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .payment-method-badge i {
            font-size: 24px;
            color: #2196f3;
        }

        .payment-method-badge div h4 {
            color: #1976d2;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .payment-method-badge div p {
            color: #424242;
            font-size: 14px;
            margin: 0;
        }

        #paypal-button-container {
            margin: 25px 0;
        }

        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 25px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #666;
        }

        .feature i {
            color: #4caf50;
            font-size: 18px;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }

        .secure-badge i {
            color: #4caf50;
            font-size: 20px;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 20px;
        }

        .loading-overlay.show {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            color: white;
            font-size: 18px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .back-link:hover {
            text-decoration: underline;
            transform: translateX(-5px);
        }

        @media (max-width: 768px) {
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="payment-container">
        <div class="header">
            <div class="course-title">{{ $course->title }}</div>
            <div class="price">{{ convertPrice($course->price, true, $course->price_usd) }}</div>
        </div>

        <div class="content">
            <div class="payment-method-badge">
                <i class="fas fa-globe"></i>
                <div>
                    <h4>International Payment</h4>
                    <p>Pay securely with PayPal or Credit/Debit Card</p>
                </div>
            </div>

            <!-- Coupon Section -->
            <div id="coupon-section" style="margin-bottom: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                <label for="coupon_code" style="display: block; margin-bottom: 8px; font-weight: 500;">Have a coupon or referral code?</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="coupon_code" class="form-control" placeholder="Enter code" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <button id="apply-coupon-btn" type="button" style="padding: 10px 20px; background: #2196f3; color: white; border: none; border-radius: 4px; cursor: pointer;">Apply</button>
                    <button id="remove-coupon-btn" type="button" style="padding: 10px 20px; background: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; display: none;">Remove</button>
                </div>
                <div id="coupon-message" style="margin-top: 8px; font-size: 14px;"></div>
            </div>

            <!-- PayPal Button Container -->
            <div id="paypal-button-container"></div>

            <div class="secure-badge" style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 15px; margin-bottom: 20px; color: #555; font-size: 12px; flex-wrap: wrap;">
                <span><i class="fas fa-lock" style="color: #4CAF50;"></i> Secure Payment</span>
                <span style="color: #ddd;">|</span>
                <span><i class="fas fa-shield-alt" style="color: #4CAF50;"></i> 30-Day Guarantee</span>
                <span style="color: #ddd;">|</span>
                <span><i class="fas fa-infinity" style="color: #4CAF50;"></i> Lifetime Access</span>
            </div>

            <a href="{{ route('course.details', ['id' => $course->id]) }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to course
            </a>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="spinner"></div>
        <div class="loading-text">Processing payment...</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Coupon Logic
            // Inject server-side values for currency handling
            const userCurrency = "{{ getAppCurrency() }}"; // 'INR' or 'USD'
            const currencySymbol = "{{ getCurrencySymbol() }}"; // '₹' or '$'
            
            // Original Price in localized currency
            const basePrice = {{ getAppCurrency() === 'USD' ? ($course->price_usd ?? 0) : $course->price }};
            
            // Helper to convert INR to User Currency
            function convertPriceFunc(priceInr) {
                // No division, use price as is
                return priceInr;
            }

            // Current displayed price (converted)
            let currentPrice = basePrice;
            let originalPriceDisplayed = currentPrice;
            let appliedCoupon = null;

            // Initial Render to ensure JS matches Server-Side (avoid any flash of content mismatch)
            // But server side renders convertPrice(...). JS should ideally not touch it until coupon applied.
            // However, we want to store the "displayed" value for reset.
            
            // Optional: If we want to guarantee they match, we could update innerHTML on load?
            // priceEl.innerText = formatCurrency(currentPrice); 

            const couponInput = document.getElementById('coupon_code');
            const applyBtn = document.getElementById('apply-coupon-btn');
            const removeBtn = document.getElementById('remove-coupon-btn');
            const msgDiv = document.getElementById('coupon-message');
            const priceEl = document.querySelector('.price');

            // Check for coupon in URL or passed from Session
            const urlParams = new URLSearchParams(window.location.search);
            const urlCoupon = urlParams.get('coupon_code'); // From URL
            const sessionCoupon = "{{ $referralCode ?? '' }}"; // From Session (Signup flow)
            

            const effectiveCoupon = urlCoupon || sessionCoupon;

            if (effectiveCoupon) {

                couponInput.value = effectiveCoupon;
                setTimeout(() => {
                    applyBtn.click();

                }, 1000); // Increased timeout slightly to be safe
            } else {

            }

            // Function to format price with symbol
            function formatCurrency(amount) {
                return currencySymbol + new Intl.NumberFormat('en-US', { 
                    minimumFractionDigits: userCurrency === 'INR' ? 0 : 0,
                    maximumFractionDigits: userCurrency === 'INR' ? 0 : 2
                }).format(amount);
            }

            // Apply Coupon Logic
            applyBtn.addEventListener('click', function() {
                const code = couponInput.value.trim();
                if(!code) return;

                applyBtn.disabled = true;
                applyBtn.innerText = 'Checking...';
                msgDiv.innerHTML = '';

                fetch(`/api/check-coupon?code=${code}&course_id={{ $course->id }}&user_id={{ auth()->id() }}&currency=${userCurrency}`)
                    .then(res => res.json())
                    .then(data => {
                        applyBtn.disabled = false;
                        applyBtn.innerText = 'Apply';

                        if(data.valid) {
                            // CHECK FOR 100% DISCOUNT
                            if (data.new_price <= 0) {
                                applyBtn.innerText = 'Claiming...';
                                applyBtn.disabled = true;
                                
                                // Process Free Claim Immediately
                                fetch("{{ route('payment.freeClaim') }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        course_id: {{ $course->id }},
                                        code: code
                                    })
                                })
                                .then(res => res.json())
                                .then(claimData => {
                                    if(claimData.success) {
                                        // SUCCESS: Redirect to course immediately
                                        msgDiv.innerHTML = `<span style="color: green;"><b>${claimData.message}</b> Redirecting...</span>`;
                                        setTimeout(() => {
                                            window.location.href = "{{ route('course.details', ['id' => $course->id]) }}";
                                        }, 1000);
                                    } else {
                                         msgDiv.innerHTML = `<span style="color: red;">${claimData.message}</span>`;
                                         applyBtn.disabled = false;
                                         applyBtn.innerText = 'Apply';
                                    }
                                })
                                .catch(err => {
                                    console.error(err);
                                    msgDiv.innerHTML = `<span style="color: red;">Error processing free claim.</span>`;
                                    applyBtn.disabled = false;
                                    applyBtn.innerText = 'Apply';
                                });
                                
                                return; // Stop further processing
                            }

                            // Normal Discount Logic
                            appliedCoupon = code;
                            
                            // The API returns 'new_price' in the DB currency (INR likely) or calculated based on passed price?
                            // Check PaymentController.php: validates against $course->price (base INR). So API returns INR.
                            // We need to convert the API result to user currency.
                            
                            // Update: logic check. If API returns new_price based on input price, we should pass converted price to API?
                            // ReferralService uses passed price. PaymentController passes $course->price (INR).
                            // So API returns INR. We must convert it.
                            
                            let newPriceInr = data.new_price;
                            currentPrice = convertPriceFunc(newPriceInr);
                            
                            // Update UI
                            // Ensure precise rounding for display matches formatCurrency
                            priceEl.innerHTML = `<span style="text-decoration: line-through; color: #777; font-size: 0.9em; margin-right: 10px;">${formatCurrency(originalPriceDisplayed)}</span> <span style="color: #4CAF50;">${formatCurrency(currentPrice)}</span>`;
                            msgDiv.innerHTML = `<span style="color: green;">${data.message}</span>`;
                            
                            couponInput.disabled = true;
                            applyBtn.style.display = 'none';
                            removeBtn.style.display = 'block';
                        } else {
                            msgDiv.innerHTML = `<span style="color: red;">${data.message}</span>`;
                        }
                    })
                    .catch(e => {
                        applyBtn.disabled = false;
                        applyBtn.innerText = 'Apply';
                        msgDiv.innerHTML = `<span style="color: red;">Error checking coupon.</span>`;
                    });
            });

            // Remove Coupon Logic
            removeBtn.addEventListener('click', function() {
                appliedCoupon = null;
                currentPrice = originalPriceDisplayed;
                
                // Reset UI
                priceEl.innerText = formatCurrency(originalPriceDisplayed); 
                msgDiv.innerHTML = '';
                
                couponInput.value = '';
                couponInput.disabled = false;
                applyBtn.style.display = 'block';
                removeBtn.style.display = 'none';
            });

            paypal.Buttons({
                // Step 1: Create the order
                // Step 1: Create the order (Server-Side)
                createOrder: function(data, actions) {
                    return fetch("{{ route('payment.createOrder') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            course_id: {{ $course->id }},
                             user_id: {{ auth()->user()->id }},
                            coupon_code: appliedCoupon // Send coupon code to calculate price server-side
                        })
                    })
                    .then(response => response.json())
                    .then(orderData => {
                         if(orderData.error) {
                             console.error("Server Error:", orderData.error);
                             alert("Could not initiate payment: " + orderData.error);
                             throw new Error(orderData.error);
                         }
                         return orderData.id; // Use the Order ID created by the server
                    });
                },

                // Step 2: Capture the order
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {

                        // Server-side payload
                        const payload = {
                            orderID: data.orderID,
                            payerID: details.payer.payer_id,
                            course_id: {{ $course->id }},
                            user_id: {{ auth()->user()->id }},
                            coupon_code: appliedCoupon // Send coupon code
                        };

                        // Send payload to your backend server
                        const overlay = document.getElementById('loading-overlay');
                        overlay.classList.add('show');

                        fetch("{{ route('payment.process') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "Accept": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Include Laravel's CSRF token
                                },
                                body: JSON.stringify(payload)
                            })
                            .then(async response => {
                                const result = await response.json();
                                overlay.classList.remove('show');

                                if (response.ok && result.success) {
                                    alert("Payment completed successfully.");
                                    // Redirect to course details page
                                    window.location.href =
                                        "{{ route('course.details', ['id' => $course->id]) }}";
                                } else {
                                    console.error("Payment processing failed:", result
                                        .error);
                                    alert("Payment failed: " + (result.error || "Please try again."));
                                }
                            })
                            .catch(error => {
                                overlay.classList.remove('show');
                                console.error("Network error:", error);
                                alert("An error occurred. Please try again.");
                            });
                    });
                },

                // Step 3: Handle errors
                onError: function(err) {
                    console.error("PayPal error:", err);
                    alert("Something went wrong with PayPal payment. Please try again.");
                }
            }).render('#paypal-button-container'); // Render the PayPal button
            
            // Reset button state on back-navigation (bfcache)
            window.addEventListener('pageshow', function(event) {
                const btn = document.getElementById('apply-coupon-btn');
                const msg = document.getElementById('coupon-message');
                if (btn) {
                    btn.disabled = false;
                    btn.innerText = 'Apply';
                }
                if (msg) {
                    msg.innerText = '';
                }
            });
        });
</script>
</body>

</html>
