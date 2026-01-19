<?php

namespace App\Services;

use App\Models\DiscountCode;
use App\Models\Referral;
use App\Models\ReferralSetting;
use App\Models\User;
use Illuminate\Support\Str;

class ReferralService
{
    /**
     * Validate a coupon or referral code.
     *
     * @param string $code
     * @param int $userId
     * @param float $coursePrice
     * @return array
     */
    public function validateCoupon($code, $userId, $coursePrice, $currency = 'INR')
    {
        $code = strtoupper(trim($code));
        $currency = strtoupper($currency);

        // 1. Check Discount Codes
        $discount = DiscountCode::where('code', $code)->first();
        if ($discount) {
            if (!$discount->is_active) {
                 return ['valid' => false, 'message' => 'Coupon is inactive.'];
            }
            if ($discount->expires_at && $discount->expires_at < now()) {
                 return ['valid' => false, 'message' => 'Coupon has expired.'];
            }
            if ($discount->max_usage > 0 && $discount->usage_count >= $discount->max_usage) {
                 return ['valid' => false, 'message' => 'Coupon usage limit reached.'];
            }

            // Dual Currency Min Order Check
            $minOrder = ($currency === 'USD') ? $discount->min_order_usd : $discount->min_order_inr;
            if ($minOrder > 0 && $coursePrice < $minOrder) {
                $symbol = ($currency === 'USD') ? '$' : '₹';
                return ['valid' => false, 'message' => "Minimum order amount is {$symbol}{$minOrder}."];
            }

            // Check if user-specific
            if ($discount->user_id && $discount->user_id != $userId) {
                return ['valid' => false, 'message' => 'This coupon is not valid for your account.'];
            }

            // Calculate
            $newPrice = $coursePrice;
            $discountAmount = 0;
            if ($discount->type == 'percent') {
                $discountAmount = $coursePrice * ($discount->value_inr / 100); // Percentage is same for both
            } else {
                // Fixed amount discount depends on currency
                $discountAmount = ($currency === 'USD') ? $discount->value_usd : $discount->value_inr;
            }
            
            $newPrice = max(0, $coursePrice - $discountAmount);
            $totalDiscount = $coursePrice - $newPrice;

            return [
                'valid' => true,
                'message' => 'Discount applied successfully.',
                'new_price' => round($newPrice, 2),
                'discount_amount' => round($totalDiscount, 2),
                'type' => 'discount',
                'object' => $discount,
                'currency' => $currency
            ];
        }

        // 2. Check Referral Codes
        $referrer = User::where('referral_code', $code)->first();
        if ($referrer) {
            if ($referrer->id == $userId) {
                return ['valid' => false, 'message' => 'You cannot use your own referral code.'];
            }

            // Get Settings
            if (!ReferralSetting::getValue('is_enabled', true)) {
                 return ['valid' => false, 'message' => 'Referral system is currently disabled.'];
            }

            $type = ReferralSetting::getValue('referee_discount_type', 'percent');
            $value = ReferralSetting::getValue('referee_discount_amount', 10);

            // Calculate
            $newPrice = $coursePrice;
            $discountAmount = 0;
            
            if ($type == 'percent') {
                 $discountAmount = $coursePrice * ($value / 100);
            } else {
                 // For referral fixed discounts, we currently only have one setting.
                 // We'll assume this value is for the primary currency or handled generally.
                 // TODO: In future, add dual-currency referral rewards if needed.
                 $discountAmount = $value;
            }
            
            $newPrice = max(0, $coursePrice - $discountAmount);
            $totalDiscount = $coursePrice - $newPrice;

            return [
                'valid' => true,
                'message' => "Referral discount applied!",
                'new_price' => round($newPrice, 2),
                'discount_amount' => round($totalDiscount, 2),
                'type' => 'referral',
                'object' => $referrer,
                'currency' => $currency
            ];
        }

        return ['valid' => false, 'message' => 'Invalid coupon code.'];
    }

    /**
     * Record usage of a coupon/referral after payment.
     * 
     * @param array $validationResult
     * @param int $userId
     * @return void
     */
    public function recordUsage($validationResult, $userId)
    {
        if (!$validationResult['valid']) return;

        if ($validationResult['type'] == 'discount') {
            $discount = $validationResult['object'];
            $discount->increment('usage_count');
            // If it was a one-time use coupon (like a reward), potentially deactivate it?
            // Currently our logic keeps it active until expiry or limit.
            // If max_usage is 1, it naturally stops working.
        } elseif ($validationResult['type'] == 'referral') {
            $referrer = $validationResult['object'];
            
            // Create Referral Record
            $referral = Referral::create([
                'referrer_id' => $referrer->id,
                'referred_user_id' => $userId,
                'status' => 'completed',
                'reward_type' => 'discount'
            ]);

            // Check if Referrer Reward is enabled and generate it
            $this->generateReferrerReward($referrer, $referral);
        }
    }

    /**
     * Generate reward for the referrer.
     */
    protected function generateReferrerReward($referrer, $referral)
    {
        $rewardType = ReferralSetting::getValue('referrer_reward_type', 'discount');
        if ($rewardType == 'discount') {
            $rewardValue = ReferralSetting::getValue('referrer_reward_value', 20);

            // Generate Reward Coupon for Referrer
            $rewardCode = strtoupper('REW-' . Str::random(8));
            DiscountCode::create([
                'code' => $rewardCode,
                'type' => 'percent',
                'value_inr' => $rewardValue,
                'value_usd' => $rewardValue,
                'user_id' => $referrer->id, // Assigned to referrer
                'max_usage' => 1, // Single use reward
                'is_active' => true
            ]);

            // Update Referral with reward details
            $referral->update([
                'reward_value' => $rewardValue,
                'reward_coupon_code' => $rewardCode
            ]);
        }
    }
}
