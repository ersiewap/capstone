<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>MIPS</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=More+Sugar&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="Homepage.css">
</head>

<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== true) {
    
  echo "<script>alert('You need to log in to view this page.');</script>";
  header('refresh:2; url=sample.php');
  exit;
}
?>


<style>
  @font-face {
        font-family:MoreSugar ;
        src: url(MoreSugar-Regular.ttf);
    }
</style>
<body>
<!-- Mobile Nav -->
<div class="navbar">
  <a href="Homepage.php"><i class="fa-solid fa-house"></i><br>Home</a>
  <a href="location.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
  <a href="book.php"><i class="fa-solid fa-plus"> </i><br>Book</a>
  <a href="pet.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
  <a href="profile1.php"><i class="fa-solid fa-user"></i><br>Profile</a>
</div>

<header class="header">
    <div class="logo-container">
        <a href="Homepage.php">
            <img class="logo" src="logo.png" alt="logo">
        </a>
    </div>

<!-- Web Nav -->
    <nav class="nav">
        <ul class="main-nav">
            <li><a href="Homepage.php">Home</a></li>
            <li><a href="location.php">Location</a></li>
            <li class="book_button"><a href="book.php#booking"><button>Book Now!</button></a></li>
            <li class="dropdown">
                <a href="#"><i class="fa-solid fa-user circle-icon"></i></a>
                <div class="dropdown-content">
                    <a href="profile1.php">Profile</a>
                    <a href="logout.php">Logout</a> <!-- Corrected the href attribute -->
                </div>
            </li>
        </ul>
    </nav>
</header>

<div class="upper-container">
    <div class="Name">
        <h1>Hello, <?php echo htmlspecialchars($_SESSION['ownerfname']); ?>!</h1>
        <h2>Your Appointments</h2>
    </div>

    <div class="curved-box">
    </div>
</div>

<div class="yellow-background">
    <div class="text-container">
        <h3>Book Your</h3>
        <h3>Pet's Appointment</h3>
        <h5>Today!</h5>
        <a href="book.php#booking" class="book-button" style ="background-color: #602147;
  color: #FFD700;
  width: 50%;
  height: 50px;
  font-size: x-large;
  border-radius: 6px;
  font-family: 'Sniglet', cursive;
  display: flex;
  justify-content: center;
  align-items: center;" >Book Now!</a>
    </div>
    <div class="imagelogo"><img class="logo1" src="icon.png" alt="logo"></div>
</div>

<!-- Pet Salon -->
<div class="pet-container">
  Pet Salon
  <div class="row">
    <div class="square">
      <div class="picture">
        <img src="Davids.jpg">
        <div class="popup">
          <div class="popup-info">
            <div>
              <div class="fire-icons">
                <div class="on">
                  <i class="fa-solid fa-location-dot"></i>
                </div>
              </div>
              <span class="popup-category">Stall 6 Pacifica Plaza 4103, Imus, Philippines · Buhay na Tubig, Philippines</span>
            </div>
            <div>
              <div class="fire-icons">
                <div class="on">
                  <i class="fa-solid fa-phone"></i>
                </div>
              </div>
              <span class="popup-category">0977 007 9908</span>
            </div>
            <div>
              <div class="fire-icons">
                <div class="on">
                  <i class="fa-solid fa-envelope"></i>
                </div>
              </div>
              <span class="popup-category">jynrdkingdavid@gmail.com</span>
            </div>
          </div>
        </div>
      </div>
      <div class="name">
        <h2>David's Pet Salon</h2>
      </div>
    </div>
    <div class="square">
      <div class="picture">
        <img src="vetterhealth.jpg">
        <div class="popup">
          <div class="popup-info">
            <div>
              <div class="fire-icons">
                <div class="on">
                  <i class="fa-solid fa-location-dot"></i>
                </div>
              </div>
              <span class="popup-category">9063 Vetter Health Animal Clinic, Gov. D. Mangubat Ave. Brgy. Burol Main, Dasmarinas, Philippines</span>
            </div>
            <div>
              <div class="fire-icons">
                <div class="on">
                  <i class="fa-solid fa-phone"></i>
                </div>
              </div>
              <span class="popup-category">0923 170 6371</span>
            </div>
          </div>
        </div>
      </div>
      <div class="name">
        <h2>Vetter Health Animal Clinic and Livestock Consultancy</h2>
      </div>
    </div>
    <div class="square">
      <div class="picture">
        <img src="Kanji.jpg">
        <div class="popup">
          <div class="popup-info">
            <div>
              <div class="fire-icons">
                <div class="on">
                  <i class="fa-solid fa-location-dot"></i>
                </div>
              </div>
              <span class="popup-category">Mantele Apartelle Unit 4, Dasmariñas, Philippines, 4114</span>
            </div>
            <div>
              <div class="fire-icons">
                <div class="on">
                  <i class="fa-solid fa-phone"></i>
                </div>
              </div>
              <span class="popup-category">0927 350 8308</span>
            </div>
            <div>
              <div class="fire-icons">
                <div class="on">
                  <i class="fa-solid fa-envelope"></i>
                </div>
              </div>
              <span class="popup-category">kanjipetservices@gmail.com</span>
            </div>
          </div>
        </div>
      </div>
      <div class="name">
        <h2>Kanji's Pet Grooming</h2>
      </div>
    </div>
  </div>
</div>

<footer></footer>
</body>
</html>
