<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PDF;

class AdminCertificateController extends Controller
{
    // Show users without certificates for a course
    public function index()
    {
        try {
            $courses = Course::all();
            $selectedCourse = request('course_id');

            $usersWithoutCerts = [];
            if ($selectedCourse) {
                $usersWithoutCerts = User::whereDoesntHave('certificates', function ($q) {
                    $q->where('course_id', request('course_id'));
                })->get();
            }

            // Safely load certificates with defensive eager loading
            $certificatesIssued = Certificate::query()
                ->with('user')
                ->with('course')
                ->with('issuedByAdmin')
                ->orderBy('issued_at', 'desc')
                ->paginate(20);

            return view('admin.certificates.index', compact(
                'courses',
                'usersWithoutCerts',
                'selectedCourse',
                'certificatesIssued'
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Certificate index error: ' . $e->getMessage());
            return view('admin.certificates.index', [
                'courses' => Course::all(),
                'usersWithoutCerts' => [],
                'selectedCourse' => null,
                'certificatesIssued' => collect([]),
                'error' => 'An error occurred loading certificates. Please check the database migration.'
            ]);
        }
    }

    // Issue certificate to user
    public function issueCertificate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Check if certificate already exists
        $existing = Certificate::where('user_id', $request->user_id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Certificate already issued to this user for this course.');
        }

        // Generate unique certificate number
        $certificateNumber = 'CERT-' . strtoupper(Str::random(10)) . '-' . now()->year;

        $certificate = Certificate::create([
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'certificate_number' => $certificateNumber,
            'issued_at' => now(),
            'issued_by_admin_id' => Auth::guard('admin')->id(),
            'remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('success', 'Certificate issued successfully to ' . $certificate->user->name);
    }

    // Revoke certificate
    public function revokeCertificate(Certificate $certificate)
    {
        $user = $certificate->user;
        $certificate->delete();

        return redirect()->back()->with('success', "Certificate revoked for {$user->name}");
    }

    // Get users without certificates for a specific course (AJAX)
    public function getUsersWithoutCertificate(Course $course)
    {
        $users = User::whereDoesntHave('certificates', function ($q) use ($course) {
            $q->where('course_id', $course->id);
        })->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    // Download certificate

    public function downloadCertificate(Certificate $certificate)
    {
        $dir = storage_path('certificates');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = "CERT-{$certificate->certificate_number}.pdf";
        $path = $dir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($path)) {
            // render a blade view (resources/views/admin/certificates/template.blade.php)
            $html = view('admin.certificates.template', compact('certificate'))->render();

            // generate PDF and save
            $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape');
            $pdf->save($path);
        }

        return response()->download($path, $filename);
    }
}
