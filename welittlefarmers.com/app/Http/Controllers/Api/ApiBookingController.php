<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LiveCourse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\BookingNotificationMail;
use App\Mail\Mailer;
use App\Models\CourseEnrollment;
use App\Models\User;

class ApiBookingController extends Controller
{
    protected $bookingMailer;

    public function __construct(BookingNotificationMail $bookingMailer)
    {
        $this->bookingMailer = $bookingMailer;
    }

    public function submitBooking(Request $request)
    {
        // API Authentication Check (Sanctum)
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Please login.',
            ], 401);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|email',
            'school_name' => 'required|string|max:225',
            'age' => 'required|integer|min:1|max:100',
            'date' => 'required|date',
            'course_name' => 'required|string|max:225',
        ]);

        // Add price to validatedData to avoid undefined index issues in Mailers or DB
        $validatedData['price'] = $request->input('price', 0);

         // Check if user already enrolled for this course_name
        $existingEnrollment = LiveCourse::where('email', $validatedData['email'])
            ->where('course_name', $validatedData['course_name'])
            ->first();

        if ($existingEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'You have already enrolled for this course.'
            ], 409);
        }

        DB::beginTransaction();
        try {
            // Save booking to database
            $booking = LiveCourse::create($validatedData);

            // Send notification email using BookingMailer
            try {
                $this->bookingMailer->sendBookingNotification($validatedData);
            } catch (\Exception $e) {
                Log::warning('Email sending failed (API)', ['error' => $e->getMessage()]);
            }

            $live_course_id = 0;
            if ($validatedData['course_name'] == "AI in Agriculture for Kids | Smart Farming Live Course") {
                $live_course_id = 2;
            }

            if ($validatedData['course_name'] == "Little Farmers Academy - Online Farming Courses for Kids") {
                $live_course_id = 3;
            }
            // Add Robotics mapping
             if (str_contains($validatedData['course_name'], "Robotics")) {
                // Future ID mapping
            }

            // Create enrollment record
            // Create enrollment record
             try {
                $alreadyenrolled = CourseEnrollment::where("course_id", $live_course_id)
                    ->where('user_id', $user->id) 
                    ->first();

                $user_details = User::where("email", $validatedData['email'])->first();
                
                if (!$user_details) {
                    $user_details = $user;
                }

                if ($alreadyenrolled) {
                    $alreadyenrolled->update([
                        "enrollment_data" => now()
                    ]);
                } else {
                    CourseEnrollment::create([
                        "user_id" => $user_details->id,
                        "course_id" => $live_course_id,
                        'enrollment_date' => now(),
                        'payment_status' => 'pending',
                    ]);
                }
            } catch (\Exception $e) {
                // Log the error but continue to commit the transaction
                // This happens if the live_course_id (e.g. 2 or 3) does not exist in the 'courses' table (FK violation)
                // We still want to save the 'LiveCourse' booking request.
                Log::warning('Course Enrollment failed during Live Booking (ignoring to save Booking)', [
                    'error' => $e->getMessage(),
                    'course_id' => $live_course_id
                ]);
            }
            
            DB::commit();

            // Send enrollment notification to admin
            try {
                $mailer = new Mailer();
                $adminEmail = env('ADMIN_EMAIL', 'admin@welittlefarmers.com');
                $mailer->sendEnrollmentNotification(
                    $adminEmail,
                    $user_details->name,
                    $user_details->email,
                    $validatedData['course_name'] ?? 'Unknown course',
                    $validatedData['price'], 
                    now()->format('d M Y, h:i A')
                );
            } catch (\Exception $e) {
                 Log::warning('Admin notification failed (API)', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking request submitted successfully! We will contact you soon.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Booking submission failed (API)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $validatedData['email'] ?? 'N/A'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(), // Temporarily exposed for debugging
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
