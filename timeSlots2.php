<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Slot Selection</title>
</head>
<body>
    <h1>Select a Time Slot</h1>
    <form action="process_time_slot.php" method="post">
        <?php
        // Define the start and end times
        $startTime = 9; // 9 AM
        $endTime = 13; // 1 PM (13 in 24-hour format)

        // Loop through the hours and create radio buttons
        for ($hour = $startTime; $hour < $endTime; $hour++) {
            // Format the hour for display (AM/PM)
            $displayHour = ($hour > 12) ? ($hour - 12) . ' PM' : $hour . ' AM';
            $value = ($hour < 10) ? '0' . $hour . ':00' : $hour . ':00'; // Value for the radio button

            echo '<div>';
            echo '<input type="radio" id="timeSlot' . $hour . '" name="timeSlot" value="' . $value . '">';
            echo '<label for="timeSlot' . $hour . '">' . $displayHour . '</label>';
            echo '</div>';
        }
        ?>
        <input type="submit" value="Submit">
    </form>
</body>
</html>