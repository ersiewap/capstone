<?php
// database details
$con = mysqli_connect("localhost","root","","capstone");

if (mysqli_connect_errno()) {
    echo "Connection Failed" . mysqli_connect_error();
}
    // close connection
?>