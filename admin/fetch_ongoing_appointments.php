<?php
// Include the database connection file
include __DIR__ . '/db_connect.php'; // Use absolute path

if (!$conn) {
    error_log("Connection failed!", 3, "errors.log");
    exit;
}

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// SQL query to fetch ongoing and upcoming appointments
$select = "SELECT b.bookID, b.pet_name, b.owner_name, b.salon, 
                GROUP_CONCAT(s.servicename SEPARATOR ', ') AS services, 
                b.date, b.time, b.payment_method, b.total_fees, b.appointment_status 
        FROM book b 
        JOIN services s ON FIND_IN_SET(s.serviceid, b.serviceid) > 0 
        WHERE CONCAT(b.date, ' ', b.time) >= ? 
        GROUP BY b.bookID";

$stmt = $conn->prepare($select);
$stmt->bind_param("s", $currentDateTime);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row; // Push each row into the data array
}

// Debugging: Log the fetched data
error_log(print_r($data, true), 3, "debug.log");

echo json_encode($data); // Return the data as JSON
?>