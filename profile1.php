<!DOCTYPE html>
<html>
<head>
<title>MIPS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/x-icon" href="favicon.ico">
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
  <a href="Services.php" ><i class="fa-solid fa-house"></i><br>Services</a>
  <a href="location.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
  <a href="book.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
  <a href="pet.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
  <a href="profile1.php"><i class="fa-solid fa-user"></i><br>Profile</a>
</div>
<!--Web Nav -->
<header class="header">
    <div class="logo">
        <a href="#">
            <img class="logo" src="logo.png" alt="logo">
        </a>
    </div>
    <nav class="nav">
        <ul class="main-nav">
            <li><a href="Homepage.php">Home</a></li>
            <li><a href="Services.php">Services</a></li>
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
        <li>
            <div class="dropdownlink pets_profile">
                <a href="pet.php"><i class="fa-solid fa-paw"></i> Pets</a>
            </div>
        </li>
    </ul>

    <hr class="line2">
    <div class="Log_out1">
        <button class="Log_out_mobile" onclick="location.href='logout.php';">
            <i class="fa-solid fa-arrow-right-from-bracket log_out _icon"></i> Log Out
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
                        <input type="hidden" name="petname" value="">
                        <p class="petname" id="petname"></p>
                    </div>
                    <div class="first_2">
                        <p class="text">Pet Salon:</p>
                        <p class="petsalon" id="petsalon"></p>
                    </div>
                </div>
                <div class="second">
                    <div class="second_1">
                        <p class="text">Service:</p>
                        <p class="services" id="services"></p>
                    </div>
                    <div class="second_2">
                        <p class="text">Payment Method:</p>
                        <p class="paymentmethod" id="paymentmethod"></p>
                    </div>
                </div>
                <div class="third">
                    <div class="third_1">
                        <p class="text">Date:</p>
                        <p class="date1" id="date1"></p>
                    </div>
                    <div class="third_2">
                        <p class="text">Time:</p>
                        <p class="time1" id="time1"></p>
                    </div>
                </div>
            </form>
            <button class="cancel_button">Cancel Appointment</button>
        </div>
    </div>

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
                document.getElementById('statusText').textContent = booking.status;
                
                document.getElementById('popup').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            }
        }
    }

    function hidePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
    </script>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
      $(function() {
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