<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["book"])) {
    // Get booking details from the form
    $name = $_POST["name"];
    $email = $_POST["email"];
    $booking_date = $_POST["booking_date"];
    $message = $_POST["message"];

    // Create the email content
    $email_subject = "Booking Confirmation for " . $name;
    $email_body = "
        <h2>Booking Details</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Booking Date:</strong> $booking_date</p>
        <p><strong>Message:</strong> $message</p>
        <p>Thank you for booking with us!</p>
    ";

    try {
        // Send the email using PHPMailer
        $mail = new PHPMailer(true);
        
        // Set mailer to use SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use your SMTP server if not Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'alyssasamson0105@gmail.com';  // Your Gmail username
        $mail->Password = 'cpuuhsbjwnpfznzp';  // Your Gmail app password or regular password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Sender and recipient settings
        $mail->setFrom('alyssasamson0105@gmail.com', 'Booking System');
        $mail->addAddress($email);  // Recipient is the customer's email

        // Email subject and body
        $mail->Subject = $email_subject;
        $mail->Body    = $email_body;
        $mail->isHTML(true);

        // Send email
        $mail->send();

        // Redirect with success message
        echo "<script>
                alert('Booking Confirmed! A confirmation email has been sent.');
                window.location.href = 'index.php';
            </script>";
    } catch (Exception $e) {
        echo "<script>
                alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');
                window.location.href = 'index.php';
            </script>";
    }
}
?>
