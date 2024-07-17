<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIPS</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=More+Sugar&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="path/to/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="pet.css">
</head>

<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== true) {
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=sample.php');
    exit;
}
?>

<body>
    <!-- Mobile Nav -->
    <div class="navbar">
        <a href="Homepage.php" ><i class="fa-solid fa-house"></i><br>Home</a>
        <a href="location.php"><i class="fa-solid fa-location-dot"></i><br>Location</a>
        <a href="book.php"><i class="fa-solid fa-plus"> </i> <br>Book</a>
        <a href="pet.php"><i class="fa-solid fa-paw"> </i><br>Pets</a>
        <a href="profile1.php"><i class="fa-solid fa-user"></i><br>Profile</a>
    </div>

<div class="navtop">

        <img  class="logo_nav_top" src="logo.png" >
    </div>

<header class="header">
    <div class="logo">
        <a href="#">
            <img class="logo" src="logo.png" alt="logo">
        </a>
    </div>
    <nav class="nav">
        <ul class="main-nav">
            <li><a href="Homepage.php">Home</a></li>
            <li><a href="location.php">Location</a></li>
            <li class="book_button"><a href="book.php"><button>Book Now!</button></a></li>
            <li class="dropdown">
                <a href="#"><i class="fa-solid fa-user circle-icon"></i></a>
                <div class="dropdown-content">
                    <a href="profile1.php">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
</header>

<div class="Pet-header">Your Pets</div>
<button class="pet-button"><a href="addpet.php"> <h1>Add Pet</h1></a></button>

<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "capstone");

// Check connection
if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}

// Retrieve the user's pets
$ownerID = $_SESSION['ownerID'];
$query = "SELECT * FROM petinfo WHERE ownerID = '$ownerID'";
$result = mysqli_query($conn, $query);

// Display the pets
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <div class="curved-box">
            <div class="box-content">
                <p><?php echo $row['petname'];?></p>
                <img src="<?php echo $row['petphoto'];?>" alt="Pet Photo" class="add-photo-button">

            </div>
        </div>
        <?php
    }
} else {
    // echo "<p>No pets added yet!</p>";
}

// Close the database connection
mysqli_close($conn);
?>

</body>
</html>