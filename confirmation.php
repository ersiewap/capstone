<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=sample.php');
    exit;
}

// Check if email details are set in session
if (isset($_SESSION['emailDetails'])) {
    $emailDetails = $_SESSION['emailDetails'];
    $email = $emailDetails['email'];
    $subject = $emailDetails['subject'];
    $message = $emailDetails['message'];

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'alyssasamson0105@gmail.com';  // Your Gmail username
        $mail->Password = 'cpuuhsbjwnpfznzp';  // Your Gmail app password or regular password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS
        $mail->Port = 587; // Use port 587 for STARTTLS

        //Recipients
        $mail->setFrom('alyssasamson0105@gmail.com', 'Booking System');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br($message); // Convert newlines to <br> tags for HTML email

        //Send email
        if ($mail->send()) {
            echo "Message has been sent successfully";
        } else {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Clear email details from session after sending
    unset($_SESSION['emailDetails']);
} else {
    echo "No email details found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="confirmation.css">
</head>
<body>
    <div class="confirmation-message">
        <h1>Booking Confirmation</h1>
        <p>Your booking has been confirmed. An email has been sent to you with the details.</p>
        <a href="HomeNew.php">Return to Home</a>
    </div>
</body>
</html>