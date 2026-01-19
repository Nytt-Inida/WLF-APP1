<?php

namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


class Mailer
{
    public function sendOtp($to, $otp)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'mailsvr.welittlefarmers.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME', 'register@welittlefarmers.com');
            $mail->Password   = env('MAIL_PASSWORD', 'TydjjnI8667dnHIKoiuJHN');
            $mail->Port       = env('MAIL_PORT', 587);
            $mail->SMTPSecure = '';

            // Add this line to ignore SSL certificate verification for debugging
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            //Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'register@welittlefarmers.com'), env('MAIL_FROM_NAME', 'We Little Farmer'));
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);        // Set email format to HTML
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = 'Your OTP code is: ' . $otp . '<br>This OTP is valid for 10 minutes.';
            $mail->AltBody = 'Your OTP code is: ' . $otp . '\nThis OTP is valid for 10 minutes.';


            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error('Mail could not be sent.', ['error' => $mail->ErrorInfo, 'exception' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send enrollment notification to admin
     */
    public function sendEnrollmentNotification($adminEmail, $userName, $userEmail, $courseName, $coursePrice, $inquiretime)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'mailsvr.welittlefarmers.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME', 'register@welittlefarmers.com');
            $mail->Password   = env('MAIL_PASSWORD', 'TydjjnI8667dnHIKoiuJHN');
            $mail->Port       = env('MAIL_PORT', 25);
            $mail->SMTPSecure = '';

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'register@welittlefarmers.com'), env('MAIL_FROM_NAME', 'We Little Farmer'));
            $mail->addAddress($adminEmail);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Course Enrollment - ' . $courseName;

            $htmlBody = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;'>
            <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; text-align: center; border-radius: 10px 10px 0 0;'>
                <h1 style='color: white; margin: 0;'> New Enrollment Inquiry</h1>
            </div>
            
            <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px;'>
                <h2 style='color: #333; margin-top: 0;'>Course Details</h2>
                <p style='font-size: 16px;'><strong>Course:</strong> {$courseName}</p>
                
                <hr style='border: 1px solid #eee; margin: 20px 0;'>
                
                <h2 style='color: #333;'>Student Information</h2>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 10px 0; border-bottom: 1px solid #eee;'><strong>Name:</strong></td>
                        <td style='padding: 10px 0; border-bottom: 1px solid #eee;'>{$userName}</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0; border-bottom: 1px solid #eee;'><strong>Email:</strong></td>
                        <td style='padding: 10px 0; border-bottom: 1px solid #eee;'>{$userEmail}</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0;'><strong>Inquiry Date:</strong></td>
                        <td style='padding: 10px 0;'>{$inquiretime}</td>
                    </tr>
                </table>
                
                <div style='margin-top: 30px; text-align: center;'>
                    <a href='/admin/login' style='display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                        View in Dashboard
                    </a>
                </div>
                
                <div style='margin-top: 30px; padding: 15px; background: #f0f7ff; border-left: 4px solid #667eea; border-radius: 5px;'>
                    <p style='margin: 0; color: #666;'><strong>Action Required:</strong> Please contact this student within 24 hours to discuss enrollment details.</p>
                </div>
            </div>
            
            <div style='text-align: center; margin-top: 20px; color: #999; font-size: 12px;'>
                <p>This is an automated notification from We Little Farmer Admin Panel</p>
            </div>
        </div>";

            $mail->Body = $htmlBody;
            $mail->AltBody = "New enrollment for {$courseName} by {$userName} ({$userEmail})";

            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error('Enrollment notification could not be sent.', ['error' => $mail->ErrorInfo, 'exception' => $e->getMessage()]);
            return false;
        }
    }
}
