<?php

session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "capstone");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user information from the database
$Owneremail = $_POST['owneremail'];
$Ownerpass = $_POST['ownerpass'];

// Hash the entered password
$hashed_password = password_hash($Ownerpass, PASSWORD_DEFAULT);

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM registration_info WHERE owneremail = ?");
$stmt->bind_param("s", $Owneremail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password_db = $row["ownerpass"];

    // Verify the password
    if (password_verify($Ownerpass, $hashed_password_db)) {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['owneremail'] = $row["owneremail"];
        $_SESSION['ownerpass'] = $row["ownerpass"];
        $_SESSION['ownerfname'] = $row["ownerfname"];
        $_SESSION['ownerlname'] = $row["ownerlname"];
        $_SESSION['ownernum'] = $row["ownernum"]; // Ensure this line is present to set the ownernum session variable
        $_SESSION['ownerID'] = $row["ownerID"];

        // Alert and redirect using JavaScript
        echo "<script>
            alert('Login successful!');
            window.location.href = 'HomeNew.php';
        </script>";
    } else {
        echo "<script>
            alert('Incorrect Email or Password');
            window.location.href = 'Login&Register.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Incorrect Email or Password');
        window.location.href = 'Login&Register.php';
    </script>";
}

$stmt->close();
$conn->close();
?>