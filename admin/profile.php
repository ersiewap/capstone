<?php
session_start(); // Start the session to access session variables

// Database connection
$servername = "localhost"; // Your server name
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "capstone"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming the staff ID is stored in the session after login
$staff_id = $_SESSION['staff_id'];

// Query to get the salon ID from the staff table
$sql = "SELECT salonid FROM staff WHERE staffid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$stmt->bind_result($salon_id);
$stmt->fetch();
$stmt->close();

// Query to get the salon name from the salon table
$sql = "SELECT shopname FROM salon WHERE salonid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $salon_id);
$stmt->execute();
$stmt->bind_result($shopname);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="profile.css" />
  </head>
  <body>
    <!-- Mobile Nav -->
    <div class="navbar">
      <a href="admin.php"><i class="fa-solid fa-house"></i><br />Dashboard</a>
      <a href="report.php"><i class="fa-solid fa-newspaper"></i><br />Reports</a>
      <a href="profile.php"><i class="fa-solid fa-user"></i><br />Admin</a>
    </div>
    <!-- Content -->
    <div id="all">
      <div>
        <div class="dashboard">Profile</div>
        <div class="salon_name"><?php echo htmlspecialchars($shopname); ?></div>
      </div>
      <div class="logout">
        <a style="color: black; text-decoration: none;" href="login_staff.php">Log Out</a>
      </div>
    </div>
  </body>
</html>