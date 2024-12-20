<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="report2.css">
</head>
<body>
<?php
session_start();
require_once 'db_connect.php'; // Ensure this file contains the correct database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables for the report
$startDate = $_GET['startDate'] ?? null;
$endDate = $_GET['endDate'] ?? null;

$reports = []; // Initialize an empty array for reports

// Retrieve the logged-in admin's salon ID from the session
$salonId = $_SESSION['salonid'] ?? null; // Ensure salonid is stored in the session

// Check if both startDate and endDate are provided
if ($startDate && $endDate && $salonId) {
    // Prepare the SQL query based on the date range and salon ID
    $sql = "SELECT bookID, ownerID, petid, salonid, serviceid, date, time, paymentmethod, is_cancelled, cancel_date, status, paymentprice 
            FROM book 
            WHERE date BETWEEN ? AND ? AND salonid = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500); // Internal server error
        echo json_encode(["error" => "Failed to prepare SQL statement."]);
        exit;
    }

    $stmt->bind_param("ssi", $startDate, $endDate, $salonId); // Bind salonId as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch data from the result set
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

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
                  <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0  256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0  29.7-13.3 29.7-29.7c0-98.5-79.8-178.3-178.3-178.3H178.3z"/></svg>
                  <span class="text">Admin</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
    </nav>

    <main>
    <h1>Reports</h1>
    <div class="button-container">
        <form method="GET" action="report.php" style="display: inline;">
            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="startDate" required>
            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" name="endDate" required>
            <button type="submit">Generate Report</button>
        </form>

        <?php if (!empty($reports)): ?>
            <!-- Button to download the report as a PDF -->
            <button onclick="downloadPDF()">Download PDF</button>
        <?php endif; ?>
    </div>

    <?php if (empty($reports)): ?>
        <p>No reports available. Please select a date range to generate reports.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Owner ID</th>
                    <th>Pet ID</th>
                    <th>Salon ID</th>
                    <th>Service ID</th>
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
              <?php foreach ($reports as $report): ?>
                  <tr>
                      <td><?php echo htmlspecialchars($report['bookID'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($report['ownerID'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($report['petid'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($report['salonid'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($report['serviceid'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($report['date'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($report['time'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($report['paymentmethod'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($report['is_cancelled'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($report['cancel_date'] ?? ''); ?></td>
                      <td>
                          <?php 
                          // Check the status and display "Ongoing" if it's 0, otherwise display the actual status
                          echo htmlspecialchars($report['status'] == 0 ? 'Ongoing' : $report['status']); 
                          ?>
                      </td>
                      <td><?php echo htmlspecialchars($report['paymentprice'] ?? ''); ?></td>
                  </tr>
              <?php endforeach; ?>
              </tbody>
        </table>
    <?php endif; ?>
</main>

<script>
function downloadPDF() {
    const startDate = "<?php echo htmlspecialchars($startDate); ?>";
    const endDate = "<?php echo htmlspecialchars($endDate); ?>";
    const salonId = "<?php echo htmlspecialchars($salonId); ?>";

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'generate_report.php';
    form.target = '_blank'; // Open in new tab

    const startDateInput = document.createElement('input');
    startDateInput.type = 'hidden';
    startDateInput.name = 'startDate';
    startDateInput.value = startDate;
    form.appendChild(startDateInput);

    const endDateInput = document.createElement('input');
    endDateInput.type = 'hidden';
    endDateInput.name = 'endDate';
    endDateInput.value = endDate;
    form.appendChild(endDateInput);

    const salonIdInput = document.createElement('input');
    salonIdInput.type = 'hidden';
    salonIdInput.name = 'salonid';
    salonIdInput.value = salonId;
    form.appendChild(salonIdInput);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form); // Clean up
}
</script>
</body>
</html>