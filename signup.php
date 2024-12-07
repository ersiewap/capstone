<?php
        include('dbconnection.php');
        // getting all values from the HTML form
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $Owneremail = $_POST['owneremail'];
            $OwnerFname = $_POST['ownerfname'];
            $OwnerLname = $_POST['ownerlname'];
            $Ownerpass = $_POST['ownerpass'];
            $Ownernum = $_POST['ownernumber'];

            // Hash the password using password_hash()
            $hashed_password = password_hash($Ownerpass, PASSWORD_DEFAULT);

            // Use prepared statements to prevent SQL injection
            $stmt = $con->prepare("insert into registration_info (owneremail, ownerfname, ownerlname, ownerpass, ownernum) values (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $Owneremail, $OwnerFname, $OwnerLname, $hashed_password, $Ownernum);
            $stmt->execute();

            if($stmt->affected_rows > 0) {
                echo "<script>alert('Successfully created Account!. Please Login again')</script>";
                echo "<script type= 'text/javascript'>document.location = 'Login&Register.php';</script>";
            }    else {
                echo "<script>alert('There was an error')</script>";
            }
        }
    ?>