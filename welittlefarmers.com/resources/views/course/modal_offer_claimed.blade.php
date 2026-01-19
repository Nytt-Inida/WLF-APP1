
    <!-- Offer Claimed Modal -->
    <div class="modal fade" id="offerClaimedModal" tabindex="-1" role="dialog" aria-labelledby="offerClaimedModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center p-4 border-0 shadow-lg" style="background: #fff; border-radius: 15px;">
                <div class="modal-body">
                    <div style="width: 80px; height: 80px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-check-circle" style="font-size: 40px; color: #4caf50;"></i>
                    </div>
                    <h3 style="font-family: 'Manrope', sans-serif; font-weight: 700; margin-bottom: 10px;">Offer Applied!</h3>
                    <p style="color: #666; font-size: 16px; margin-bottom: 25px;">
                        We've applied your special discount code <strong id="modal-coupon-code" style="color: #ffa03f;"></strong>.
                        <br>Complete your enrollment now to secure this price!
                    </p>
                    <button type="button" class="btn btn-primary w-100 py-3 rounded-pill" style="font-size: 18px; font-weight: 700;" onclick="proceedToPayment()">
                        Awesome, Proceed!
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let claimedCoupon = "";

        function proceedToPayment() {
            if(claimedCoupon) {
                // Redirect to payment page with coupon
                // Use URL object to safely append parameters regardless of existing query string
                try {
                    const baseUrl = "{{ route('payment.show', ['course_id' => 1]) }}";
                    const urlObj = new URL(baseUrl, window.location.origin);
                    urlObj.searchParams.set('coupon_code', claimedCoupon);
                    window.location.href = urlObj.toString();
                } catch(e) {
                    console.error("URL construction error", e);
                    // Fallback
                    const baseUrl = "{{ route('payment.show', ['course_id' => 1]) }}";
                    const separator = baseUrl.includes('?') ? '&' : '?';
                    window.location.href = baseUrl + separator + "coupon_code=" + claimedCoupon;
                }
            } else {
                $('#offerClaimedModal').modal('hide');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('show_claim_popup') === '1') {
                const coupon = urlParams.get('coupon');
                if(coupon) {
                    claimedCoupon = coupon;
                    document.getElementById('modal-coupon-code').innerText = coupon;
                    // Using jQuery for Bootstrap 4 modal compatibility if standard Bootstrap 5 instance fails
                     if (typeof $ !== 'undefined' && $.fn.modal) {
                         $('#offerClaimedModal').modal('show');
                     } else {
                         // Fallback for BS5
                         var myModal = new bootstrap.Modal(document.getElementById('offerClaimedModal'));
                         myModal.show();
                     }
                    
                    // Allow auto-close URL cleanup
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?coupon=" + coupon; 
                    window.history.replaceState({path:newUrl},'',newUrl);
                }
            }
        });
    </script>
