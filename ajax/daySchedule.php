<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "capstone";


    $startDate = $_GET["date"];
    $schedules = [];
    $salon_id = $_GET["salon_id"];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT GROUP_CONCAT(DATE_FORMAT(book.time, '%H:%i')) 'time' FROM `book` WHERE book.date = '$startDate' AND salonid = '$salon_id'  ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row['time']; 
        
    }
    echo json_encode($schedules);
    exit;

