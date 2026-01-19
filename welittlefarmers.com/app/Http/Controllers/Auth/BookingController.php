<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LiveCourse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\BookingNotificationMail;
use App\Mail\Mailer;
use App\Models\CourseEnrollment;
use App\Models\User;

class BookingController extends Controller
{
    protected $bookingMailer;

    public function __construct(BookingNotificationMail $bookingMailer)
    {
        $this->bookingMailer = $bookingMailer;
    }

    public function submitBooking(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Do login',
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
            $emailSent = $this->bookingMailer->sendBookingNotification($validatedData);

            if (!$emailSent) {
                // Email failed but we still commit the booking
                Log::warning('Booking saved but email failed to send', [
                    'email' => $validatedData['email'],
                    'course' => $validatedData['course_name']
                ]);
            }

            DB::commit();

            $live_course_id = 0;
            if ($validatedData['course_name'] == "AI in Agriculture for Kids | Smart Farming Live Course") {
                $live_course_id = 2;
            }

            if ($validatedData['course_name'] == "Little Farmers Academy - Online Farming Courses for Kids") {
                $live_course_id = 3;
            }


            // Create enrollement recored
            $alreadyenrolled = CourseEnrollment::where("course_id", $live_course_id)
                ->where('user_id', Auth::id())
                ->first();

            $user_details = User::where("email", $validatedData['email'])
                ->first();


            if ($alreadyenrolled) {
                $alreadyenrolled->update([
                    "enrollment_data" => now()
                ]);
            } else {

                $enrolled = CourseEnrollment::create(
                    [
                        "user_id" => $user_details->id,
                        "course_id" => $live_course_id,
                        'enrollment_date' => now(),
                        'payment_status' => 'pending',
                    ]
                );
            }

            // Send enrollment notifiaction to admin
            $mailer = new Mailer();
            $adminEmail = env('ADMIN_EMAIL', 'admin@welittlefarmers.com');
            $mailer->sendEnrollmentNotification(
                $adminEmail,
                $user_details->name,
                $user_details->email,
                $validatedData['course_name'] ?? 'Unknow course',
                $validatedData['price'] ?? 0,
                $inquiretime = now()->format('d M Y, h:i A')

            );



            return response()->json([
                'success' => true,
                'message' => 'Booking request submitted successfully! We will contact you soon.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Booking submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $validatedData['email'] ?? 'N/A'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
