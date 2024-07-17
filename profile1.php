<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="profile1.css">
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

$sql = "SELECT b.bookid, p.petname, b.serviceid, b.date, b.time, b.paymentmethod, s.shopname
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
      $serviceResult = $conn->query("SELECT servicename  FROM services WHERE serviceid = $serviceId");
      if ($serviceRow = $serviceResult->fetch_assoc()) {
          $serviceNames[] = $serviceRow['servicename'];
      }
  }

  $row['servicenames'] = implode(', ', $serviceNames); // Convert array to comma-separated string
  $row['petname'] = $row['petname']; // Add petname key to the row array
  $bookings[] = $row;
}

$data = array();

if (isset($_POST['petname']) && !empty($_POST['petname'])) {
  $data['petname'] = $_POST['petname'];
}

if (isset($_POST['petsalon']) && !empty($_POST['petsalon'])) {
  $data['petsalon'] = $_POST['petsalon'];
}

if (isset($_POST['date']) && !empty($_POST['date'])) {
  $data['date'] = $_POST['date'];
}

if (isset($_POST['time']) && !empty($_POST['time'])) {
  $data['time'] = $_POST['time'];
}

if (isset($_POST['paymentmethod']) && !empty($_POST['paymentmethod'])) {
  $data['paymentmethod'] = $_POST['paymentmethod'];
}

if (isset($_POST['services']) && is_array($_POST['services'])) {
  $data['servicenames'] = implode(', ', $_POST['services']);
}

echo json_encode($data);

  
$stmt->close();
$conn->close();

echo '<script>';
echo 'console.log(' . json_encode($bookings) . ');'; // Logging $bookings to console
echo '</script>';

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
<!--Web Nav -->
<header class="header">  
  <div class="logo">
      <a href="Homepage.php">
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
     <div class="navtop">
      
      <img  class="logo_nav_top" src="logo-nav.png" >
     </div>
    <div class="your_profile">YOUR PROFILE</div> 
    <div class="profile-box">
        <div class="profile-name"><?php echo htmlspecialchars($_SESSION['ownerfname']); ?> <?php echo htmlspecialchars($_SESSION['ownerlname']); ?></div>

        <button class="edit-button">
          <a href="edit.php"><i class="fas fa-pencil-alt pencil"></i></a>
        </button>
    </div>
    <hr class="line1"></hr>

    <!-- Bookings -->
    <ul class="accordion-menu">
        <li>
            <div class="dropdownlink"><i class="fa-regular fa-calendar-days"></i> Bookings <i class="fa fa-chevron-down" aria-hidden="true"></i></div>
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
        <!-- History -->
        <li>
            <div class="dropdownlink"><i class="fa-solid fa-clock-rotate-left"></i> History <i class="fa fa-chevron-down" aria-hidden="true"></i></div>
            <ul class="submenuItems">
                <li><a href="#">You do not have a History</a></li>
                <div class="profile-box">
                    <div class="code" hidden></div>
                    <div class="profile-name"></div>
                    <div class="profile-name"></div>
                    <button class="View_button1" onclick="showPopup1()">View</button>
                </div>
            </ul>
        </li>

        <li>
            <div class="dropdownlink pets_profile">
                <a href="pet.php"><i class="fa-solid fa-paw"></i> Pets</a>
            </div>
        </li>
    </ul>

    <hr class="line2">
    <div class="Log_out1">
        <button class="Log_out_mobile" onclick="location.href='logout.php';">
            <i class="fa-solid fa-arrow-right-from-bracket log_out_icon"></i> Log Out
        </button>
    </div>

    <!-- Pop Up for Bookings -->
    <div class="overlay" id="overlay" onclick="hidePopup()">
        <div class="popup" id="popup">
            <button class="close-button" onclick="hidePopup()">&times;</button>
            <form action="">
                <div class="first">
                    <div class="first_1">
                        <p class="text">Pet Name:</p>
                        <p class="petname"></p>
                    </div>
                    <div class="first_2">
                        <p class="text">Pet Salon:</p>
                        <p class="petsalon"></p>
                    </div>
                </div>
                <div class="second">
                    <div class="second_1">
                        <p class="text">Service:</p>
                        <p class="services"></p>
                    </div>
                    <div class="second_2">
                        <p class="text">Payment Method:</p>
                        <p class="paymentmethod"></p>
                    </div>
                </div>
                <div class="third">
                    <div class="third_1">
                        <p class="text">Date:</p>
                        <p class="date1"></p>
                    </div>
                    <div class="third_2">
                        <p class="text">Time:</p>
                        <p class="time1"></p>
                    </div>
                </div>
            </form>
            <button class="cancel_button">Cancel Appointment</button>
        </div>
    </div>
    
    <!-- POP UP FOR History -->
    <div class="overlay1" id="overlay1" onclick="hidePopup1()">
    <div class="popup1" id="popup1">
      
    <button class="close-button1" onclick="hidePopup1()">&times;</button>
    <form action="" >
      <div class="first1">  
        <div class="first_11">
          <p class="text1" >Pet Name:</p>
          <p class="petname1">dsaef</p>
        </div> 
        <div class="first_1">
          <p class="text1">Pet Salon:</p>
          <p class="petsalon1">dsaads </p>
          </div>
      </div>
      <div class="second2" >
        <div class="second_12">
          <p class="text1">Service:</p>
          <p class="services2">casvas</p> 
        </div>
        <div class="second_22">
          <p class="text1">Payment Method:</p>
          <p class="paymentmethod2">dwqd </p>
          </div>
      </div>
      <div class="third3" >
        <div class="third_13">
          <p class="text1">Date:</p>
          <p class="date13">dsadqw</p>
        </div>
        <div class="third_23">
          <p class="text1">Time:</p>
          <p class="time13">sdada</p>
          </div>
      </div>
      <div class="fourth">
        <p class="text1">Status:</p>
        <p class=" status">das </p>
      </div>
    </form>
    </div>
    </div>
  
    
    
    <!-- <script>
        function showPopup(booking) {
            document.getElementById('popup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';

        // Update HTML elements with booking data
        document.querySelector('#popup.petname').textContent = booking.petname;
        document.querySelector('#popup.petsalon').textContent = booking.shopname;
        document.querySelector('#popup.services').textContent = booking.servicenames;
        document.querySelector('#popup.paymentmethod').textContent = booking.paymentmethod;
        document.querySelector('#popup.date1').textContent = booking.date;
        document.querySelector('#popup.time1').textContent = booking.time;
        }

        function hidePopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script> -->

    <script>
    function showPopup(bookingID) {
        // Send an AJAX request to booking2.php to fetch the data
        $.ajax({
            type: 'POST',
            url: 'booking2.php',
            data: { bookingID: bookingID },
            dataType: 'json',
            success: function(data) {
          // Update the text content of the HTML elements with the corresponding class names
          popup.querySelector('.petname').textContent = ['petname'];
          popup.querySelector('.petsalon').textContent = ['shopname'];
          popup.querySelector('.services').textContent = ['servicenames'];
          popup.querySelector('.paymentmethod').textContent = ['paymentmethod'];
          popup.querySelector('.date').textContent = ['date'];
          popup.querySelector('.time').textContent = ['time'];
        

                // Show the popup
                document.getElementById('popup').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            }
        });
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
  </script>
    <script>
      // Pop Up for Bookings
      function showPopup() {
          document.getElementById('popup').style.display = 'block';
          document.getElementById('overlay').style.display = 'block';
      }
  
      function hidePopup() {
          document.getElementById('popup').style.display = 'none';
          document.getElementById('overlay').style.display = 'none';
      }
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
        })
        </script>
</body>
</html>
