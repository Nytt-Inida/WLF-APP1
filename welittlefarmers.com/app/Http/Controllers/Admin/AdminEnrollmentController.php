<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentInquiry;
use App\Models\Course;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminEnrollmentController extends Controller
{
    // Display all enrollment inquiries
    public function index()
    {
        $inquiries = EnrollmentInquiry::with(['course', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_inquiries' => EnrollmentInquiry::count(),
            'pending' => EnrollmentInquiry::where('status', 'pending')->count(),
            'contacted' => EnrollmentInquiry::where('status', 'contacted')->count(),
            'enrolled' => EnrollmentInquiry::where('status', 'enrolled')->count(),
        ];

        return view('admin.enrollments.index', compact('inquiries', 'stats'));
    }

    // Show single inquiry details
    public function show(EnrollmentInquiry $enrollment)
    {
        $enrollment->load(['course', 'user', 'contactedByAdmin']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    // Update inquiry status
    public function updateStatus(Request $request, EnrollmentInquiry $enrollment)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,enrolled,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $adminId = Auth::guard('admin')->id();

        if ($request->status === 'contacted' && $enrollment->status === 'pending') {
            $enrollment->markAsContacted($adminId, $request->admin_notes);
        } elseif ($request->status === 'enrolled') {
            $enrollment->markAsEnrolled($request->admin_notes);
        } elseif ($request->status === 'rejected') {
            $enrollment->markAsRejected($request->admin_notes);
        } else {
            $enrollment->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
            ]);
        }

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    // Add notes to inquiry
    public function addNotes(Request $request, EnrollmentInquiry $enrollment)
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $enrollment->update([
            'admin_notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Notes added successfully!');
    }

    // Delete inquiry
    public function destroy(EnrollmentInquiry $enrollment)
    {
        $studentName = $enrollment->name;
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', "Enrollment inquiry for '{$studentName}' has been deleted.");
    }

    // Export to CSV
    public function export()
    {
        $inquiries = EnrollmentInquiry::with(['course', 'user'])->get();

        $filename = 'enrollment_inquiries_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // CSV Headers
        fputcsv($output, [
            'ID',
            'Student Name',
            'Email',
            'Phone',
            'Course',
            'Status',
            'Created At',
            'Contacted At',
            'Notes'
        ]);

        // CSV Data
        foreach ($inquiries as $inquiry) {
            fputcsv($output, [
                $inquiry->id,
                $inquiry->name,
                $inquiry->email,
                $inquiry->phone ?? 'N/A',
                $inquiry->course->title ?? 'N/A',
                ucfirst($inquiry->status),
                $inquiry->created_at->format('Y-m-d H:i:s'),
                $inquiry->contacted_at ? $inquiry->contacted_at->format('Y-m-d H:i:s') : 'Not contacted',
                $inquiry->admin_notes ?? 'N/A',
            ]);
        }

        fclose($output);
        exit;
    }

    // Filter by status
    public function filterByStatus($status)
    {
        $validStatuses = ['pending', 'contacted', 'enrolled', 'rejected'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->route('admin.enrollments.index');
        }

        $inquiries = EnrollmentInquiry::with(['course', 'user'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_inquiries' => EnrollmentInquiry::count(),
            'pending' => EnrollmentInquiry::where('status', 'pending')->count(),
            'contacted' => EnrollmentInquiry::where('status', 'contacted')->count(),
            'enrolled' => EnrollmentInquiry::where('status', 'enrolled')->count(),
        ];

        return view('admin.enrollments.index', compact('inquiries', 'stats', 'status'));
    }

    // Show remainder page (users who haven't enrolled)
    public function reminderPage()
    {
        // Include all users who have not made any enrollment inquiries,
        // regardless of whether their email is verified.
        $baseQuery = User::whereDoesntHave('enrollmentInquiries');

        $usersWithoutInquiries = $baseQuery
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $availableCourses = Course::all();

        $stats = [
            'users_without_inquiries' => (clone $baseQuery)->count(),
            'total_users' => User::count(),
            'users_with_inquiries' => User::has('enrollmentInquiries')->count(),
            'reminders_sent_today' => User::whereDate('last_reminder_sent_at', today())->count(),

            'not_contacted' => (clone $baseQuery)
                ->whereNull('last_reminder_sent_at')
                ->count(),

            'eligible_for_reminder' => (clone $baseQuery)
                ->where(function ($q) {
                    $q->whereNull('last_reminder_sent_at')
                        ->orWhere('last_reminder_sent_at', '<=', now()->subDays(7));
                })
                ->count(),

            // NEW: overdue (reminded before, and last reminder > 7 days ago)
            'overdue' => (clone $baseQuery)
                ->whereNotNull('last_reminder_sent_at')
                ->where('last_reminder_sent_at', '<=', now()->subDays(7))
                ->count(),
        ];

        return view('admin.enrollments.reminders', compact('usersWithoutInquiries', 'availableCourses', 'stats'));
    }



    /**
     * Send reminder to single user who hasn't inquired
     */
    public function sendReminderToNonInquirer(Request $request, User $user)
    {
        // Check if user has made any inquiries
        if ($user->enrollmentInquiries()->exists()) {
            return redirect()->back()->with('error', 'This user has already made an inquiry.');
        }

        // Check if reminder was sent recently
        if ($user->last_reminder_sent_at && $user->last_reminder_sent_at->gt(now()->subDays(7))) {
            $daysSince = $user->last_reminder_sent_at->diffInDays(now());
            return redirect()->back()->with('error', "A reminder was sent {$daysSince} days ago. Please wait at least 7 days.");
        }

        try {
            // Send reminder email
            $this->sendCourseExplorationEmail($user);

            // Update user's last reminder timestamp
            $user->update([
                'last_reminder_sent_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Course exploration reminder sent successfully to ' . $user->name);
        } catch (\Exception $e) {
            Log::error('Failed to send course exploration reminder', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to send reminder. Please try again.');
        }
    }

    /**
     * Send bulk reminders to users without inquiries
     */
    public function sendBulkRemindersToNonInquirers(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $successCount = 0;
        $failCount = 0;
        $skippedCount = 0;

        foreach ($request->user_ids as $userId) {
            $user = User::find($userId);

            if (!$user) {
                $failCount++;
                continue;
            }

            // Skip if user has inquiries
            if ($user->enrollmentInquiries()->exists()) {
                $skippedCount++;
                continue;
            }

            // Skip if reminder sent recently (within 7 days)
            if ($user->last_reminder_sent_at && $user->last_reminder_sent_at->gt(now()->subDays(7))) {
                $skippedCount++;
                continue;
            }

            try {
                $this->sendCourseExplorationEmail($user);

                $user->update([
                    'last_reminder_sent_at' => now(),
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
                Log::error('Failed to send bulk course exploration reminder', [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $message = "Sent {$successCount} reminder(s) successfully.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} skipped (recently contacted or has inquiries).";
        }
        if ($failCount > 0) {
            $message .= " {$failCount} failed.";
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Send course exploration email to user
     */
    private function sendCourseExplorationEmail($user)
    {
        $mailer = new PHPMailer(true);

        // SMTP Configuration
        $mailer->isSMTP();
        $mailer->Host = 'mailsvr.welittlefarmers.com';
        $mailer->Port = 587;
        $mailer->Username = 'register@welittlefarmers.com';
        $mailer->Password = 'TydjjnI8667dnHIKoiuJHN';
        $mailer->SMTPAuth = true;
        $mailer->SMTPSecure = 'tls';
        $mailer->SMTPAutoTLS = false;
        $mailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        $mailer->Timeout = 30;
        $mailer->SMTPKeepAlive = false;

        // Email settings
        $mailer->setFrom('register@welittlefarmers.com', 'We Little Farmer');
        $mailer->addAddress($user->email, $user->name);

        $mailer->isHTML(true);
        $mailer->Subject = ' Explore Our Courses - Start Your Learning Journey Today!';

        // Email body
        $mailer->Body = $this->getCourseExplorationEmailTemplate($user);
        $mailer->AltBody = $this->getCourseExplorationEmailPlainText($user);

        $mailer->send();

        return true;
    }

    /**
     * HTML email template for course exploration
     */
    private function getCourseExplorationEmailTemplate($user)
    {
        $coursesUrl = url('/course'); // Update with your actual courses page URL
        $whatsappNumber = config('app.whatsapp_number', '918590870849');
        $whatsappUrl = "https://wa.me/{$whatsappNumber}";

        // Get featured courses
        $featuredCourses = Course::limit(3)->get();

        $coursesHtml = '';
        foreach ($featuredCourses as $course) {
            $coursesHtml .= "
            <div style='background: #f8f9fa; padding: 20px; margin-bottom: 15px; border-radius: 8px; border-left: 4px solid #667eea;'>
                <h3 style='margin-top: 0; color: #333;'>{$course->title}</h3>
                <p style='color: #666; margin-bottom: 10px;'>" . Str::limit($course->description, 100) . "</p>
                <p style='margin: 0;'>
                    <span style='color: #667eea; font-weight: bold; font-size: 18px;'>
                        ₹" . number_format($course->price, 0) . ($course->price_usd ? " / $" . number_format($course->price_usd, 0) : "") . "
                    </span>
                </p>
            </div>
            ";
        }

        return "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;'>
                        <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
                            <h1 style='color: white; margin: 0;'>🚀 Start Your Learning Journey!</h1>
                        </div>
                        
                        <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px;'>
                            <p style='font-size: 16px; color: #333;'>Hi <strong>{$user->name}</strong>,</p>
                            
                            <p style='font-size: 16px; color: #666; line-height: 1.6;'>
                                We noticed you haven't explored our courses yet. We have amazing learning opportunities 
                                waiting for you that could transform your skills and career!
                            </p>
                            
                            <div style='background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 25px 0; border-radius: 5px;'>
                                <h3 style='margin-top: 0; color: #856404;'>✨ Why Learn With Us?</h3>
                                <ul style='color: #856404; margin-bottom: 0; padding-left: 20px;'>
                                    <li>Expert instructors with industry experience</li>
                                    <li>Hands-on practical projects</li>
                                    <li>Flexible learning at your own pace</li>
                                    <li>Certificate upon completion</li>
                                    <li>Lifetime access to course materials</li>
                                </ul>
                            </div>
                            
                            " . ($featuredCourses->count() > 0 ? "
                            <h3 style='color: #333; margin-top: 30px;'>🎯 Featured Courses</h3>
                            {$coursesHtml}
                            " : "") . "
                            
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='{$coursesUrl}' style='display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;'>
                                    🔍 Browse All Courses
                                </a>
                            </div>
                            
                            <hr style='border: 1px solid #eee; margin: 30px 0;'>
                            
                            <h3 style='color: #333;'>💡 What Our Students Say</h3>
                            <div style='background: #f0f7ff; padding: 20px; border-radius: 8px; font-style: italic; color: #666;'>
                                \"The courses are well-structured and the instructors are very supportive. 
                                I learned practical skills that I could apply immediately in my projects!\"
                                <br><br>
                                <strong style='color: #667eea;'>- Happy Student</strong>
                            </div>
                            
                            <div style='margin-top: 30px; padding: 20px; background: #e7f3ff; border-radius: 5px; text-align: center;'>
                                <h4 style='margin-top: 0; color: #0056b3;'>🎁 Special Offer for You!</h4>
                                <p style='margin: 10px 0; color: #666;'>
                                    Contact us today and get personalized course recommendations!
                                </p>
                                <a href='{$whatsappUrl}' style='display: inline-block; margin-top: 10px; padding: 12px 30px; background: #25D366; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                                    💬 Chat on WhatsApp
                                </a>
                            </div>
                            
                            <div style='margin-top: 30px; text-align: center;'>
                                <p style='color: #999; font-size: 14px;'>
                                    Have questions? We're here to help!<br>
                                    <a href='mailto:register@welittlefarmers.com' style='color: #667eea;'>register@welittlefarmers.com</a>
                                </p>
                            </div>
                        </div>
                        
                        <div style='text-align: center; margin-top: 20px; color: #999; font-size: 12px;'>
                            <p>We Little Farmer - Your Learning Partner</p>
                            <p>You're receiving this because you have an account with us.</p>
                        </div>
                    </div>
                    ";
    }

    /**
     * Plain text version for course exploration
     */
    private function getCourseExplorationEmailPlainText($user)
    {
        $coursesUrl = url('/courses');
        $whatsappNumber = config('app.whatsapp_number', '918590870849');

        return "
            Start Your Learning Journey!

            Hi {$user->name},

            We noticed you haven't explored our courses yet. We have amazing learning opportunities waiting for you!

            Why Learn With Us?
            - Expert instructors with industry experience
            - Hands-on practical projects
            - Flexible learning at your own pace
            - Certificate upon completion
            - Lifetime access to course materials

            Browse all courses: {$coursesUrl}

            Contact us on WhatsApp: https://wa.me/{$whatsappNumber}

            Have questions? Email us at register@welittlefarmers.com

            We Little Farmer - Your Learning Partner
                    ";
    }
}
