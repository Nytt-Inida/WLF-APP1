<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class VideoSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to video streaming routes
        if (!$request->is('video/stream/*') && !$request->is('video/generate-url/*')) {
            return $next($request);
        }

        // Check 1: Must be an authenticated AJAX/Fetch request OR direct video player
        if (!$request->expectsJson() && !$request->header('X-Requested-With')) {
            // Allow video player requests but block direct browser access
            $acceptHeader = $request->header('Accept');

            // If browser is expecting HTML (direct URL access), block it
            if (str_contains($acceptHeader, 'text/html')) {
                Log::warning('Direct browser access to video blocked', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl()
                ]);

                return response('Direct video access is not allowed. Please use the course player.', 403)
                    ->header('Content-Type', 'text/plain');
            }
        }

        // Check 2: Referer must be from your domain
        $referer = $request->header('referer');

        // CHANGED: Only check referer for streaming, not for URL generation
        if ($request->is('video/stream/*')) {
            if (!$referer) {
                Log::warning('Video stream request without referer blocked', [
                    'ip' => $request->ip(),
                    'user_id' => auth()->id() ?? 'guest'
                ]);

                return response('Videos can only be accessed through the course player.', 403)
                    ->header('Content-Type', 'text/plain');
            }
        }

        // Check 3: Authentication is NOT required here anymore
        // Controller will handle guest access for first lesson

        // Check 4: Rate limiting per IP (not just user)
        $identifier = auth()->check() ? 'user_' . auth()->id() : 'ip_' . $request->ip();
        $cacheKey = "video_requests_{$identifier}_" . now()->format('Y-m-d-H-i');

        $requestCount = cache()->get($cacheKey, 0);

        // Allow max 60 video requests per minute per user/IP
        if ($requestCount > 60) {
            Log::warning('Video request rate limit exceeded', [
                'identifier' => $identifier,
                'count' => $requestCount
            ]);

            return response('Too many requests. Please wait a moment.', 429)
                ->header('Content-Type', 'text/plain');
        }

        cache()->put($cacheKey, $requestCount + 1, now()->addMinutes(2));

        return $next($request);
    }
}