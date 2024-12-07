    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="BookingPage1.css">
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
    <a href="LocationNew.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
    <a href="BookingPage1.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
    <a href="addpetnew.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
    <a href="Serv.php"><i class="fa-solid fa-briefcase"></i> </i><br>Services</a>
    <a href="YourProfile.php"><i class="fa-solid fa-user"></i><br>Profile</a>
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
                <li><a href="HomeNew.php">Home</a></li>
                <li><a href="Serv.php">Services</a></li>
                <li><a href="LocationNew.php">Location</a></li>
                <li class="book_button"><a href="BookingPage1.php"><button>BOOK NOW!</button></a></li>
                <li class="dropdown">
                    <a href="#"><i class="fa-solid fa-user circle-icon"></i></a>
                    <div class="dropdown-content">
                    <a href="YourProfile.php">Profile</a>
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

        <section class="background_book">
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

                    <hr class="line2"></hr>

            <div class="calendar-container">
                <div class="calendar-header">
                    <span id="prevMonth">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                    <div id="calendar"></div>
                    <span id="nextMonth">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </div>
                <div class="slots" id="timeSlots2"><?php include ("timeSlots2.php")?>;</div>
            </div>


                <hr class="line3"></hr>
                <div class="booking_items">Payment Method</div>
                    <form id="payment_select0">
                        <select class="payment_select">
                            <option>Gcash</option>
                            <option>Cash</option>
                        </select>
                    </form>

                    <hr class="line4"></hr>
                <div class="fee">
                    <h3 class="amount" id="total">Total Amount: Php0.00</h3>
                </div>
            </div>
                </div>
            
                <a ><button class=" button_next">Next</button></a>
                <!-- Modal Structure -->
                <div id="popupModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p>Please complete all details to proceed.</p>
                    </div>
                </div>

        </section>

        <script>
        
        fetch('/capstone/get-booked-dates.php')
            .then(response => response.json())
            .then(data => {
                // Update salonBookedDates with the fetched data
                salonBookedDates = data;

                // Render the calendar
                renderCalendar();
            })
            .catch(error => console.error('Error fetching booked dates:', error));


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



        let total = 0;
        const selectedServices = new Set(); // To keep track of selected services
        let selectedcontainer = []; // To store selected services

        // Function to update the total amount
        function updateTotal(amount, button) {
            if (selectedServices.has(button.innerText)) {
                // If already selected, remove the amount
                total -= amount;
                selectedServices.delete(button.innerText);
                selectedcontainer = selectedcontainer.filter(service => service !== button.innerText);
                button.classList.remove('button_selected'); // Remove selected style
            } else {
                // If not selected, add the amount
                total += amount;
                selectedServices.add(button.innerText);
                selectedcontainer.push(button.innerText);
                button.classList.add('button_selected'); // Add selected style
            }
            document.getElementById("total").innerText = "Total Amount: ₱ " + total.toFixed(2);
        }

        // Event listener for the dropdown to select the service category
        document.addEventListener('DOMContentLoaded', function() {
            const salonSelect = document.getElementById('salon_select');
            const serviceForm = document.getElementById('id_pick_service');

            salonSelect.addEventListener('change', function() {
                const selectedSalon = this.value;
                serviceForm.innerHTML = ''; // Clear previous services

                if (services[selectedSalon]) {
                    services[selectedSalon].forEach(service => {
                        // Create a button for each service
                        const button = document.createElement('button');
                        // console.log("kahit ano ",service )
                        button.innerText = `${service.text} (₱${service.amount}.00)`;
                        button.type = 'button'; // Set button type to avoid default form behavior
                        button.onclick = (e) => {
                            e.preventDefault(); // Prevent form submission
                            updateTotal(service.amount, button); // Update total and toggle button
                        };
                        serviceForm.appendChild(button);
                        serviceForm.appendChild(document.createElement('br')); // Add line break
                    });
                }
            });
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

        <script>
            // Function to set the min attribute to today's date
            function setMinDate() {
                const datePicker = document.getElementById('date');
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-based
                const day = String(today.getDate()).padStart(2, '0');
                const todayDate = `${year}-${month}-${day}`;
                datePicker.setAttribute('min', todayDate);
            }

            // Call the function when the page loads
            window.onload = setMinDate;
        </script>


        <script src="Date.js"></script>
    </body>
    </html>