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

// Get the pet name from the URL parameter
$petName = $_GET['petname'];

// Retrieve pet details from the database
$stmt = $conn->prepare("SELECT * FROM petinfo WHERE petname = ?");
$stmt->bind_param("s", $petName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $petDetails = $result->fetch_assoc();
    echo json_encode($petDetails);
} else {
    $error = array('error' => 'Pet not found');
    echo json_encode($error);
}

// Close the database connection
$conn->close();
?>