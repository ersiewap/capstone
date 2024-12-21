<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="BookingPage2.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=sample.php');
    exit;
}

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
$selectedTime = $_POST['timeSlot'] ?? ''; // Retrieve the selected time
$selectedPayment = $_POST['payment_method'] ?? '';
$userservices = isset($_POST['serviceid']) ? $_POST['serviceid'] : [];
    
// Initialize total amount
$totalAmount = 0;

// Fetch pet name
$stmt = $conn->prepare("SELECT petname FROM petinfo WHERE petid = ?");
$stmt->bind_param("i", $selectedPet);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$petname = $row['petname'] ?? 'Unknown Pet';
$stmt->close();

// Define the $salons array
$salons = array(
    array('salonid' => 1, 'shopname' => 'Vetter Health Animal Clinic and Livestock Consultancy'),
    array('salonid' => 2, 'shopname' => 'Davids Pet Grooming Salon'),
    array('salonid' => 3, 'shopname' => 'Kanjis Pet Grooming Services'),
);

$salonName = '';
foreach ($salons as $salon) {
    if ($salon['salonid'] == $selectedSalon) {
        $salonName = $salon['shopname'];
        break;
    }
}

$serviceNames = [];
if (!empty($userservices) && is_array($userservices)) {
    $serviceIds = implode(',', array_map('intval', $userservices));
    $sql = "SELECT servicename, price FROM services WHERE serviceid IN ($serviceIds)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $serviceNames[] = htmlspecialchars($row['servicename']);
            $totalAmount += (float)$row['price']; // Add to total amount
        }
    }
}
$serviceNames = implode(', ', $serviceNames);

$conn->close();
?>

<body>
    <div class="nav">
        <div>
            <a href="BookingPage1.php"><i class="fa-solid fa-arrow-left arrow_left"></i></a>
            Booking Summary
        </div>
    </div>
    <!-- content -->
    <div class="box">
        <div class="contents">Service</div>
        <div class="services1">
            <div class="contents1_service"><?php echo $serviceNames; ?></div>
        </div>
        
        <hr class="line1">
        <div class="container_date_time">
            <div class="date_div">
                <div class="contents">Date</div>
                <div class="contents1_date"><?php echo htmlspecialchars($selectedDate); ?></div>
            </div>
            <div class="time_div">
                <div class="contents">Time</div>
                <div class="contents1_time"><?php echo htmlspecialchars($selectedTime); ?></div>
            </div>
        </div>
        
        <hr class="line1">
        
        <div class="contents">Pet</div>
        <div class="contents1_pet"><?php echo htmlspecialchars($petname); ?></div>
        <hr class="line1">
        <div class="contents">Pet Salon</div>
        <div class="contents1_salon"><?php echo htmlspecialchars($salonName); ?></div>
        <hr class="line1">
        <div class="contents">Payment Method</div>
        <div class="contents1_payment"><?php echo htmlspecialchars($selectedPayment); ?></div>
        
        <hr class="line1">
        <div class="contents">Total Fee</div>
        <div class="contents1_fee"><?php echo number_format($totalAmount, 2); ?></div>

    </div>

    <a class="cd-popup-trigger book_button">Book</a>
    <div class="cd-popup" role="alert">
        <div class="cd-popup-container">
            <i id="initial-icon" class="fa-solid fa-calendar-check"></i>
            <p class="popup-text">Are you sure you want to book an appointment?</p>
            <div id="confirmation-section" style="display: none;">
                <i class="fa-solid fa-circle-check"></i>
                <p class="confirmation-text">Booking Successful!</p>
            </div>
            <ul class="cd-buttons">
                <li><a href="#0" class="yes-button" onclick="confirmBooking()">Book</a></li>
                <li><a href="#0" class="no-button">Cancel</a></li>
            </ul>
            <a href="#0" class="cd-popup-close img-replace">Close</a>
        </div> <!-- cd-popup-container -->
    </div> <!-- cd-popup -->
    
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    jQuery(document).ready(function($){
        //open popup
        $('.cd-popup-trigger').on('click', function(event){
            event.preventDefault();
            $('.cd-popup').addClass('is-visible');
        });
        
        //close popup
        $('.cd-popup').on('click', function(event){
            if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
                event.preventDefault();
                $(this).removeClass('is-visible');
            }
        });
        
        //close popup when clicking the esc keyboard button
        $(document).keyup(function(event){
            if(event.which=='27'){
                $('.cd-popup').removeClass('is-visible');
            }
        });

        // show confirmation message when "Yes" button is clicked
        $('.yes-button').on('click', function(event){
            event.preventDefault();
            $('.popup-text').hide();
            $('.confirmation-text').show();
            $('.cd-buttons').hide();
        });
        
        $('.no-button').on('click', function(event){
            event.preventDefault();
            $('.cd-popup').removeClass('is-visible');
        });
    });

    function confirmBooking() {
        // Hide the initial booking text and icon
        document.querySelector('.popup-text').style.display = 'none';
        document.getElementById('initial-icon').style.display = 'none';
        // Show the confirmation section
        document.getElementById('confirmation-section').style.display = 'block';
    }
</script>

</body>
</html>