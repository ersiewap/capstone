<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="BookingPage2.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== true) {
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

// Get values from URL parameters
$selectedPet = $_GET['pet'] ?? '';
$selectedSalon = $_GET['salon'] ?? '';
$selectedDate = $_GET['date'] ?? '';
$selectedTime = $_GET['meeting-time'] ?? '';
$selectedPayment = $_GET['payment'] ?? '';
$userservices = isset($_GET['service']) ? $_GET['service'] : [];



$stmt = $conn->prepare("SELECT petname FROM petinfo WHERE petid = ?");
$stmt->bind_param("i", $selectedPet);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$petname = $row['petname'];

$stmt->close();

// Define the $salons array
$salons = array(
    array('salonid' => 1, 'shopname' => 'Vetter Health Animal Clinic and Livestock Consultancy'),
    array('salonid' => 2, 'shopname' => 'Davids Pet Grooming Salon'),
    array('salonid' => 3, 'shopname' => 'Kanjis Pet Grooming Services'),
    // Add more salons to the array as needed
);

$salonName = '';
foreach ($salons as $salon) {
    if ($salon['salonid'] == $selectedSalon) {
        $salonName = $salon['shopname'];
        break;
    }
}

// Fetch service names from the database
$serviceNames = [];
if (!empty($userservices)) {
$serviceIds =($userservices);
$sql = "SELECT servicename FROM services WHERE serviceid IN ($serviceIds)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $serviceNames[] = htmlspecialchars($row['servicename']);
    }
}
}
$serviceNames = implode(',',$serviceNames);

$conn->close();

?>

