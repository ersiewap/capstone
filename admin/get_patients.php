<?php
require_once 'db_connect.php';

// Get the salon_id from the logged in admin
$salon_id = $_SESSION['salonid'];

// Query to retrieve data from the database
$sql = "SELECT * FROM patients WHERE salon_id = '$salon_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
} else {
    echo "No data found";
}

$conn->close();

// Convert the data to JSON
echo json_encode($patients);
?>