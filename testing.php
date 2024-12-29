<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>
</head>
<body>
    <form action="send.php" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required> <br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required> <br>

        <label for="booking_date">Booking Date:</label>
        <input type="date" name="booking_date" id="booking_date" required> <br>

        <label for="message">Message:</label>
        <textarea name="message" id="message" required></textarea> <br>

        <button type="submit" name="book">Book Now</button>
    </form>
</body>
</html>
