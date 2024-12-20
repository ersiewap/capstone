<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "capstone";


    $startDate = $_GET["start"];
    $endDate   = $_GET["end"];
    $schedules = generateDateRange($startDate,$endDate);
    $salon_id = $_GET["salon_id"];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT book.date,IF(count(*) = 4,'Full','Available') 'title',IF(count(*) = 4,'full','available') 'status' FROM book WHERE book.date BETWEEN '$startDate' AND '$endDate' AND salonid = '$salon_id' GROUP BY book.date ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $schedules[$row['date']]['title'] = $row['title']; 
        $schedules[$row['date']]['start'] = $row['date'];
        $schedules[$row['date']]['status'] = $row["status"];
    }
    
    $formattedSched = flattenArray($schedules);
    echo json_encode($formattedSched);
    exit;



function generateDateRange($startDate, $endDate) {
    $dates = array();

    $start = new DateTime($startDate);
    
    //last day
    $date = new DateTime($startDate);
    $date->modify('last day of this month');
    $end = $date;
    $end->modify('+1 day');

    while ($start < $end) {
        $currDate = $start->format('Y-m-d'); 
        $dates[$currDate] = ["title" => "Available",
                            "start" => $currDate,
                            "status" => "available"];
        $start->modify('+1 day'); 
    }
    return $dates;
}

function flattenArray($datesArray){
    foreach ($datesArray as $date => $data) {
        if (@$data['status'] === 'full') {
            $newTitle = "Fully Booked";
        } else {
            $newTitle = "Available";
        }
    
        $transformedArray[] = [
            "title" => @$newTitle,
            "start" => @$data['start'],
            "status" => @$data['status']
        ];
    }
    return $transformedArray;
}