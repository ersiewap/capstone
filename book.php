<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap">
<link rel="stylesheet" href="book.css">
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

// Retrieve the ownerID of the currently logged-in user
$ownerID = $_SESSION['ownerID'];

$stmt = $conn->prepare("SELECT petid, petname FROM petinfo WHERE ownerID = ?");
$stmt->bind_param("i", $ownerID);
$stmt->execute();
$result = $stmt->get_result();

// Create an array to store the pet information
$pets = array();


while ($row = $result->fetch_assoc()) {
    $pets[] = array('petid' => $row['petid'], 'petname' => $row['petname']);
}


$stmt = $conn->prepare("SELECT salonid, shopname FROM salon");
$stmt->execute();
$result = $stmt->get_result();

// Create an array to store the pet information
$salons = array();


while ($row = $result->fetch_assoc()) {
    $salons[] = array('salonid' => $row['salonid'], 'shopname' => $row['shopname']);
}

?>

<body>
    <!-- Mobile Nav -->
<div class="navbar">
    <a href="Homepage.php" ><i class="fa-solid fa-house"></i><br>Home</a>
    <a href="location.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
    <a href="book.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
    <a href="pet.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
    <a href="profile1.php"><i class="fa-solid fa-user"></i><br>Profile</a>
</div>

    <!-- Web Nav -->
    <header class="header">
        <div class="logo">
            <a href="#">
                <img class="logo"src="logo-nav.png" alt="logo" >
            </a>
        </div>
        <nav>
            <ul class="main-nav">
                <li><a href="Homepage.php">Home</a></li>
                <li><a href="location.php">Location</a></li>
                <li class="book_button"><a href="book.php"><button>Book Now!</button></a></li>
                <li class="dropdown">
                    <a href="#"><i class="fa-solid fa-user circle-icon"></i></a>
                    <div class="dropdown-content">
                    <a href="profile1.php">Profile</a>
                    <a href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    
    <!-- Content -->
    <section class="first">
        <div class="label1"><span style="color: #FFD700;">Woof!</span> Let’s Get Your <br> Appointment Started!</div>
        <img  class="booking_img" src="booking_img.png" alt="">
    </section>
    
    <div class="navtop">

        <img  class="logo_nav_top" src="logo-nav.png" >
    </div>
    
    <section class="background_book" >
        <div class=" book_word"> BOOK AN <br> APPOINTMENT</div>
        <div class=" booking_box" id="booking">
        <form method="POST" action="book1.php">
            <div class="booking_items">Pick a Pet</div>
                <div id="pet_select0">
                <select class="pet_select">
                    <?php foreach ($pets as $pet) { ?>
                        <option value="<?php echo $pet['petid']; ?>"><?php echo $pet['petname']; ?></option>
                    <?php } ?>
                </select>
                </div>    
                <div class="booking_items">Pick a Salon</div>
                <div id="salon_select0">
                    <select class="salon_select" id="salon_select">
                    <?php foreach ($salons as $salon) { ?>
                        <option value="<?php echo $salon['salonid']; ?>"><?php echo $salon['shopname']; ?></option>
                    <?php } ?>
                    </select>
                </div>
            
                <div class="booking_items">Pick a Service</div>
                <div id="id_pick_service">
                <?php foreach ($services[$selectedSalon] as $service) { ?>
                    <label>
                        <input type="checkbox" name="serviceid[]" value="<?php echo $service['value']; ?>">
                        <?php echo $service['text']; ?>
                    </label><br>
                <?php } ?>
                </div>
            
            

            <div class="booking_items">Pick a Date</div>
                <div id="date_select">
                    <input class="date_picker" type="date" id="date" name="date">
                </div>

            <div class="booking_items">Pick a Time</div>
                <div id="time_select">
                    <input class="time_picker" type="time" id="meeting-time" name="meeting-time">
                </div>
                
            <div class="booking_items">Payment Method</div>
                <div id="payment_select0">
                    <select class="payment_select">
                        <option>Gcash</option>
                        <option>Cash</option>
                    </select>
                </div>
                
            </div>
            <!-- href="book1.html" -->
            <a ><button class=" button_next">Next</button></a>
            <!-- Modal Structure -->
            <div id="popupModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Please complete all details to proceed.</p>
                </div>
            </div>
        </form>
    </section>

    <script>
        const services = {

    1: [
        { value: 1, text: 'Online Consultation' },  
        { value: 2, text: 'Consultation' },
        { value: 3, text: 'Treatment' },
        { value: 4, text: 'Vaccine & Deworming' },
        { value: 5, text: 'Full Grooming' },
        { value: 6, text: 'Sanitary' },
        { value: 7, text: 'Face Trim' },
        { value: 8, text: 'Nail Trim' },
        { value: 9, text: 'Ear Cleaning' },
        { value: 10, text: 'Laboratory Test' },
        { value: 11, text: 'Surgeryy' },
        { value: 12, text: 'Confinement' },
        { value: 13, text: 'Boarding' },    
        { value: 14, text: 'Whelping Assistant' },
        ],
        
    2: [
        { value: 15, text: 'Full Groom' },
        { value: 16, text: 'Bath and Blow Dryer' },
        { value: 17, text: 'Heavy Dematting' },
        { value: 18, text: 'Nail Trimming' },
        { value: 19, text: 'Teeth Brushing' },
        { value: 20, text: 'Ear Cleaning' },
        { value: 21, text: 'Face Trim' },
        { value: 22, text: 'Sanitary Trim' }
    ],
    3: [
        { value: 23, text: 'Standard Groom (Dogs Only)' },
        { value: 24, text: 'Full Groom (Dogs Only)' },
        { value: 25, text: 'Full Groom (Cats Only)' },
        { value: 26, text: 'Teeth Brushing' },
        { value: 27, text: 'Ear Cleaning' },
        { value: 28, text: 'Face Trim' },
        { value: 29, text: 'Nail Trim' },
        { value: 30, text: 'Tear Stain Clean' },
        { value: 31, text: 'Anal Sac Express' },
        { value: 32, text: 'Fur Trim' },
        { value: 33, text: 'Fur Style Shave' },
        { value: 34, text: 'Dematting' },
        { value: 35, text: 'Full Bath' },
        { value: 36, text: 'Med Bath' }
    ]
};

