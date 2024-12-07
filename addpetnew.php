<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== true) {
    
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=sample.php');
    exit;
}

// Connect to your database
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
$ownerID = $_SESSION['ownerID']; // assuming you have a session variable set

// Get the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $petname = $_POST['petname'];
    $petbirth = $_POST['petbirth'];
    $petgender = $_POST['petgender'];
    $petspecies = $_POST['petspecies'];
    $petbreed = $_POST['petbreed'];
    $fileInput = $_FILES['fileInput'];

    // Handle file upload
    $target_dir = "uploads/"; // adjust this to your desired upload directory
    $target_file = $target_dir . basename($fileInput["name"]);
    $image_path = $target_file; // store the file path in the database

    // Validate user input
    if (empty($petname) || empty($petbirth) || empty($petgender) || empty($petspecies) || empty($petbreed)) {
        echo "Please fill in all fields";
        exit;
    }

    // Insert the data into the database
    $sql = "INSERT INTO petinfo (ownerID, petname, petbirth, petgender, petspecies, petbreed, petphoto) 
VALUES ('$ownerID', '$petname', '$petbirth', '$petgender', '$petspecies', '$petbreed', '$image_path')";
    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: ". $sql. "<br>". mysqli_error($conn);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($fileInput["tmp_name"], $target_file)) {
        echo "File uploaded successfully";
    } else {
        echo "Error uploading file";
    }
}

// Retrieve pet information for the currently logged-in account
$sql = "SELECT * FROM petinfo WHERE ownerID = '$ownerID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $pets = array();
    while($row = $result->fetch_assoc()) {
        $petID = $row['petid'];
        $petName = $row['petname'];
        $petBirth = $row['petbirth'];
        $petGender = $row['petgender'];
        $petSpecies = $row['petspecies'];
        $petBreed = $row['petbreed'];
        $petPhoto = $row['petphoto'];

        $pets[] = array(
            'petID' => $petID,
            'petName' => $petName,
            'petBirth' => $petBirth,
            'petGender' => $petGender,
            'petSpecies' => $petSpecies,
            'petBreed' => $petBreed,
            'petPhoto' => $petPhoto
        );
    }
} else {
    $pets = array();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="addpetnew.css">

</head>


<body>
    
   

<main >
     <!-- Mobile Nav -->
     <div class="navbar">
        <a href="HomeNew.php" ><i class="fa-solid fa-house"></i><br>Home</a>
        <a href="LocationNew.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
        <a href="book.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
        <a href="addpetnew.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
        <a href="Serv.php"><i class="fa-solid fa-briefcase"></i> </i><br>Services</a>
        <a href="YourProfile.php"><i class="fa-solid fa-user"></i><br>Profile</a>
      </div>
  
  <div class="navtop">
  
          <img  class="logo_nav_top" src="logo.png" >
      </div>
  

    
    <header class="header">
        <div class="logo">
            <a href="#">
                <img class="logo" src="logo.png" alt="logo">
            </a>
        </div>
        <nav class="nav">
            <ul class="main-nav">
                <li><a href="HomeNew.php">Home</a></li>
                <li><a href="Serv.php">Services</a></li>
                <li><a href="LocationNew.php">Location</a></li>
                <li class="book_button"><a href="book.php"><button>BOOK NOW!</button></a></li>
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

    <div id="bottom_container">
         <!-- SIDE NAV -->
            <div class="sidenav">
                <h1 style="text-align: center; font-family: 'Lora', serif;">Your Pets</h1>
                <button id="addPetButton" class="addpet"><a href="#" data-toggle="modal" data-target="#addPetModal">Add a Pet</a></button>
                <div id="petList"></div>
            </div>
            <div class="main-content"  >
                <!-- Add pet Form -->
                <div id="petDetailsForm" class="pet-details-form" style="display: none;">
                    <h2>Add New Pet Details</h2>
                    <form id="petForm">
                        <div class="center-button">
                            <label for="fileInput" class="pet-button-picture" id="uploadButton">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                            <input style="display:none;" type="file" id="fileInput" accept="image/*">
                            <div class="text-pet">Add Photo</div>
                        </div>
                        <label for="petName">Name:</label>
                        <input type="text" id="petNameInput" name="petName" required><br>
                
                        <label for="petBirthday">Birthday:</label>
                        <input type="date" id="petBirthdayInput" name="petBirthday" required><br>
                
                        <label for="petSpecies">Species:</label>
                        <input type="text" id="petSpeciesInput" name="petSpecies" required><br>
                
                        <label for="petBreed">Breed:</label>
                        <input type="text" id="petBreedInput" name="petBreed" required><br>
                
                        <label for="petSex">Sex:</label>
                        <select id="petSexInput" name="petSex" required>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select><br>
                
                        <button type="submit" class="submit-button" >Add Pet</button>
                        <!-- Add a div to display the success message -->
                        <div id="successMessage" style="display: none; color: green;"></div>
                    </form>
                </div>
                <!-- Content -->
                <div id="petList">
                    <div class="pet-details">
                        <h2 style="text-align: center; font-family: 'Lora', sans-serif; color: #602147;">Pet Information</h2>
                        <img id="petImage" src="" alt="Pet" style="width:150px;height:150px;">
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Name:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petName"></span></p>
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Birthday:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petBirthday"></span></p>
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Sex:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petSex"></span></p>
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Species:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petSpecies"></span></p>
                        <p style="font-family: 'Open Sans', sans-serif;"><strong>Breed:</strong> <span style="font-family: 'Poppins', sans-serif;" id="petBreed"></span></p>
                    </div>
                    <div class="pet-history">
                        <h2>Pet History</h2>
                        <table class="appointments_table">
                        <tr>
                            <th>Reference Number</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Salon</th>
                            <th>Payment Method</th>
                            <th>Total Fee</th>
                            <th>Appointment Status</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </table>
                    </div>
                    </div>
                
                
            </div>
            
        </div>
    </div>
</main>
<script>
function getPetList() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "getPetList.php", true);
    xhr.send();
    xhr.onload = function() {
        if (xhr.status === 200) {
            var petList = JSON.parse(xhr.responseText);
            var html = "";
            for (var i = 0; i < petList.length; i++) {
                html += "<a href='#' data-petname='" + petList[i].petname + "'>" + petList[i].petname + "</a><br>";
            }
            document.getElementById("petList").innerHTML = html;
        } else {
            console.log("Error getting pet list: " + xhr.statusText);
        }
    };
}

    window.onload = getPetList;
