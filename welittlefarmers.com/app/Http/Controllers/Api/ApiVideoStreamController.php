<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\VideoAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiVideoStreamController extends Controller
{
    /**
     * Generate signed URL for mobile app (uses Sanctum token auth)
     */
    public function generateSignedUrl(Request $request, $lessonId)
    {
        try {
            // Get authenticated user from Sanctum (optional for first lesson preview)
            $user = $request->user();
            
            // Get lesson
            $lesson = Lesson::findOrFail($lessonId);
            
            // Check if this is the first lesson (free preview)
            $isFirstLesson = $this->isFirstLesson($lesson);
            
            // Allow guests for first lesson only
            if (!$isFirstLesson && !$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized',
                    'message' => 'You must be logged in to access videos. The first lesson is available as a free preview.'
                ], 401);
            }

            // Check if user has access to this lesson
            if (!$this->userHasAccess($lesson, $user)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Access Denied',
                    'message' => 'You do not have access to this lesson. Please purchase the course.'
                ], 403);
            }

            // Check if video file exists
            $videoPath = $this->getVideoPath($lesson);

            if (!Storage::disk('local')->exists($videoPath)) {
                Log::error('Video file not found during URL generation (API)', [
                    'lesson_id' => $lessonId,
                    'video_path' => $videoPath,
                    'video_url' => $lesson->video_url,
                    'user_id' => $user->id
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Video Not Found',
                    'message' => 'Video file does not exist. Please contact support.'
                ], 404);
            }

            // Generate unique token with additional security
            $expiresAt = now()->addMinutes(30);

            $tokenData = [
                'lesson_id' => $lessonId,
                'user_id' => $user ? $user->id : 'guest',
                'is_guest' => !$user,
                'expires_at' => $expiresAt->timestamp,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->timestamp,
                'random' => bin2hex(random_bytes(16)),
                'api_request' => true // Flag for API requests
            ];

            // Encrypt the token
            $token = Crypt::encryptString(json_encode($tokenData));

            // Create hash for database lookup
            $tokenHash = hash('sha256', $token);

            // Log the access (only for authenticated users)
            if ($user) {
                VideoAccessLog::create([
                    'user_id' => $user->id,
                    'lesson_id' => $lessonId,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'token_hash' => $tokenHash,
                    'accessed_at' => now(),
                    'expires_at' => $expiresAt,
                    'is_valid' => true
                ]);
            }

            // Get last watched position (only for authenticated users)
            $lastWatchedSeconds = null;
            if ($user) {
                $progress = $lesson->progress()->where('user_id', $user->id)->first();
                $lastWatchedSeconds = $progress ? $progress->last_position_seconds : null;
            }

            // Generate full URL for API (use api route instead of web route)
            $videoUrl = url('/api/video/stream/' . urlencode($token));

            return response()->json([
                'success' => true,
                'video_url' => $videoUrl,
                'expires_in' => 1800, // 30 minutes in seconds
                'expires_at' => $expiresAt->toIso8601String(),
                'last_watched_seconds' => $lastWatchedSeconds
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating video URL (API)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'lesson_id' => $lessonId,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Server Error',
                'message' => 'Failed to generate video URL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stream video for API requests (mobile app)
     */
    public function streamVideo(Request $request, $token)
    {
        try {
            // Decode URL-encoded token
            $token = urldecode($token);

            // Decrypt token
            $tokenData = json_decode(Crypt::decryptString($token), true);

            if (!$tokenData) {
                Log::warning('Invalid token format (API)');
                return $this->videoError('Invalid token format', 403);
            }

            // Validate token expiry
            if ($tokenData['expires_at'] < now()->timestamp) {
                Log::warning('Token expired (API)', ['expired_at' => $tokenData['expires_at']]);
                return $this->videoError('Token expired. Please refresh.', 403);
            }

            // For API requests, the token IS the authentication.
            // We cannot rely on $request->user() being present as video players often don't send auth headers.
            $isGuest = ($tokenData['user_id'] === 'guest' || !isset($tokenData['user_id']));
            
            // Validate guest access if applicable
            if ($isGuest) {
                // For guests, verify it's still the first lesson
                $lesson = Lesson::findOrFail($tokenData['lesson_id']);
                if (!$this->isFirstLesson($lesson)) {
                    return $this->videoError('Access denied. Please log in to continue.', 403);
                }
                $user = null;
            } else {
                // For authenticated users, we trust the signed token.
                // We fetch the user purely for the userHasAccess check (which expects a User object).
                // Note: We use the user_id embedded in the encrypted token.
                $user = \App\Models\User::find($tokenData['user_id']);
                
                if (!$user) {
                     return $this->videoError('User not found', 403);
                }
            }

            // Check token in database (only for authenticated users)
            if (!$isGuest) {
                $tokenHash = hash('sha256', $token);
                $accessLog = VideoAccessLog::where('token_hash', $tokenHash)
                    ->where('is_valid', true)
                    ->first();

                if (!$accessLog) {
                    Log::warning('Token not found in database (API)', ['token_hash' => $tokenHash]);
                    // Still allow but log it
                }
            }

            // Get lesson
            $lesson = Lesson::findOrFail($tokenData['lesson_id']);

            // Re-validate access rights
            $accessUser = $isGuest ? null : $user;
            if (!$this->userHasAccess($lesson, $accessUser)) {
                Log::warning('Access check failed (API)', [
                    'user_id' => $user?->id ?? 'guest',
                    'lesson_id' => $lesson->id
                ]);
                return $this->videoError('Access denied to this lesson', 403);
            }

            // Get video file path
            $videoPath = $this->getVideoPath($lesson);

            if (!Storage::disk('local')->exists($videoPath)) {
                Log::error('Video file not found (API)', [
                    'path' => $videoPath,
                    'lesson_video_url' => $lesson->video_url
                ]);
                return $this->videoError('Video file not found', 404);
            }

            // Stream the video
            return $this->streamVideoFile($videoPath, $request);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            Log::error('Decryption failed (API)', ['error' => $e->getMessage()]);
            return $this->videoError('Invalid or corrupted token', 403);
        } catch (\Exception $e) {
            Log::error('Video streaming error (API)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->videoError('An error occurred while loading the video', 500);
        }
    }

    /**
     * Check if user has access to the lesson
     */
    private function userHasAccess($lesson, $user = null)
    {
        // Check if this is the first lesson (free preview)
        if ($this->isFirstLesson($lesson)) {
            return true; // Allow first lesson for preview (guests + authenticated users)
        }

        // For all other lessons, require authentication and purchase
        if (!$user) {
            return false; // Must be logged in for other lessons
        }

        $course = $lesson->section->course;
        
        // Check if user has paid for the course
        return \Illuminate\Support\Facades\DB::table('user_progress')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('payment_status', 1)
            ->exists();
    }

    /**
     * Check if lesson is the first lesson of the course
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

    /**
     * Get video file path from lesson
     */
    private function getVideoPath($lesson)
    {
        $videoUrl = $lesson->video_url;

        // Case 1: If it's just a filename
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

    private function streamVideoFile($path, Request $request)
    {
        $fullPath = Storage::disk('local')->path($path);

        if (!file_exists($fullPath)) {
            return response('File not found', 404);
        }

        $fileSize = filesize($fullPath);
        $start = 0;
        $end = $fileSize - 1;

        // Handle range requests (CRITICAL for iOS AVPlayer)
        $rangeHeader = $request->header('Range');

        if ($rangeHeader) {
            // Parse range header (e.g., "bytes=0-1023" or "bytes=1024-")
            if (preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches)) {
                $start = intval($matches[1]);
                if (!empty($matches[2])) {
                    $end = intval($matches[2]);
                } else {
                    // If end is not specified, use file size - 1
                    $end = $fileSize - 1;
                }
            }

            // Validate range
            if ($start > $end || $start >= $fileSize || $end >= $fileSize) {
                return response('Requested Range Not Satisfiable', 416)
                    ->header('Content-Range', "bytes */$fileSize");
            }
        }

        $length = $end - $start + 1;

        // Create streaming response with proper range support
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

        // Set headers - CRITICAL for iOS: Must include Content-Length and Accept-Ranges
        $response->headers->set('Content-Type', 'video/mp4');
        $response->headers->set('Content-Length', $length);
        $response->headers->set('Accept-Ranges', 'bytes'); // CRITICAL: Tells client range requests are supported

        // Cache control - videos should not be cached
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Content-Disposition', 'inline');

        // CORS headers for mobile app
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Range');

        // Set status code and content-range for partial content (CRITICAL for iOS)
        if ($rangeHeader) {
            $response->setStatusCode(206); // Partial Content
            $response->headers->set('Content-Range', "bytes $start-$end/$fileSize");
        } else {
            $response->setStatusCode(200);
            // Content-Length is already set above (line 372) for full content
        }

        return $response;
    }

    /**
     * Return video error response
     */
    private function videoError($message, $code = 403)
    {
        return response($message, $code)
            ->header('Content-Type', 'text/plain');
    }
}

