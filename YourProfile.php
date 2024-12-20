<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<link rel="stylesheet" href="YourProfilenew.css">
</head>

<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "capstone";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ownerID = $_SESSION['ownerID'];

$sql = "SELECT b.bookid, p.petname, b.serviceid, b.date, b.time, b.paymentmethod, s.shopname, b.status
        FROM book b
        JOIN petinfo p ON b.petid = p.petid
        JOIN salon s ON b.salonid = s.salonid
        WHERE b.ownerID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ownerID);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    // Split the service IDs and fetch service names
    $serviceIds = explode(',', $row['serviceid']);
    $serviceNames = [];
    foreach ($serviceIds as $serviceId) {
        $serviceId = intval($serviceId);
        $serviceResult = $conn->query("SELECT servicename FROM services WHERE serviceid = $serviceId");
        if ($serviceRow = $serviceResult->fetch_assoc()) {
            $serviceNames[] = $serviceRow['servicename'];
        }
    }

    // Convert array to comma-separated string
    $row['servicenames'] = implode(', ', $serviceNames);
    $row['petname'] = $row['petname']; // Add petname key to the row array

    // Set the status based on the appointment_status value
    $row['status'] = ($row['appointment_status'] == 0) ? "On Going" : "Cancelled";

    $bookings[] = $row;
}

$stmt->close();
$conn->close();

echo '<script>';
echo 'console.log(' . json_encode($bookings) . ');'; // Logging $bookings to console
echo '</script>';
?>

<body>
  
    <!-- Mobile Nav -->
<div class="navbar">
    <a href="Homenew.php" ><i class="fa-solid fa-house"></i><br>Home</a>
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
     <div class="navtop">
      
      <img  class="logo_nav_top" src="logo-nav.png" >
     </div>
    <div class="your_profile">YOUR PROFILE</div> 
    <div class="profile-box">
      <div class="profile-name"><?php echo htmlspecialchars($_SESSION['ownerfname']); ?> <?php echo htmlspecialchars($_SESSION['ownerlname']); ?></div>
        <button class="edit-button">
          <a href="EditPageNew.php"><i class="fas fa-pencil-alt pencil"></i></a>
        </button>
    </div>
    <hr class="line1"></hr>

