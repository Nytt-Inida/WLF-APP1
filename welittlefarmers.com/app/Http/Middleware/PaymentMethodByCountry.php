<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class PaymentMethodByCountry
{
    // HANDLE AN INCOMING REQUEST
    // REDIRECTS TO APPROPRIATE PAYMENT METHOD BASED ON USER'S COUNTRY

    public function handle(Request $request, Closure $next)
    {
        // GET COURSE ID FROM ROUTE PARAMETER
        $courseId = $request->route('course_id');

        // GET USER'S IP address
        $ip = $request->ip();

        if ($request->has('test_country')) {
            $countryCode = $request->get('test_country');
        } else {
            $countryCode = $this->getCountryCodeFromIp($ip);
        }



        // Indian users -> Manual QR Payment
        if ($countryCode == 'IN') {
            return redirect()->route('payment.manual', array_merge(['course_id' => $courseId], $request->query()));
        }

        // International users -> Paypal payment
        return $next($request);
    }


    // GET COUNTRY CODE FROM IP ADDRESS WITH CACHING
    protected function getCountryCodeFromIp($ip)
    {

        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168')) {
            // Default to US for local host, testing
            return env('DEFAULT_TEST_COUNTRY', 'IN');
        }

        // Cache the result for 24 hr to avoid repeated API Calls
        $cacheKey = "country_code_{$ip}";

        return Cache::remember($cacheKey, 86400, function () use ($ip) {
            try {

                // Try multiple free I[ geolocation APIs for reliability

                // ipowhois.app(free, no API key needed)
                $countryCode = $this->tryIpWhois($ip);
                if ($countryCode) return $countryCode;

                Log::warning('Could not detect country for IP', ['ip' => $ip]);
                return 'US';
            } catch (\Exception $e) {
                Log::error('IP error', [
                    'ip' => $ip,
                    'error' => $e->getMessage()
                ]);

                return 'US';
            }
        });
    }

    // ipwhois.app API
    private function tryIpWhois($ip)
    {

        try {
            $url = "http://ipwhois.app/json/{$ip}?objects=country_code";
            $response = @file_get_contents($url);

            if ($response) {
                $data = json_decode($response, true);
                return $data['country_code'] ?? null;
            }
        } catch (\Exception $e) {
            Log::warning('ipwhois.app failed', ['error' => $e->getMessage()]);
        }

        return null;
    }
}