</script>

<script>
// Get the pet list element
const petList = document.getElementById('petList');

// Add a click event handler to each pet list item
petList.addEventListener('click', function(event) {
  if (event.target.tagName === 'A') {
    const petName = event.target.textContent;
    console.log(`Pet Name: ${petName}`); // Verify the pet name is being passed correctly

    // Get the pet details from the PHP script
    getPetDetails(petName);
  }
});

// Function to get pet details from the PHP script
function getPetDetails(petName) {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', `getPetDetails.php?petname=${petName}`);
  xhr.onload = () => {
    if (xhr.status === 200) {
      const petDetails = JSON.parse(xhr.responseText);
      displayPetDetails(petDetails);
    } else {
      console.log('Error getting pet details:', xhr.statusText);
    }
  };
  xhr.send();
}

// Function to display pet details
function displayPetDetails(petDetails) {
  const petImage = document.getElementById('petImage');
  const petName = document.getElementById('petName');
  const petBirthday = document.getElementById('petBirthday');
  const petSex = document.getElementById('petSex');
  const petSpecies = document.getElementById('petSpecies');
  const petBreed = document.getElementById('petBreed');

  petImage.src = petDetails.petphoto;
  petName.textContent = petDetails.petname;
  petBirthday.textContent = petDetails.petbirth;
  petSex.textContent = petDetails.petgender;
  petSpecies.textContent = petDetails.petspecies;
  petBreed.textContent = petDetails.petbreed;
}
</script>


<!-- Script for Add pet form -->
<script>
    // For Photo Upload
  document.getElementById('uploadButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                console.log('Selected file:', file.name);
                // You can add your own logic to handle the selected file here
            }
        });

    // Show the form when the "Add Pet" button is clicked
    document.getElementById('addPetButton').addEventListener('click', function() {
        // Toggle visibility of the form
        
        const petForm = document.getElementById('petDetailsForm');
        petForm.style.display = petForm.style.display === 'block' ? 'none' : 'block';
        const petList = document.getElementById('petList');
        petList.style.display = petList.style.display === 'none' ? 'block' : 'none';

    });
   

        // Handle form submission
        document.getElementById('petForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the default way

        // Get form data
        const petName = document.getElementById('petNameInput').value;
        const petBirthday = document.getElementById('petBirthdayInput').value;
        const petSpecies = document.getElementById('petSpeciesInput').value;
        const petBreed = document.getElementById('petBreedInput').value;
        const petSex = document.getElementById('petSexInput').value;
        const fileInput = document.getElementById('fileInput');

        // Create a new FormData object
        const formData = new FormData();
        formData.append('petname', petName);
        formData.append('petbirth', petBirthday);
        formData.append('petspecies', petSpecies);
        formData.append('petbreed', petBreed);
        formData.append('petgender', petSex);
        formData.append('fileInput', fileInput.files[0]);

        // Send the form data to the PHP script
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'addPet.php', true);
        xhr.send(formData);

        // Handle the response from the PHP script
        xhr.onload = () => {
        if (xhr.status === 200) {
            console.log('Pet added successfully!');
            // Display a success message
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = 'Pet added successfully!';
            successMessage.style.display = 'block';

            // Refresh the pet list
            getPetList();
        } else {
            console.log('Error adding pet:', xhr.statusText);
        }
        };
        });
</script>


</body>
</html> 
