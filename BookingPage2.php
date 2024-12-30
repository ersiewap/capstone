<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="BookingPage2.css">
</head>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=sample.php');
    exit;
}

$userId = $_SESSION['ownerID']; // Ensure this is set when the user logs in
$userEmail = $_SESSION['owneremail'] ?? 'default@example.com'; // User's email from session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "capstone";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get values from POST parameters
$selectedPet = $_POST['pet_id'] ?? '';
$selectedSalon = $_POST['salon_id'] ?? '';
$selectedDate = $_POST['selected_date'] ?? '';
$selectedTime = $_POST['timeSlot'] ?? '';
$selectedPayment = $_POST['payment_method'] ?? '';
$userservices = isset($_POST['serviceid']) ? $_POST['serviceid'] : [];

// Validation: Check if all required fields are filled
if (empty($selectedPet) || empty($selectedSalon) || empty($selectedDate) || empty($selectedTime) || empty($selectedPayment) || empty($userservices)) {
    echo "<script>alert('Please complete the booking by selecting all required information.'); window.location.href='BookingPage1.php';</script>";
    exit; // Stop further execution
}

// Fetch pet name
$stmt = $conn->prepare("SELECT petname FROM petinfo WHERE petid = ?");
$stmt->bind_param("i", $selectedPet);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$petname = $row['petname'] ?? 'Unknown Pet';
$stmt->close();

// Fetch salon name
$stmt = $conn->prepare("SELECT shopname FROM salon WHERE salonid = ?");
$stmt->bind_param("i", $selectedSalon);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$salonName = $row['shopname'] ?? 'Unknown Salon';
$stmt->close();

// Calculate total amount for services
$totalAmount = 0;
$serviceNames = [];
if (!empty($userservices) && is_array($userservices)) {
    $serviceIdsString = implode(',', array_map('intval', $userservices));
    $sql = "SELECT servicename, price FROM services WHERE serviceid IN ($serviceIdsString)";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $serviceNames[] = htmlspecialchars($row['servicename']);
            $totalAmount += (float)$row['price'];
        }
    }
}
$serviceNamesString = implode(', ', $serviceNames);

// Display booking summary
?>
<body>
    <div class="summary">
        <h2>Booking Summary</h2>
        <p>Service: <?php echo $serviceNamesString; ?></p>
        <p>Date: <?php echo htmlspecialchars($selectedDate); ?></p>
        <p>Time: <?php echo htmlspecialchars($selectedTime); ?></p>
        <p>Pet: <?php echo htmlspecialchars($petname); ?></p>
        <p>Pet Salon: <?php echo htmlspecialchars($salonName); ?></p>
 <p>Payment Method: <?php echo htmlspecialchars($selectedPayment); ?></p>
        <p>Total Fee: <?php echo number_format($totalAmount, 2); ?></p>

        <button id="confirmBooking">Confirm Booking</button>
    </div>

    <script>
        document.getElementById('confirmBooking').addEventListener('click', function() {
            // Create a form to submit the booking details
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = ''; // Submit to the same page

            // Add hidden inputs for the booking details
            const inputs = [
                { name: 'pet_id', value: '<?php echo $selectedPet; ?>' },
                { name: 'salon_id', value: '<?php echo $selectedSalon; ?>' },
                { name: 'selected_date', value: '<?php echo $selectedDate; ?>' },
                { name: 'timeSlot', value: '<?php echo $selectedTime; ?>' },
                { name: 'payment_method', value: '<?php echo $selectedPayment; ?>' },
                { name: 'serviceid[]', value: '<?php echo implode(',', $userservices); ?>' },
                { name: 'confirm_booking', value: '1' } // Hidden input to indicate confirmation
            ];

            inputs.forEach(input => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = input.name;
                hiddenInput.value = input.value;
                form.appendChild(hiddenInput);
            });

            document.body.appendChild(form);
            form.submit(); // Submit the form
        });
    </script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    // Prepare email details
    $email = $userEmail; // Use the logged-in user's email
    $subject = "Booking Confirmation";
    $message = "
        Service: $serviceNamesString,
        Date: " . htmlspecialchars($selectedDate) . ",
        Time: " . htmlspecialchars($selectedTime) . ",
        Pet: " . htmlspecialchars($petname) . ",
        Pet Salon: " . htmlspecialchars($salonName) . ",
        Payment Method: " . htmlspecialchars($selectedPayment) . ",
        Total Fee: " . number_format($totalAmount, 2) . "
    ";

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

        // Recipients
        $mail->setFrom('alyssasamson0105@gmail.com', 'Booking System');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br($message); // Convert newlines to <br> tags for HTML email

        // Send email
        if ($mail->send()) {
            echo "<script>alert('Booking confirmed! An email has been sent to you.');</script>";

            // Insert booking details into the database
            $stmt = $conn->prepare("INSERT INTO book (ownerID, petid, salonid, serviceid, date, time, paymentmethod, paymentprice, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $ownerID = $userId; // Assuming you have the ownerID from the session
            $serviceIDs = implode(',', $userservices); // Convert service IDs array to a comma-separated string
            $status = 0; // Set status to 0 for ongoing

            // Update the bind_param to include 'd' for the paymentprice and 'i' for the status
            $stmt->bind_param("iiissssdi", $ownerID, $selectedPet, $selectedSalon, $serviceIDs, $selectedDate, $selectedTime, $selectedPayment, $totalAmount, $status);

            if ($stmt->execute()) {
            } else {
                echo "<script>alert('Error saving booking details: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
    }
}
?>
</body>
</html>