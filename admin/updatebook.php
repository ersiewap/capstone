<?php
// Start the session
session_start();

// Enable error reporting for debugging (optional, can be removed in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include __DIR__ . '/db_connect.php'; // Adjust the path as necessary

// Check if the database connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the current date
$currentDate = date('Y-m-d');

// Prepare the SQL query to update the status of past appointments
$query = "UPDATE book SET status = 1 WHERE date < ? AND status = 0 AND is_cancelled = 0";

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $currentDate); // Assuming date is stored as a string in 'Y-m-d' format
$stmt->execute();

// Check if the query execution was successful
if ($stmt->affected_rows > 0) {
    echo "Updated " . $stmt->affected_rows . " appointment(s) to status 1.";
} else {
    echo "No appointments to update.";
}

// Close the statement and database connection
$stmt->close();
mysqli_close($conn);
?>