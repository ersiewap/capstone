<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIPS</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=More+Sugar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="path/to/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="addpet.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>


</head>

<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== true) {
    
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=sample.php');
    exit;
}



?>

<body>

<div class="nav">
    <div>
        <a href="pet.php"><i class="fa-solid fa-arrow-left arrow_left"></i></a>
        Edit Profile
    </div>
</div>

<!-- Mobile Nav -->
<div class="navbar">
  <a href="Homepage.php" ><i class="fa-solid fa-house"></i><br>Home</a>
  <a href="location.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
  <a href="book.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
  <a href="pet.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
  <a href="profile1.php"><i class="fa-solid fa-user"></i><br>Profile</a>
</div>


<section>

    <div class="add-pet-container">
        <form class="add-pet-form" method="POST" action="addpet1.php">
            <div class="center-button">
                <label for="fileInput" class="pet-button-picture" id="uploadButton">
                    <i class="fa-solid fa-camera"></i>
                </label>
                <input style="display:none;" type="file" id="fileInput" accept="image/*">
                <div class="text-pet">Add Photo</div>
            </div>
<div class="main-container">
            <!-- Input Pet Name -->
            <div id="petInput">
                <label class="text-input" for="petname">Input Pet Name:</label>
                <input class="pet-input" type="text" id="petname" name="petname">
            </div>

            <div class="Birthandgen">
            <!-- Birthday -->
            <div class="container">
                <label class="birthday-text" for="birthday">Birthday:</label>
                <input class="birthday-input" type="date" id="petbirth" name="petbirth">
            </div>

            <!-- Gender -->
            <div class="Gender">
                <label class="sex-text" for="gender">Sex:</label>
                <select class="gender-dropdown" id="petgender" name="petgender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            </div>


            <!-- Species -->
            <div >
                <label class="species-text" for="species">Species:</label>
                <input class="species-input" type="text" id="petspecies" name="petspecies">
            </div>

            <!-- Breed -->
            <div >
                <label class="breed-text" for="breed">Breed:</label>
                <input class="breed-input" type="text" id="petbreed" name="petbreed">
            </div>
            </div>

            <!-- Submit Button -->
            <a id="confirm-add-pet" class="cd-popup-trigger book_button">Add Pet</a>
            <div class="cd-popup" role="alert">
                <div class="cd-popup-container">
                    <i id="initial-icon" class="fa-solid fa-paw"></i>
                    <p class="popup-text">Are you sure you want to add a pet?</p>
                    <div id="confirmation-section" style="display: none;">
                        <i class="fa-solid fa-circle-check"></i>
                        <p class="confirmation-text">Pet added successfully!</p>
                    </div>
                    <ul class="cd-buttons">
                        <li><input type="submit" value="Add Pet" class="yes-button" onclick="submitForm()"/></li>
                        <li><input type="button" value="Cancel:" class="no-button"/></li>
                    </ul>
                    <a href="#" class="cd-popup-close img-replace">Close</a>
                </div> <!-- cd-popup-container -->
            </div> <!-- cd-popup -->
        </form>


    </div>


</section>
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

</script>

<script>
    function submitForm() {
        // Get the form data
        var petname = document.getElementById('petname').value;
        var petbirth = document.getElementById('petbirth').value;
        var petgender = document.getElementById('petgender').value;
        var petspecies = document.getElementById('petspecies').value;
        var petbreed = document.getElementById('petbreed').value;
        var fileInput = document.getElementById('fileInput');

        // Create a FormData object
        var formData = new FormData();
        formData.append('petname', petname);
        formData.append('petbirth', petbirth);
        formData.append('petgender', petgender);
        formData.append('petspecies', petspecies);
        formData.append('petbreed', petbreed);
        formData.append('fileInput', fileInput.files[0]);

        // Send the data to the server using XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'addpet1.php', true);
        xhr.onload = function() {
            console.log('xhr.onload called');
            document.getElementById('confirmation-section').style.display = 'block';
        };
        xhr.send(formData);
    }
</script>

<!-- <script>
    function confirmBooking() {
    // Hide the booking text and icon
    document.querySelector('.popup-text').style.display = 'none';
    document.getElementById('booking-icon').style.display = 'none';
    // Show the confirmation text
    document.querySelector('.confirmation-text').style.display = 'block';
}

</script>
<script>function confirmBooking() {
    // Hide the initial booking text and icon
    document.querySelector('.popup-text').style.display = 'none';
    document.getElementById('initial-icon').style.display = 'none';
    // Show the confirmation section
    document.getElementById('confirmation-section').style.display = 'block';
} -->
</script>
</body>
</html>