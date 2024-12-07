<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="report.css">
</head>
<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "capstone";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for the report
$reportType = $_GET['reportType'] ?? 'Yearly'; // Default report type
$startDate = $_GET['startDate'] ?? date('Y-m-d', strtotime('-1 year')); // Default start date
$endDate = $_GET['endDate'] ?? date('Y-m-d'); // Default end date

// Retrieve the logged-in staff's salon ID
$salonId = $_SESSION['salonid'];

// Prepare SQL query based on the report type and salon ID
$sql = "SELECT b.bookID, b.ownerID, b.petid, b.salonid, 
              GROUP_CONCAT(s.servicename SEPARATOR ', ') AS servicenames, 
              b.date, b.time, b.paymentmethod, b.is_cancelled, 
              b.cancel_date, b.status, b.paymentprice 
        FROM book b 
        JOIN services s ON FIND_IN_SET(s.serviceid, b.serviceid) > 0 
        WHERE b.date BETWEEN ? AND ? AND b.salonid = ? 
        GROUP BY b.bookID";

// Prepare statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $startDate, $endDate, $salonId); // Bind salonId as an integer
$stmt->execute();
$result = $stmt->get_result();
?>

<body>
    <!-- Mobile Nav -->
    <div class="navbar">
      <a href="admin.php"><i class="fa-solid fa-house"></i><br />Dashboard</a>
      <a href="report.php"><i class="fa-solid fa-newspaper"></i><br />Reports</a>
      <a href="services.php"><i class="fa-solid fa-briefcase"></i><br />Services</a>
      <a href="profile.php"><i class="fa-solid fa-user"></i><br />Admin</a>
    </div>

    <nav class="sidebar">
        <header>
            <div class="logo">
                <img class="logo_1" src="logo-nav.png" >
            </div>
        </header>
        <div class="menu-bar">
          <div class="menu">
            <ul class="menu-link">
              <li class="nav-link"><a href="admin.php">Dashboard</a></li>
              <li class="nav-link"><a href="report.php">Reports</a></li>
              <li class="nav-link"><a href="services.php">Services</a></li>
            </ul>
            <ul class="menu-sign">
              <li class="nav-link profile">
                <a href="#">
                  <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
                  <span class="title nav">Admin</span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href ="profile.php">Profile</a></li>
                  <li><a href="login_staff.php">Logout</a ```html
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
    </nav>

    <main>
        <h1>Report from <?php echo htmlspecialchars($startDate); ?> to <?php echo htmlspecialchars($endDate); ?></h1>
        
        <!-- Form for selecting date range and report type -->
        <form method="GET" action="report.php" class="report-form">
            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>" required>
            
            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" name="endDate" value="<?php echo htmlspecialchars($endDate); ?>" required>
            
            <label for="reportType">Report Type:</label>
            <select id="reportType" name="reportType">
                <option value="Yearly" <?php echo $reportType == 'Yearly' ? 'selected' : ''; ?>>Yearly</option>
                <option value="Monthly" <?php echo $reportType == 'Monthly' ? 'selected' : ''; ?>>Monthly</option>
                <option value="Weekly" <?php echo $reportType == 'Weekly' ? 'selected' : ''; ?>>Weekly</option>
                <option value="Daily" <?php echo $reportType == 'Daily' ? 'selected' : ''; ?>>Daily</option>
            </select>
            
            <button type="submit" class="btn">Generate Report</button>
            <!-- Assuming you have the necessary variables set -->
            <a href="generate_report.php?startDate=<?php echo urlencode($startDate); ?>&endDate=<?php echo urlencode($endDate); ?>&salonid=<?php echo urlencode($_SESSION['salonid']); ?>" target="_blank">
              <button type="button" class="btn">Download PDF</button>
            </a>
        </form>

        <table id="reportTable" class="report-table">
        <thead>
    <tr>
        <th>BookID</th>
        <th>OwnerID</th>
        <th>PetID</th>
        <th>SalonID</th>
        <th style="width: 250px;">Service Names</th> <!-- Update header to reflect multiple service names -->
        <th>Date</th>
        <th>Time</th>
        <th>Payment Method</th>
        <th>Is Cancelled</th>
        <th>Cancel Date</th>
        <th>Status</th>
        <th>Payment Price</th>
    </tr>
</thead>
<tbody>
    <?php
    // Fetch data from the result set and output as table rows
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['bookID']) . '</td>';
        echo '<td>' . htmlspecialchars($row['ownerID']) . '</td>';
        echo '<td>' . htmlspecialchars($row['petid']) . '</td>';
        echo '<td>' . htmlspecialchars($row['salonid']) . '</td>';
        echo '<td>' . htmlspecialchars($row['servicenames']) . '</td>'; // Displaying concatenated service names
        echo '<td>' . htmlspecialchars($row['date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['time']) . '</td>';
        echo '<td>' . htmlspecialchars($row['paymentmethod']) . '</td>';
        echo '<td>' . htmlspecialchars($row['is_cancelled']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cancel_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '<td>' . htmlspecialchars($row['paymentprice']) . '</td>';
        echo '</tr>';
    }
    ?>
</tbody>
        </table>
    </main>
</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$conn->close();
?>