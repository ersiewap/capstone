<?php
session_start();
require_once 'db_connect.php'; // Ensure this file contains the correct database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate input parameters
$startDate = $_GET['startDate'] ?? null;
$endDate = $_GET['endDate'] ?? null;

// Check if all required parameters are provided
if (!$startDate || !$endDate) {
    http_response_code(400); // Bad request
    echo json_encode(["error" => "Missing parameters."]);
    exit;
}

// Prepare the SQL query based on the date range
$sql = "SELECT BookID, OwnerID, PetID, SalonID, ServiceID, Date, Time, PaymentMethod, Is_Cancelled, Cancel_Date, Status, Payment_Price 
        FROM book 
        WHERE Date BETWEEN ? AND ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500); // Internal server error
    echo json_encode(["error" => "Failed to prepare SQL statement."]);
    exit;
}

$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

$reports = [];

// Fetch data from the result set
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

// Return the data as a JSON response
header('Content-Type: application/json'); // Set the content type to JSON
echo json_encode($reports);

// Close the statement and connection
$stmt->close();
$conn->close();
?>