<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

function sendMembershipEmail($name, $email, $phone, $address, $upi_reference, $franchise_name)
{
    $adminEmail = "crm@bookmylabs.in"; // Admin email
    $labLogo = "cid:logo"; // Content-ID for the embedded image
    $labName = "BookMyLabs";
    $labSupportEmail = "support@bookmylabs.com";
    $labSupportPhone = "+91 12345 67890";

    // Email to Member
    $subjectMember = "Membership Confirmation - $labName Support";
    $bodyMember = getEmailTemplate($name, $email, $phone, $address, $upi_reference, $franchise_name, $labLogo, $labName, $labSupportEmail, $labSupportPhone, true);

    sendEmail($email, $subjectMember, $bodyMember);

    // Email to Admin
    $subjectAdmin = "New Membership Created - $labName Support";
    $bodyAdmin = getEmailTemplate($name, $email, $phone, $address, $upi_reference, $franchise_name, $labLogo, $labName, $labSupportEmail, $labSupportPhone, false);

    sendEmail($adminEmail, $subjectAdmin, $bodyAdmin);
}

function getEmailTemplate($name, $email, $phone, $address, $upi_reference, $franchise_name, $labLogo, $labName, $labSupportEmail, $labSupportPhone, $isMember)
{
    $greeting = $isMember ? "Dear $name," : "Admin,";
    $message = $isMember
        ? "Welcome to <strong>$labName</strong>! We are excited to have you as a member. Below are your membership details:"
        : "A new member has joined <strong>$labName</strong>. Here are the details of their membership:";

    return "
    <div style='max-width: 600px; margin: auto; font-family: Arial, sans-serif; border-radius: 10px; overflow: hidden; background: #ffffff; box-shadow: 0px 4px 10px rgba(0,0,0,0.1);'>
        
        <!-- Header -->
        <div style='background: #3498db; padding: 20px; text-align: center;'>
            <img src='$labLogo' alt='$labName Logo' style='max-width: 120px;'>
            <h2 style='color: #fff; margin-top: 10px;'>Membership Confirmation</h2>
        </div>

        <!-- Body -->
        <div style='padding: 20px; color: #333;'>
            <p style='font-size: 16px;'>$greeting</p>
            <p style='font-size: 14px; color: #555;'>$message</p>

            <table style='width: 100%; border-collapse: collapse; margin-top: 15px; background: #f9f9f9; border-radius: 5px;'>
                <tr><td style='padding: 10px; border-bottom: 1px solid #ddd; font-weight: bold; color: #2c3e50;'>Name:</td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>$name</td></tr>
                <tr><td style='padding: 10px; border-bottom: 1px solid #ddd; font-weight: bold; color: #2c3e50;'>Email:</td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>$email</td></tr>
                <tr><td style='padding: 10px; border-bottom: 1px solid #ddd; font-weight: bold; color: #2c3e50;'>Phone:</td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>$phone</td></tr>
                <tr><td style='padding: 10px; border-bottom: 1px solid #ddd; font-weight: bold; color: #2c3e50;'>Address:</td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>$address</td></tr>
                <tr><td style='padding: 10px; border-bottom: 1px solid #ddd; font-weight: bold; color: #2c3e50;'>UPI Reference ID:</td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>$upi_reference</td></tr>
                <tr><td style='padding: 10px; font-weight: bold; color: #2c3e50;'>Franchise Name:</td><td style='padding: 10px;'>$franchise_name</td></tr>
            </table>

            <p style='font-size: 14px; color: #555; margin-top: 20px; text-align: center;'>
                If you have any questions, contact us at 
                <a href='mailto:$labSupportEmail' style='color: #3498db; text-decoration: none; font-weight: bold;'>$labSupportEmail</a> 
                or call <strong>$labSupportPhone</strong>.
            </p>
        </div>

        <!-- Footer -->
        <div style='background: #2c3e50; padding: 15px; text-align: center; color: #fff; font-size: 13px;'>
            <p>Thank you for choosing <strong>$labName</strong>!</p>
        </div>

    </div>
    ";
}



// Function to Send Email
function sendEmail($to, $subject, $body)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'mail.bookmylabs.in'; // SMTP Server
        $mail->SMTPAuth = true;
        $mail->Username = 'crm@bookmylabs.in'; // Your email
        $mail->Password = 'bookmylabs@8517'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('crm@bookmylabs.in', 'BookMyLabs Support');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;

        // Embed the local image
        $mail->addEmbeddedImage('vendors/images/BOOK-MY-LAB.jpg', 'logo', 'BOOK-MY-LAB.jpg');

        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
    }
}
