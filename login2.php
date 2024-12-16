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

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM registration_info WHERE owneremail = ?");
$stmt->bind_param("s", $Owneremail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password_db = $row["ownerpass"];

    // Check if the password is already hashed
    if (password_needs_rehash($hashed_password_db, PASSWORD_DEFAULT)) {
        // If the password is not hashed, compare it directly
        if ($Ownerpass === $hashed_password_db) {
            // Hash the password and update the database
            $new_hashed_password = password_hash($Ownerpass, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE registration_info SET ownerpass = ? WHERE owneremail = ?");
            $update_stmt->bind_param("ss", $new_hashed_password, $Owneremail);
            $update_stmt->execute();
            $update_stmt->close();

            // Set session variables and redirect
            $_SESSION['loggedin'] = true;
            $_SESSION['owneremail'] = $row["owneremail"];
            $_SESSION['ownerfname'] = $row["ownerfname"];
            $_SESSION['ownerlname'] = $row["ownerlname"];
            $_SESSION['ownernum'] = $row["ownernum"];
            $_SESSION['ownerID'] = $row["ownerID"];

            echo "<script>
                alert('Login successful! Your password has been updated.');
                window.location.href = 'HomeNew.php';
            </script>";
        } else {
            echo "<script>
                alert('Incorrect Email or Password');
                window.location.href = 'Login&Register.php';
            </script>";
        }
    } else {
        // Verify the password
        if (password_verify($Ownerpass, $hashed_password_db)) {
            // Set session variables and redirect
            $_SESSION['loggedin'] = true;
            $_SESSION['owneremail'] = $row["owneremail"];
            $_SESSION['ownerfname'] = $row["ownerfname"];
            $_SESSION['ownerlname'] = $row["ownerlname"];
            $_SESSION['ownernum'] = $row["ownernum"];
            $_SESSION['ownerID'] = $row["ownerID"];

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
    }
} else {
    echo "<script>
        alert('Incorrect Email or Password');
        window.location.href = 'Login&Register.php';
    </script>";
}

$stmt->close();
$conn->close();