<body>
    <div  class="nav">
        <div>
            <a href="BookingPage1.html"><i class="fa-solid fa-arrow-left arrow_left"></i></a>
            Booking Summary
        </div>
    </div>
    <!-- content -->
    <div class="box">
        <div class="contents">Service</div>
        <div class="services1">
            <div class="contents1_service">Laboratory</div>
            <i onclick="this.parentElement.style.display='none'" class="fa-solid fa-x"></i>
        </div>
        
        <a href="#" id="add_services_button" class="add_services_button">
            <i class="fa-regular fa-plus plus_sign"></i>
            <div id="add_more">Add More Services</div>
            <div id="div1">
            </div>
        </a>
        
        <hr class="line1">
        <div class="container_date_time">
            <div class="date_div">
            <div class="contents">Date</div>
        
        </div>
        <div class=" time_div">
            <div class="contents">Time</div>
        </div>

        </div>
        
            
        <div class="contents3">  
        <div class="contents1_date">2024-06-04</div>
        <div class="contents1_time">14:00</div>
        </div>
        <hr class="line1">
        
        <div class="contents">Pet </div>
        <div class="contents1_pet">Molly  </div>
        <hr class="line1">
        <div class="contents">Pet Salon</div>
        <div class="contents1_salon">David’s Pet Salon</div>
        <hr class="line1">
        <div class="contents">Payment Method</div>
        <div class="contents1_payment">Cash</div>
        
        <hr class="line1">
        <div class="contents">Total Fee</div>
        <div class="contents1_fee">100.00</div>

    </div>

    <a  class="cd-popup-trigger book_button">Book</a>
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
    
    const services = {
            davids: [
            { value: 1, text: 'Full Groom Small'},
            { value: 2, text: 'Full Groom Medium' },
            { value: 3, text: 'Full Groom Large'},
            { value: 4, text: 'Full Groom Extra Large'},
            { value: 5, text: 'Bath and Blow Dryer Small' },
            { value: 6, text: 'Bath and Blow Dryer Medium'},
            { value: 7, text: 'Bath and Blow Dryer Large'},
            { value: 8, text: 'Bath and Blow Dryer Extra Large'},
            { value: 9, text: 'Heavy Dematting Small' },
            { value: 10, text: 'Heavy Dematting Medium'},
            { value: 11, text: 'Heavy Dematting Large'},
            { value: 12, text: 'Heavy Dematting Extra Large' },
            { value: 13, text: 'Nail Trimming Small-Medium' },
            { value: 14, text: 'Nail Trimming Large' },
            { value: 15, text: 'Teeth Brushing Small-Medium' },
            { value: 16, text: 'Teeth Brushing Large' },
            { value: 17, text: 'Ear Cleaning Small-Medium'},
            { value: 18, text: 'Ear Cleaning Large'},
            { value: 19, text: 'Face Trim'},
            { value: 20, text: 'Sanitary Trim Small-Medium' },
            { value: 21, text: 'Sanitary Trim Large'}
            ],
            vetter: [
                { value: 1, text: 'Vaccination' },
                { value: 2, text: 'Deworming' },
                { value: 3, text: 'Consultation' },
                { value: 4, text: 'Major and Minor Surgeries' },
                { value: 5, text: 'CBC and Blood Chemistry' },
                { value: 6, text: 'Ultrasound' },
                { value: 7, text: 'PCR Diagnostic Test' },
                { value: 8, text: 'Swab Test' },
                { value: 9, text: 'Rapid Test for Viral Infections' },
                { value: 10, text: 'Urinalysis' },
                { value: 11, text: 'X-ray' },
                { value: 12, text: 'Ovulation Test' },
            ],
            kanjis: [
            { value: 1, text: 'Standard Groom (Dogs Only) Small'},
            { value: 1, text: 'Standard Groom (Dogs Only) Medium'},
            { value: 1, text: 'Standard Groom (Dogs Only) Large' },
            { value: 1, text: 'Standard Groom (Dogs Only) Giant'},
            { value: 2, text: 'Full Groom (Dogs Only) Small'},
            { value: 2, text: 'Full Groom (Dogs Only) Medium' },
            { value: 2, text: 'Full Groom (Dogs Only) Large'},
            { value: 2, text: 'Full Groom (Dogs Only) Giant'},
            { value: 3, text: 'Full Groom (Cats Only) Small Kitten'},
            { value: 3, text: 'Full Groom (Cats Only) Adult'},
            { value: 4, text: 'Toothbrush' },
            { value: 5, text: 'Ear Cleaning'},
            { value: 6, text: 'Face Trim'},
            { value: 6, text: 'Nail Trim'},
            { value: 6, text: 'Tear Stain Clean'},
            { value: 6, text: 'Anal Sac Express' },
            { value: 6, text: 'Fur Trim' },
            { value: 6, text: 'Fur Style/ Shave' },
            { value: 6, text: 'Dematting'},
            { value: 6, text: 'Full Bath'},
            { value: 6, text: 'Med. Bath' }
            ]
        };

    
    document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const selectedSalon = urlParams.get('salon');

            if (selectedSalon) {
                populateServices(selectedSalon);
            }

            document.getElementById('add_services_button').addEventListener('click', function(event) {
                event.preventDefault();
                // document.getElementById('booking_items').style.display = 'block';
                const bookingItems = document.getElementById('booking_items');
    if (bookingItems.style.display === 'block') {
        bookingItems.style.display = 'none';
    } else {
        bookingItems.style.display = 'block';
    }
            });
        });
        
        function populateServices(salon) {
            const serviceForm = document.getElementById('id_pick_service');
            serviceForm.innerHTML = '';

            if (services[salon]) {
                services[salon].forEach(service => {
                    const label = document.createElement('label');
                    const input = document.createElement('input');
                    const span = document.createElement('span');

                    input.type = 'checkbox';
                    input.name = 'services';
                    input.value = service.value;
                    span.textContent = service.text;

                    label.appendChild(input);
                    label.appendChild(span);
                    serviceForm.appendChild(label);
                });
            }
        }

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

</script>
<script>
    function confirmBooking() {
    // Hide the booking text and icon
    document.querySelector('.popup-text').style.display = 'none';
    document.getElementById('booking-icon').style.display = 'none';
    // Show the confirmation text
    document.querySelector('.confirmation-text').style.display = 'block';
}

</script>
<script>
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