<?php

session_start();

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

// Close the database connection
mysqli_close($conn);
?>