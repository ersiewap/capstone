<?php
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "capstone"; // Your database name

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Getting all values from the HTML form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Owneremail = trim($_POST['owneremail']);
    $OwnerFname = trim($_POST['ownerfname']);
    $OwnerLname = trim($_POST['ownerlname']);
    $Ownerpass = trim($_POST['ownerpass']);
    $Ownernum = trim($_POST['ownernumber']);

    // Validate that all fields are filled
    if (empty($Owneremail) || empty($OwnerFname) || empty($OwnerLname) || empty($Ownerpass) || empty($Ownernum)) {
        echo "<script>alert('Please fill in all fields.')</script>";
        echo "<script type='text/javascript'>document.location = 'Login&Register.php';</script>";
    } else {
        // Check if the email is already registered
        $checkEmailStmt = $con->prepare("SELECT * FROM registration_info WHERE owneremail = ?");
        $checkEmailStmt->bind_param("s", $Owneremail);
        $checkEmailStmt->execute();
        $result = $checkEmailStmt->get_result();

        if ($result->num_rows > 0) {
            // Email already exists
            echo "<script>alert('This email is already registered. Please use a different email.')</script>";
            echo "<script type='text/javascript'>document.location = 'Login&Register.php';</script>";
        } else {
            // Hash the password using password_hash()
            $hashed_password = password_hash($Ownerpass, PASSWORD_DEFAULT);

            // Use prepared statements to prevent SQL injection
            $stmt = $con->prepare("INSERT INTO registration_info (owneremail, ownerfname, ownerlname, ownerpass, ownernum) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $Owneremail, $OwnerFname, $OwnerLname, $hashed_password, $Ownernum);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Successfully created Account! Please Login again')</script>";
                echo "<script type='text/javascript'>document.location = 'Login&Register.php';</script>";
            } else {
                echo "<script>alert('There was an error during registration.')</script>";
                echo "<script type='text/javascript'>document.location = 'Login&Register.php';</script>";
            }
        }
    }
}
?>