<!-- Bookings -->
    <ul class="accordion-menu">
        <li>
          <div class="dropdownlink"><i class="fa-regular fa-calendar-days"></i></i> Appointments
            <i class="fa fa-chevron-down" aria-hidden="true"></i>
          </div>
          <ul class="submenuItems">
                <?php if (empty($bookings)) : ?>
                    <li><a href="#">You do not have active bookings</a></li>
                <?php else : ?>
                    <?php foreach ($bookings as $booking) : ?>
                        <li>
                            <div class="profile-box">
                                <div class="profile-name"><?php echo htmlspecialchars($booking['petname']); ?></div>
                                <div class="profile-name"><?php echo htmlspecialchars($booking['servicenames']); ?></div>
                                <button class="View_button" onclick="showPopup(<?php echo $booking['bookid']; ?>)">View</button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </li> 

        <li>
          <div class="dropdownlink" href="#"> <i class="fa-solid fa-credit-card"></i>Rewards
            
          </div>
          
        </li> 
        <li>
            <div class="dropdownlink pets_profile">
              <a href="addpetnew.php" ><i class="fa-solid fa-paw"></i>Pets</a>
            </div>
          </li>
          

      </ul>
    
    <hr class="line2">
    <div class="Log_out1">
        <button class="Log_out_mobile" onclick="location.href='logout.php';">
            <i class="fa-solid fa-arrow-right-from-bracket log_out _icon"></i> Log Out
        </button>
    </div>
    
    <!-- POP UP FOR History -->
    <div class="overlay1" id="overlay1" onclick="hidePopup1()"></div>
    <div class="popup1" id="popup1">
      
    <button class="close-button1" onclick="hidePopup1()">&times;</button>
    <form action="" >
      <div class="first1">  
        <div class="first_1">
            <p class="text1">Pet Name:</p>
            <input type="hidden" name="petname" value="">
            <p class="petname" id="petname"></p>
        </div>
        <div class="first_1">
          <p class="text1">Pet Salon:</p>
          <p class="petsalon1" id="petsalon"></p>
          </div>
      </div>
      <div class="second2" >
        <div class="second_12">
          <p class="text1">Service:</p>
          <p class="services2" id="services"></p> 
        </div>
        <div class="second_22">
          <p class="text1">Payment Method:</p>
          <p class="paymentmethod2" id="paymentmethod"></p>
          </div>
      </div>
      <div class="third3" >
        <div class="third_13">
          <p class="text1">Date:</p>
          <p class="date13" id="date1"></p>
        </div>
        <div class="third_23">
          <p class="text1">Time:</p>
          <p class="time13" id="time1">sdada</p>
          </div>
      </div>
      <div class="fourth" >
            <div class="fourth_13">
              <p class="text1">Status:</p> 
              <p id="statusText" class="status">On Going</p>
            </div>
            <div class="fourth_23">
              <p class="text1">Fee:</p>
              <p class="fee" id="fee">sdada</p>
              </div>
          </div>
          <button type="button" id="cancelButton" class="cancel_button" style="display:none;">Cancel Appointment</button>
      </div>
        </div>


    </form>


    <script>
    var bookings = <?php echo json_encode($bookings); ?>;
    function showPopup(bookingID) {
    for (var i = 0; i < bookings.length; i++) {
        if (bookings[i].bookid === bookingID) {
            var booking = bookings[i];
            document.getElementById('petname').textContent = booking.petname;
            document.getElementById('petsalon').textContent = booking.shopname;
            document.getElementById('services').textContent = booking.servicenames;
            document.getElementById('paymentmethod').textContent = booking.paymentmethod;
            document.getElementById('date1').textContent = booking.date;
            document.getElementById('time1').textContent = booking.time;
            document.getElementById('fee').textContent = booking.fee;
            document.getElementById('statusText').textContent = booking.status;
            
            document.getElementById('popup1').style.display = 'block';
            document.getElementById('overlay1').style.display = 'block';
        }
    }
}

    function hidePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
    </script>
    
    
    <script>
      // Pop Up for Bookings
      function showPopup1() {
          document.getElementById('popup1').style.display = 'block';
          document.getElementById('overlay1').style.display = 'block';
      }
  
      function hidePopup1() {
          document.getElementById('popup1').style.display = 'none';
          document.getElementById('overlay1').style.display = 'none';
      }

      // For Cancel Button
      // Function to show or hide the cancel button based on the current status
function updateCancelButtonVisibility() {
  const statusText = document.getElementById("statusText").textContent;
  const cancelButton = document.getElementById("cancelButton");

  // Only show the cancel button if the status is "On Going"
  if (statusText === "On Going") {
    cancelButton.style.display = "inline-block";
  } else {
    cancelButton.style.display = "none";
  }
}

// Run the function once to set the initial visibility of the cancel button
updateCancelButtonVisibility();

// Event listener for the cancel button
document.getElementById("cancelButton").addEventListener("click", function() {
  // Ask for confirmation
  const confirmCancel = confirm("Are you sure you want to cancel the appointment?");
  
  // If the user confirms, change the status to "Cancelled" and hide the cancel button
  if (confirmCancel) {
    document.getElementById("statusText").textContent = "Cancelled";
    
    // Disable and hide the cancel button
    this.disabled = true;
    this.textContent = "Appointment Cancelled";
    
    // Update the visibility of the button since the status has changed
    updateCancelButtonVisibility();
  }
});

  </script>
   
  
    
    
      


      <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
      <script>$(function() {
          var Accordion = function(el, multiple) {
            this.el = el || {};
            // more then one submenu open?
            this.multiple = multiple || false;
            
            var dropdownlink = this.el.find('.dropdownlink');
            dropdownlink.on('click',
                            { el: this.el, multiple: this.multiple },
                            this.dropdown);
          };
          
          Accordion.prototype.dropdown = function(e) {
            var $el = e.data.el,
                $this = $(this),
                //this is the ul.submenuItems
                $next = $this.next();
            
            $next.slideToggle();
            $this.parent().toggleClass('open');
            
            if(!e.data.multiple) {
              //show only one menu at the same time
              $el.find('.submenuItems').not($next).slideUp().parent().removeClass('open');
            }
          }
          
          var accordion = new Accordion($('.accordion-menu'), false);
        })</script>
</body>
</html>