document.getElementById('salon_select').addEventListener('change', function() {
    const selectedSalon = this.value;
    const serviceForm = document.getElementById('id_pick_service');
    serviceForm.innerHTML = '';

    if (services[selectedSalon]) {
        services[selectedSalon].forEach(service => {
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
});

// Trigger change event to load default services
document.getElementById('salon_select').dispatchEvent(new Event('change'));

const modal = document.getElementById("popupModal");
const closeModal = document.getElementsByClassName("close")[0];

document.querySelector('.button_next').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default form submission

    // Check if all required fields are filled
    const petSelect = document.querySelector('.pet_select').value;
    const salonSelect = document.querySelector('#salon_select').value;
    const servicesChecked = document.querySelectorAll('input[name="services"]:checked').length > 0;
    const dateSelected = document.querySelector('#date').value;
    const timeSelected = document.querySelector('#meeting-time').value;
    const paymentSelect = document.querySelector('.payment_select').value;

    var checkboxes = document.querySelectorAll('input[name="services"]:checked');

    // Initialize an array to store the selected values
    var selectedItems = [];

    // Loop through the checkboxes and get their values
    checkboxes.forEach(function(checkbox) {
        selectedItems.push(checkbox.value);
        });


    // Validate inputs
    if (!petSelect || !salonSelect || !servicesChecked || !dateSelected || !timeSelected || !paymentSelect || !servicesChecked) {
        modal.style.display = "block"; // Show the modal
    } else {
        // Proceed to the next page
        window.location.href = `book1.php?salon=${salonSelect}&pet=${petSelect}&date=${dateSelected}
        &meeting-time=${timeSelected}&payment=${paymentSelect}&service=${selectedItems}`;
    }
});

// Close the modal when the close button is clicked
closeModal.onclick = function() {
    modal.style.display = "none";
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}

    </script>

</body>
</html>