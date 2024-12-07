<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="Serv.css".css">

</head>
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
<!--Web Nav -->
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
            <a href="YourProfile.php  ">Profile</a>
            <a href="logout.php">Logout</a>
            </div>
        </li>
    </ul>
</nav>
</header>
    <div class="navtop">
      
        <img  class="logo_nav_top" src="logo-nav.png" >
    </div>
    
<!-- Full-Width Image with Text and Button -->
<div class="full-width-image-container">
    <img src="servicesbanners.png" alt="Beautiful Scenery">
    <div class="text-overlay">
      <h1>Pet Salon</h1>
      <h2>Pricelists</h2>
    </div>
  </div>
  
  <!-- Tables -->
  
  <div class="service-container">
      <div class="service-header">
          <h2>Services</h2>
          <div class="dropdown1">
              <select id="salonDropdown" onchange="showPriceCards()">
                  <option value="davids-pet-salon">David's Pet Salon</option>
                  <option value="vetter-health-clinic">Vetter Health Clinic</option>
                  <option value="kanjis-pet-grooming">Kanji's Pet Grooming</option>
              </select>
          </div>
      </div>
  </div>
  
  <!-- Price Cards Containers -->
  <div id="davids-pet-salon" class="price-cards-container-wrap">
    <div class="price-card">
      <div class="card-header1">Full Groom</div>
      <div class="card-content">
        <p><span>Small</span><span>450.00</span></p>
        <p><span>Medium</span><span>650.00</span></p>
        <p><span>Large</span><span>900.00</span></p>
        <p><span>Extra Large</span><span>1400.00</span></p>
      </div>
    </div>
  
    <div class="price-card">
      <div class="card-header2">Bath and Blow Dryer</div>
      <div class="card-content">
        <p><span>Small</span><span>350.00</span></p>
        <p><span>Medium</span><span>400.00</span></p>
        <p><span>Large</span><span>550.00</span></p>
        <p><span>Extra Large</span><span>650.00</span></p>
      </div>
    </div>
  </div>
  
  <div class="price-cards-container">
    <div class="price-card">
      <div class="card-header3">Heavy Dematting</div>
      <div class="card-content">
        <p><span>Small</span><span>150.00</span></p>
        <p><span>Medium</span><span>150.00</span></p>
        <p><span>Large</span><span>200.00</span></p>
        <p><span>Extra Large</span><span>300.00</span></p>
      </div>
    </div>
  
    <div class="price-card">
      <div class="card-header4">Ala Carte</div>
      <div class="card-content">
        <p><span>Nail Trim</span><span>50.00</span></p>
        <p><span>Teeth Brushing</span><span>50.00</span></p>
        <p><span>Ear Cleaning</span><span>50.00</span></p>
        <p><span>Face Trim</span><span>50.00</span></p>
        <p><span>Sanitary Trim</span><span>50.00</span></p>
      </div>
    </div>
  </div>
  
  <div id="vetter-health-clinic" class="price-cards-container-wrap" style="display:none;">
    <div class="price-card">
      <div class="card-header1">Full Groom</div>
      <div class="card-content">
        <p><span>Small</span><span>500.00</span></p>
        <p><span>Medium</span><span>700.00</span></p>
        <p><span>Large</span><span>950.00</span></p>
        <p><span>Extra Large</span><span>1450.00</span></p>
      </div>
    </div>
  
    <div class="price-card">
      <div class="card-header2">Bath and Blow Dryer</div>
      <div class="card-content">
        <p><span>Small</span><span>370.00</span></p>
        <p><span>Medium</span><span>420.00</span></p>
        <p><span>Large</span><span>580.00</span></p>
        <p><span>Extra Large</span><span>680.00</span></p>
      </div>
    </div>
  </div>
  
  <div id="kanjis-pet-grooming" class="price-cards-container-wrap" style="display:none;">
    <div class="price-card">
      <div class="card-header1">Heavy Dematting</div>
      <div class="card-content">
        <p><span>Small</span><span>150.00</span></p>
        <p><span>Medium</span><span>150.00</span></p>
        <p><span>Large</span><span>200.00</span></p>
        <p><span>Extra Large</span><span>300.00</span></p>
      </div>
    </div>
  
    <div class="price-card">
      <div class="card-header4">Ala Carte</div>
      <div class="card-content">
        <p><span>Nail Trim</span><span>50.00</span></p>
        <p><span>Teeth Brushing</span><span>50.00</span></p>
        <p><span>Ear Cleaning</span><span>50.00</span></p>
        <p><span>Face Trim</span><span>50.00</span></p>
        <p><span>Sanitary Trim</span><span>50.00</span></p>
      </div>
    </div>
  </div>
  
  <script>
    function showPriceCards() {
      // Hide all containers
      var containers = document.querySelectorAll('.price-cards-container-wrap');
      containers.forEach(function(container) {
        container.style.display = 'none';
      });
  
      // Show the selected container
      var selectedSalon = document.getElementById('salonDropdown').value;
      document.getElementById(selectedSalon).style.display = 'flex';
    }
  </script>
  
</body>
</html>