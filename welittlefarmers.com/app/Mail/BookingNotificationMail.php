<?php

namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class BookingNotificationMail
{
    public function sendBookingNotification($bookingData)
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
            $mail->Port       = env('MAIL_PORT', 25);
            $mail->SMTPSecure = '';

            // Ignore SSL certificate verification
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            //Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'register@welittlefarmers.com'), env('MAIL_FROM_NAME', 'We Little Farmer'));
            $mail->addAddress($bookingData['email'], $bookingData['name']);

            // Optional: Add CC to admin
            // $mail->addCC('admin@welittlefarmers.com');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Live Session Booking - We Little Farmer';
            $mail->Body    = $this->getBookingEmailBody($bookingData);
            $mail->AltBody = $this->getBookingEmailAltBody($bookingData);

            $mail->send();


            return true;
        } catch (Exception $e) {
            Log::error('Booking email could not be sent.', [
                'error' => $mail->ErrorInfo,
                'exception' => $e->getMessage(),
                'email' => $bookingData['email']
            ]);
            return false;
        }
    }

    protected function getBookingEmailBody($data)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Booking Confirmation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #f4f4f4;
                }
                .container {
                    background-color: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                .header {
                    background-color: #4CAF50;
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .content {
                    padding: 30px 20px;
                }
                .booking-details {
                    background-color: #f9f9f9;
                    padding: 20px;
                    margin: 20px 0;
                    border-left: 4px solid #4CAF50;
                    border-radius: 4px;
                }
                .detail-row {
                    padding: 10px 0;
                    border-bottom: 1px solid #eee;
                }
                .detail-row:last-child {
                    border-bottom: none;
                }
                .detail-label {
                    font-weight: bold;
                    color: #555;
                    display: inline-block;
                    width: 150px;
                }
                .detail-value {
                    color: #333;
                }
                .next-steps {
                    background-color: #e8f5e9;
                    padding: 15px;
                    margin: 20px 0;
                    border-radius: 4px;
                }
                .next-steps h3 {
                    color: #2e7d32;
                    margin-top: 0;
                }
                .next-steps ul {
                    margin: 10px 0;
                    padding-left: 20px;
                }
                .next-steps li {
                    margin: 8px 0;
                }
                .footer {
                    text-align: center;
                    padding: 20px;
                    color: #777;
                    font-size: 12px;
                    background-color: #f9f9f9;
                }
                .button {
                    display: inline-block;
                    padding: 12px 30px;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🌱 Booking Confirmation</h1>
                </div>
                
                <div class="content">
                    <h2>Dear ' . htmlspecialchars($data['name']) . ',</h2>
                    <p>Thank you for booking a live session with <strong>We Little Farmer</strong>!</p>
                    <p>We have received your booking request and will contact you soon with further details.</p>
                    
                    <div class="booking-details">
                        <h3 style="margin-top: 0; color: #4CAF50;">Your Booking Details</h3>
                        
                        <div class="detail-row">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value">' . htmlspecialchars($data['name']) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value">' . htmlspecialchars($data['email']) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">School:</span>
                            <span class="detail-value">' . htmlspecialchars($data['school_name']) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Age:</span>
                            <span class="detail-value">' . htmlspecialchars($data['age']) . ' years</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Preferred Date:</span>
                            <span class="detail-value">' . date('F j, Y', strtotime($data['date'])) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Course:</span>
                            <span class="detail-value">' . htmlspecialchars($data['course_name']) . '</span>
                        </div>
                    </div>
                    
                    <div class="next-steps">
                        <h3>📋 What happens next?</h3>
                        <ul>
                            <li>Our team will review your booking request</li>
                            <li>We will contact you within 24-48 hours</li>
                            <li>You will receive confirmation and further instructions</li>
                            <li>Payment details will be shared after confirmation</li>
                        </ul>
                    </div>
                    
                    <p>If you have any questions or need immediate assistance, feel free to reply to this email or contact us.</p>
                    
                    <p style="margin-top: 30px;">
                        Best regards,<br>
                        <strong>We Little Farmer Team</strong>
                    </p>
                </div>
                
                <div class="footer">
                    <p>This is an automated confirmation email.</p>
                    <p>&copy; ' . date('Y') . ' We Little Farmer. All rights reserved.</p>
                    <p style="margin-top: 10px;">
                        <a href="https://welittlefarmers.com" style="color: #4CAF50; text-decoration: none;">Visit our website</a>
                    </p>
                </div>
            </div>
        </body>
        </html>
        ';
    }

    protected function getBookingEmailAltBody($data)
    {
        return "Dear " . $data['name'] . ",\n\n" .
            "Thank you for booking a live session with We Little Farmer!\n\n" .
            "BOOKING DETAILS:\n" .
            "==================\n" .
            "Name: " . $data['name'] . "\n" .
            "Email: " . $data['email'] . "\n" .
            "School: " . $data['school_name'] . "\n" .
            "Age: " . $data['age'] . " years\n" .
            "Preferred Date: " . date('F j, Y', strtotime($data['date'])) . "\n" .
            "Course: " . $data['course_name'] . "\n\n" .
            "WHAT HAPPENS NEXT?\n" .
            "==================\n" .
            "- Our team will review your booking request\n" .
            "- We will contact you within 24-48 hours\n" .
            "- You will receive confirmation and further instructions\n" .
            "- Payment details will be shared after confirmation\n\n" .
            "If you have any questions, feel free to reply to this email.\n\n" .
            "Best regards,\n" .
            "We Little Farmer Team\n\n" .
            "© " . date('Y') . " We Little Farmer. All rights reserved.";
    }
}
