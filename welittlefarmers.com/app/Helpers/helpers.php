<?php

use Illuminate\Support\Facades\Log;

/**
 * Get user's currency based on IP location
 * 
 * @return string 'INR' or 'USD'
 */
if (!function_exists('getAppCurrency')) {
    function getAppCurrency()
    {
        $ip = request()->ip();

        // 1. Priority: URL Switch for testing
        if (request()->query('test_country') === 'US') {
            return 'USD';
        }

        // 2. Localhost Development Check (Always INR)
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168')) {
            return 'INR';
        }

        // 3. Session Cache (Speed - avoid calling API on every page load)
        if (session()->has('user_currency') && session('user_currency_ip') === $ip) {
            return session('user_currency');
        }

        // 4. Live IP Detection (ipwhois.app)
        try {
            $url = "http://ipwhois.app/json/{$ip}?objects=country_code";
            $response = @file_get_contents($url, false, stream_context_create(['http' => ['timeout' => 2]]));
            
            if ($response !== false) {
                $data = json_decode($response, true);
                $country = $data['countryCode'] ?? $data['country_code'] ?? 'IN';

                $currency = ($country === 'IN') ? 'INR' : 'USD';
                
                // Save to session for this IP (if session is available)
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session(['user_currency' => $currency, 'user_currency_ip' => $ip]);
                }
                
                return $currency;
            }
        } catch (\Exception $e) {
            // Log::error('IP detection failed: ' . $e->getMessage());
        }

        // 5. Default Fallback - Default to INR for Indian users
        // This ensures Indian users see ₹ symbol by default
        return 'INR';
    }
}

/**
 * Convert price to user's currency
 * 
 * @param float $priceInINR - Price in INR
 * @param bool $formatted - Return formatted string with symbol
 * @param float|null $priceInUSD - Optional price in USD
 * @return string|float
 */
if (!function_exists('convertPrice')) {
    function convertPrice($priceInINR, $formatted = true, $priceInUSD = null)
    {
        $currency = getAppCurrency();

        if ($currency === 'USD') {
            // Use priceInUSD if provided, otherwise fallback to INR (which might be wrong but better than zero)
            $usdVal = $priceInUSD !== null ? $priceInUSD : $priceInINR;
            return $formatted ? '$' . number_format($usdVal, 2) : $usdVal;
        }

        // Return INR
        return $formatted ? '₹' . number_format($priceInINR, 0) : $priceInINR;
    }
}

/**
 * Get currency symbol for current user
 * 
 * @return string '₹' or '$'
 */
if (!function_exists('getCurrencySymbol')) {
    function getCurrencySymbol()
    {
        return getAppCurrency() === 'USD' ? '$' : '₹';
    }
}

/**
 * Check if user is from India
 * 
 * @return bool
 */
if (!function_exists('isIndianUser')) {
    function isIndianUser()
    {
        return getAppCurrency() === 'INR';
    }
}
