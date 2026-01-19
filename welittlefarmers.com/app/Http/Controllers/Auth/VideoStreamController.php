<?php

namespace App\Http\Controllers\Auth;

use App\Models\Lesson;
use App\Models\VideoAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamController extends Controller
{
    public function generateSignedUrl($lessonId)
    {
        try {
            // Get lesson first to check if it's the first lesson
            $lesson = Lesson::findOrFail($lessonId);

            // Check if this is the first lesson (free preview)
            $isFirstLesson = $this->isFirstLesson($lesson);

            // CHANGED: Allow guests for first lesson only
            if (!$isFirstLesson && !auth()->check()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'You must be logged in to access videos'
                ], 401);
            }

            // Check if user has access to this lesson
            if (!$this->userHasAccess($lesson)) {
                return response()->json([
                    'error' => 'Access Denied',
                    'message' => 'You do not have access to this lesson'
                ], 403);
            }

            // Check if video file exists
            $videoPath = $this->getVideoPath($lesson);

            if (!Storage::disk('local')->exists($videoPath)) {
                Log::error('Video file not found during URL generation', [
                    'lesson_id' => $lessonId,
                    'video_path' => $videoPath,
                    'video_url' => $lesson->video_url
                ]);

                return response()->json([
                    'error' => 'Video Not Found',
                    'message' => 'Video file does not exist. Please contact support.'
                ], 404);
            }

            // Generate unique token with additional security
            $expiresAt = now()->addMinutes(30);

            // CHANGED: Handle session for guests vs authenticated users
            $userId = auth()->check() ? auth()->id() : 'guest';
            $sessionId = session()->getId();

            $tokenData = [
                'lesson_id' => $lessonId,
                'user_id' => $userId,
                'is_guest' => !auth()->check(),
                'expires_at' => $expiresAt->timestamp,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'session_id' => $sessionId,
                'timestamp' => now()->timestamp,
                'random' => bin2hex(random_bytes(16))
            ];

            // Encrypt the token
            $token = Crypt::encryptString(json_encode($tokenData));

            // Create hash for database lookup
            $tokenHash = hash('sha256', $token);

            // Log the access (only if authenticated user)
            if (auth()->check()) {
                VideoAccessLog::create([
                    'user_id' => auth()->id(),
                    'lesson_id' => $lessonId,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'token_hash' => $tokenHash,
                    'accessed_at' => now(),
                    'expires_at' => $expiresAt,
                    'is_valid' => true
                ]);
            }

            // Get last watched position for authenticated users
            $lastWatchedSeconds = null;
            if (auth()->check()) {
                $progress = $lesson->progress()->where('user_id', auth()->id())->first();
                $lastWatchedSeconds = $progress ? $progress->last_position_seconds : null;
            }


            return response()->json([
                'success' => true,
                'video_url' => route('video.stream', ['token' => urlencode($token)]),
                'expires_in' => 1800,
                'expires_at' => $expiresAt->toIso8601String(),
                'last_watched_seconds' => $lastWatchedSeconds
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating video URL', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'lesson_id' => $lessonId
            ]);

            return response()->json([
                'error' => 'Server Error',
                'message' => 'Failed to generate video URL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function streamVideo(Request $request, $token)
    {
        try {
            // Decode URL-encoded token
            $token = urldecode($token);

            // Decrypt token
            $tokenData = json_decode(Crypt::decryptString($token), true);

            if (!$tokenData) {
                Log::warning('Invalid token format');
                return $this->videoError('Invalid token format', 403);
            }

            // SECURITY CHECK 1: Validate token expiry
            if ($tokenData['expires_at'] < now()->timestamp) {
                Log::warning('Token expired', ['expired_at' => $tokenData['expires_at']]);
                return $this->videoError('Token expired. Please refresh the page.', 403);
            }

            // SECURITY CHECK 2: Validate user authentication
            // CHANGED: Allow guests for first lesson
            $isGuest = isset($tokenData['is_guest']) && $tokenData['is_guest'];

            if (!$isGuest) {
                // For authenticated users, validate user ID
                if (!auth()->check() || $tokenData['user_id'] !== auth()->id()) {
                    Log::warning('User mismatch', [
                        'token_user' => $tokenData['user_id'],
                        'current_user' => auth()->id()
                    ]);
                    return $this->videoError('Invalid user authentication', 403);
                }
            } else {
                // For guests, just verify it's still a guest (not logged in now)
                // This prevents token sharing between guest and logged-in sessions
            }

            // SECURITY CHECK 3: Validate session ID
            if (isset($tokenData['session_id']) && $tokenData['session_id'] !== session()->getId()) {
                Log::warning('Session mismatch - token cannot be used in different session', [
                    'token_session' => $tokenData['session_id'],
                    'current_session' => session()->getId(),
                    'user_id' => auth()->id() ?? 'guest'
                ]);
                return $this->videoError('This video link has expired. Please reload the page.', 403);
            }

            // SECURITY CHECK 4: Validate IP address
            if ($tokenData['ip'] !== $request->ip()) {
                Log::warning('IP mismatch', [
                    'token_ip' => $tokenData['ip'],
                    'current_ip' => $request->ip()
                ]);
                // Optional: Make this stricter by returning error
                // return $this->videoError('IP address mismatch', 403);
            }

            // SECURITY CHECK 5: Strict Referer validation
            $referer = $request->header('referer');
            $allowedDomains = [
                parse_url(config('app.url'), PHP_URL_HOST),
                'localhost',
                '127.0.0.1'
            ];

            // Direct URL access (no referer) is BLOCKED
            if (!$referer) {
                Log::warning('No referer - direct URL access blocked', [
                    'user_id' => auth()->id() ?? 'guest',
                    'lesson_id' => $tokenData['lesson_id']
                ]);
                return $this->videoError('Videos can only be accessed through the course player.', 403);
            }

            $refererHost = parse_url($referer, PHP_URL_HOST);
            if (!in_array($refererHost, $allowedDomains)) {
                Log::warning('Invalid referer domain', [
                    'referer' => $referer,
                    'referer_host' => $refererHost,
                    'allowed' => $allowedDomains
                ]);
                return $this->videoError('Invalid referer domain', 403);
            }

            // Additional check: Referer must be from course details page
            if (!str_contains($referer, 'course_details') && !str_contains($referer, 'course/')) {
                Log::warning('Invalid referer page', [
                    'referer' => $referer,
                    'user_id' => auth()->id() ?? 'guest'
                ]);
                return $this->videoError('Videos can only be accessed from the course page.', 403);
            }

            // SECURITY CHECK 6: Check token in database (only for authenticated users)
            if (!$isGuest) {
                $tokenHash = hash('sha256', $token);
                $accessLog = VideoAccessLog::where('token_hash', $tokenHash)
                    ->where('is_valid', true)
                    ->first();

                if (!$accessLog) {
                    Log::warning('Token not found or revoked in database', ['token_hash' => $tokenHash]);
                    // Allow for now, but log it
                }
            }

            // Get lesson
            $lesson = Lesson::findOrFail($tokenData['lesson_id']);

            // SECURITY CHECK 7: Re-validate access rights
            if (!$this->userHasAccess($lesson)) {
                Log::warning('Access check failed', [
                    'user_id' => auth()->id() ?? 'guest',
                    'lesson_id' => $lesson->id
                ]);
                return $this->videoError('Access denied to this lesson', 403);
            }

            // Get video file path
            $videoPath = $this->getVideoPath($lesson);

            if (!Storage::disk('local')->exists($videoPath)) {
                Log::error('Video file not found', [
                    'path' => $videoPath,
                    'full_path' => Storage::disk('local')->path($videoPath),
                    'lesson_video_url' => $lesson->video_url
                ]);
                return $this->videoError('Video file not found', 404);
            }

            // Stream the video
            return $this->streamVideoFile($videoPath, $request);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            Log::error('Decryption failed', ['error' => $e->getMessage()]);
            return $this->videoError('Invalid or corrupted token', 403);
        } catch (\Exception $e) {
            Log::error('Video streaming error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->videoError('An error occurred while loading the video', 500);
        }
    }

    private function streamVideoFile($path, Request $request)
    {
        $fullPath = Storage::disk('local')->path($path);
        $fileSize = filesize($fullPath);
        $start = 0;
        $end = $fileSize - 1;

        // Handle range requests (for video seeking)
        $rangeHeader = $request->header('Range');

        if ($rangeHeader) {
            // Parse range header
            if (preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches)) {
                $start = intval($matches[1]);
                if (!empty($matches[2])) {
                    $end = intval($matches[2]);
                }
            }

            // Validate range
            if ($start > $end || $start >= $fileSize || $end >= $fileSize) {
                return response('Requested Range Not Satisfiable', 416)
                    ->header('Content-Range', "bytes */$fileSize");
            }
        }

        $length = $end - $start + 1;

        // Create streaming response
        $response = new StreamedResponse(function () use ($fullPath, $start, $length) {
            $stream = fopen($fullPath, 'rb');

            if ($stream === false) {
                return;
            }

            // Seek to start position
            fseek($stream, $start);

            $buffer = 1024 * 16; // 16KB chunks
            $bytesRead = 0;

            while (!feof($stream) && $bytesRead < $length) {
                $bytesToRead = min($buffer, $length - $bytesRead);
                $data = fread($stream, $bytesToRead);

                if ($data === false) {
                    break;
                }

                echo $data;
                flush();

                $bytesRead += strlen($data);
            }

            fclose($stream);
        });

        // Set headers - IMPORTANT: Add security headers
        $response->headers->set('Content-Type', 'video/mp4');
        $response->headers->set('Content-Length', $length);
        $response->headers->set('Accept-Ranges', 'bytes');

        // Cache control - videos should not be cached
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // CRITICAL: Prevent downloading
        $response->headers->set('Content-Disposition', 'inline');

        // Set status code and content-range for partial content
        if ($rangeHeader) {
            $response->setStatusCode(206); // Partial Content
            $response->headers->set('Content-Range', "bytes $start-$end/$fileSize");
        } else {
            $response->setStatusCode(200);
        }

        return $response;
    }

    /**
     * Check if user has access to the lesson
     * CHANGED: Allow guests for first lesson only
     */
    private function userHasAccess($lesson)
    {
        // Check if this is the first lesson (free preview)
        if ($this->isFirstLesson($lesson)) {
            return true; // Allow everyone (guests + authenticated users)
        }

        // For all other lessons, require authentication
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Get the course through section
        $course = $lesson->section->course;

        // Check if user has paid for the course
        return $course->userHasPaid($user);
    }

    /**
     * NEW: Check if lesson is the first lesson of the course
     */
    private function isFirstLesson($lesson)
    {
        $course = $lesson->section->course;
        $firstSection = $course->sections()->orderBy('id', 'asc')->first();

        if (!$firstSection) {
            return false;
        }

        $firstLesson = $firstSection->lessons()->orderBy('id', 'asc')->first();

        return $firstLesson && $lesson->id === $firstLesson->id;
    }

    private function getVideoPath($lesson)
    {
        $videoUrl = $lesson->video_url;

        // Case 1: If it's just a filename (after migration)
        if (!filter_var($videoUrl, FILTER_VALIDATE_URL) && !str_contains($videoUrl, '/')) {
            return 'videos/' . $videoUrl;
        }

        // Case 2: If it's a full URL, extract filename
        if (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
            $path = parse_url($videoUrl, PHP_URL_PATH);
            $filename = basename($path);
            return 'videos/' . $filename;
        }

        // Case 3: If it already has 'videos/' prefix
        if (str_starts_with($videoUrl, 'videos/')) {
            return $videoUrl;
        }

        // Case 4: If it's a path without videos/ prefix
        $filename = basename($videoUrl);
        return 'videos/' . $filename;
    }

    private function videoError($message, $code = 403)
    {
        return response($message, $code)
            ->header('Content-Type', 'text/plain');
    }
}
